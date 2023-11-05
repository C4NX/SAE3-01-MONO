<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Animal;
use App\Entity\Appointment;
use App\Entity\Client;
use App\Entity\TypeAppointment;
use App\Entity\Veto;
use App\Form\AppointmentFormType;
use App\Repository\AgendaDayRepository;
use App\Repository\AppointmentRepository;
use App\Repository\UnavailabilityRepository;
use App\Repository\VacationRepository;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentsController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/appointments', name: 'app_appointments')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Client) {
            throw $this->createAccessDeniedException();
        }

        $appointments = $user->getAppointments();

        return $this->render('appointments/index.html.twig', [
            'appointments' => $appointments,
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/appointments/take', name: 'app_appointments_take')]
    public function take(Request $request, AppointmentRepository $appointmentRepository, AgendaDayRepository $agendaDayRepository, UnavailabilityRepository $unavailabilityRepository, VacationRepository $vacationRepository): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Client) {
            throw $this->createAccessDeniedException();
        }

        $appointmentsForm = $this->createForm(AppointmentFormType::class, options: ['client' => $user]);

        $appointmentsForm->handleRequest($request);
        if ($appointmentsForm->isSubmitted() && $appointmentsForm->isValid()) {
            /* @var \DateTime $appointmentDate */
            $appointmentDate = $appointmentsForm->get('date')->getData();

            /* @var Veto $appointmentVeto */
            $appointmentVeto = $appointmentsForm->get('vet')->getData();
            $appointmentAgenda = $appointmentVeto->getAgenda();

            /* @var TypeAppointment $appointmentType */
            $appointmentType = $appointmentsForm->get('type')->getData();

            /* @var Address $appointmentAddress */
            $appointmentAddress = $appointmentsForm->get('address')->getData();

            /* @var bool $appointmentUrgent */
            $appointmentUrgent = $appointmentsForm->get('isUrgent')->getData();

            /* @var string $appointmentNote */
            $appointmentNote = $appointmentsForm->get('note')->getData();

            /* @var Animal $appointmentAnimal */
            $appointmentAnimal = $appointmentsForm->get('animal')->getData();

            // need to be after now.
            $isAfterThatDate = $appointmentDate > new \DateTime();

            // get null or one appointment at the date, if there is an appointment, no appointment can be taken at this datetime.
            $appointmentAtDate = $appointmentRepository->getAppointmentAt($appointmentDate, $appointmentType, $appointmentVeto);
            $unavailabilityAtDate = $unavailabilityRepository->getUnavailabilityAt($appointmentDate, $appointmentType, $appointmentAgenda);
            $vacationAtDate = $vacationRepository->getVacationAt($appointmentDate, $appointmentAgenda);

            $isValidWorkDay = $appointmentAgenda->canTakeAt($appointmentDate, $agendaDayRepository, $appointmentType);

            $hasAppointment = null !== $appointmentAtDate;
            $hasUnavailability = null !== $unavailabilityAtDate;
            $hasVacation = null !== $vacationAtDate;

            // add errors
            if ($hasAppointment) {
                $appointmentsForm->get('date')->addError(new FormError("Impossible de prendre un rendez-vous a cette date, il y en a déjà un de {$appointmentAtDate->getType()->getDuration()} minutes"));
            }
            if ($hasUnavailability) {
                $appointmentsForm->get('date')->addError(new FormError("Le vétérinaire n'est pas disponible a cette date '{$unavailabilityAtDate->getLib()}', merci de consulter le planning du {$appointmentVeto->getDisplayName()}."));
            }
            if ($hasVacation) {
                $appointmentsForm->get('date')->addError(new FormError("Le vétérinaire est en vacances du {$vacationAtDate->getDateStart()->format('d/m/Y')} au {$vacationAtDate->getDateEnd()->format('d/m/Y')}."));
            }
            if (!$isValidWorkDay) {
                $appointmentsForm->get('date')->addError(new FormError("Cette journée est hors horaire pour le vétérinaire, merci de consulter le planning du {$appointmentVeto->getDisplayName()}."));
            }
            if (!$isAfterThatDate) {
                $appointmentsForm->get('date')->addError(new FormError('La date ne peut pas être antérieur à la date actuelle.'));
            }

            // If
            // - No appointment found at this date
            // - No unavailability found at this date
            // - No vacation found at this date
            // - Is a valid day of work for the vet
            // - Date is correct
            if (!$hasAppointment
                && !$hasUnavailability
                && !$hasVacation
                && $isValidWorkDay
                && $isAfterThatDate) {
                $appointment = new Appointment();
                $appointment->setType($appointmentType);
                $appointment->setVeto($appointmentVeto);
                $appointment->setClient($user);
                $appointment->setAddress($appointmentAddress);
                $appointment->setDateApp($appointmentDate);
                $appointment->setIsCompleted(false);
                $appointment->setIsUrgent($appointmentUrgent);
                $appointment->setNote($appointmentNote);
                $appointment->setAnimal($appointmentAnimal);

                // pre-calc of the end datetime.
                $appointment->setDateEnd((clone $appointmentDate)->modify("+{$appointmentType->getDuration()} minute"));

                $appointmentRepository->save($appointment, true);

                return $this->redirectToRoute('app_appointments');
            }
        }

        return $this->renderForm('appointments/take.html.twig', [
            'appointment_form' => $appointmentsForm,
        ]);
    }
}
