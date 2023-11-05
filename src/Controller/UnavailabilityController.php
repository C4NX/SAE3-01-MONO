<?php

namespace App\Controller;

use App\Entity\Unavailability;
use App\Entity\Vacation;
use App\Entity\Veto;
use App\Repository\UnavailabilityRepository;
use App\Repository\VacationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UnavailabilityController extends AbstractController
{
    #[Route('/unavailability/{id}/delete',
        name: 'app_unavailability_delete',
        requirements: ['id' => "\d+"])]
    public function delete(Unavailability $unavailability, UnavailabilityRepository $unavailabilityRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Veto) {
            throw $this->createAccessDeniedException();
        } elseif ($unavailability->getAgenda() !== $user->getAgenda()) {
            throw $this->createAccessDeniedException();
        }

        $unavailabilityRepository->remove($unavailability, true);

        return $this->redirectToRoute('app_planning_edit', ['id' => $unavailability->getAgenda()->getId()]);
    }
}
