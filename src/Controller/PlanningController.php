<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\AgendaDay;
use App\Entity\Unavailability;
use App\Entity\Vacation;
use App\Entity\Veto;
use App\Form\AgendaFormType;
use App\Form\UnavailabilityFormType;
use App\Form\UnavailabilityRepeatedFormType;
use App\Form\VacationFormType;
use App\Repository\AgendaDayRepository;
use App\Repository\AgendaRepository;
use App\Repository\AppointmentRepository;
use App\Repository\UnavailabilityRepository;
use App\Repository\VacationRepository;
use App\Repository\VetoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlanningController extends AbstractController
{
    #[Route('/planning', name: 'app_planning')]
    public function index(VetoRepository $vetoRepository): Response
    {
        return $this->render('planning/index.html.twig', [
            'vets' => $vetoRepository->findAll(),
        ]);
    }

    #[Route('/planning/{id}/',
        name: 'app_planning_show',
        requirements: ['id' => "\d+"])]
    public function show(Agenda $agenda, AppointmentRepository $appointmentRepository, UnavailabilityRepository $unavailabilityRepository, VacationRepository $vacationRepository, Request $request): Response
    {
        $weekOffset = $request->query->get('offset', 0);

        $allApps = $appointmentRepository->findByVetoOnWeek($agenda->getVeto(), $weekOffset);

        // date('monday this week')|format_date
        $firstDayOfWeek = (new \DateTime('monday this week'))->modify("+{$weekOffset} week");
        $lastDayOfWeek = (new \DateTime('sunday this week'))->modify("+{$weekOffset} week");

        $vacations = $vacationRepository->findBy(['agenda' => $agenda], ['dateStart' => 'ASC']);

        return $this->render('planning/show.html.twig', [
            'agenda' => $agenda,
            'appointments' => $allApps,
            'firstDayOfWeek' => $firstDayOfWeek,
            'lastDayOfWeek' => $lastDayOfWeek,
            'weekOffset' => $weekOffset,
            'vacations' => $vacations,
            'unavailabilities' => $unavailabilityRepository->findAllOnWeek($agenda, $firstDayOfWeek),
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/planning/{id}/delete',
        name: 'app_planning_delete',
        requirements: ['id' => "\d+"])]
    public function delete(Agenda $agenda, AgendaRepository $agendaRepository, VetoRepository $vetoRepository, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Veto) {
            throw $this->createAccessDeniedException();
        } elseif ($user->getAgenda() !== $agenda) {
            throw $this->createAccessDeniedException();
        }

        // remove agenda instance from the current user (agenda is from this veto) and save it to the db.
        $user->setAgenda(null);
        $vetoRepository->save($user);

        // to complete remove the agenda instance
        $agendaRepository->remove($agenda, true);

        return $this->redirectToRoute('app_planning');
    }

    #[Route('/planning/create', name: 'app_planning_create')]
    public function create(Request $request, AgendaRepository $agendaRepository, AgendaDayRepository $dayRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Veto) {
            throw $this->createAccessDeniedException();
        }

        $agendaForm = $this->createForm(AgendaFormType::class);
        $agendaForm->handleRequest($request);

        if ($agendaForm->isSubmitted() && $agendaForm->isValid()) {
            $agenda = new Agenda();
            $agenda->setVeto($user);

            $agendaRepository->save($agenda, false);

            // check for no sunday
            $dayCount = $agendaForm->get('sunday')->getData() ? 8 : 7;
            for ($i = 1; $i < $dayCount; ++$i) {
                $day = new AgendaDay();
                $day->setAgenda($agenda);
                $day->setDay($i);
                $day->setStartHour($agendaForm->get('timeStart')->getData());
                $day->setEndHour($agendaForm->get('timeEnd')->getData());

                $dayRepository->save($day, true);
            }

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->renderForm('planning/create.html.twig', [
            'agenda_form' => $agendaForm,
        ]);
    }

    #[Route('/planning/{id}/edit',
        name: 'app_planning_edit',
        requirements: ['id' => "\d+"])]
    public function edit(Request $request, AgendaRepository $agendaRepository, Agenda $agenda, VacationRepository $vacationRepository, UnavailabilityRepository $unavailabilityRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Veto || $agenda->getVeto() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $success = false;

        $vacationAddForm = $this->createForm(VacationFormType::class);
        $vacationAddForm->handleRequest($request);

        $unavailabilityAddForm = $this->createForm(UnavailabilityFormType::class);
        $unavailabilityAddForm->handleRequest($request);

        $unavailabilityRepeatedForm = $this->createForm(UnavailabilityRepeatedFormType::class);
        $unavailabilityRepeatedForm->handleRequest($request);

        if ($vacationAddForm->isSubmitted() && $vacationAddForm->isValid()) {
            /** @var Vacation $newVacation */
            $newVacation = $vacationAddForm->getData();
            $newVacation->setAgenda($agenda);
            $vacationRepository->save($newVacation, true);
            $success = true;
        } elseif ($unavailabilityAddForm->isSubmitted() && $unavailabilityAddForm->isValid()) {
            /** @var Unavailability $newUnavailability */
            $newUnavailability = $unavailabilityAddForm->getData();
            $newUnavailability->setAgenda($agenda);
            $newUnavailability->setIsRepeated(false);
            $unavailabilityRepository->save($newUnavailability, true);
            $success = true;
        } elseif ($unavailabilityRepeatedForm->isSubmitted() && $unavailabilityRepeatedForm->isValid()) {
            /** @var Unavailability $newUnavailability */
            $newUnavailability = $unavailabilityRepeatedForm->getData();
            $newUnavailability->setAgenda($agenda);

            /** @var string $dayStartRealname */
            $dayStartRealname = $unavailabilityRepeatedForm->get('startDay')->getData();
            /** @var string $dayEndRealname */
            $dayEndRealname = $unavailabilityRepeatedForm->get('endDay')->getData();

            /** @var \DateTime $dayStartTime */
            $dayStartTime = $unavailabilityRepeatedForm->get('startDayTime')->getData();
            /** @var \DateTime $dayEndTime */
            $dayEndTime = $unavailabilityRepeatedForm->get('endDayTime')->getData();


            // create the datetime for passing only the day and hours on. it creates a datetime from 'now'.
            $dayStart = new \DateTime("$dayStartRealname {$dayStartTime->format('H:i')}");
            $dayEnd = new \DateTime("$dayEndRealname {$dayEndTime->format('H:i')}");
            $newUnavailability->setDateDeb($dayStart);
            $newUnavailability->setDateEnd($dayEnd);

            $newUnavailability->setIsRepeated(true);
            $unavailabilityRepository->save($newUnavailability, true);
            $success = true;
        }

        return $this->renderForm('planning/edit.html.twig', [
            'agenda' => $agenda,
            'veto' => $user,
            'vacation_add_form' => $vacationAddForm,
            'unavailability_add_form' => $unavailabilityAddForm,
            'unavailability_add_repeated_Form' => $unavailabilityRepeatedForm,
            'success' => $success,
        ]);
    }
}
