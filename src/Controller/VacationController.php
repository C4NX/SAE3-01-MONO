<?php

namespace App\Controller;

use App\Entity\Vacation;
use App\Entity\Veto;
use App\Repository\VacationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class VacationController extends AbstractController
{
    #[Route('/vacation/{id}/delete',
        name: 'app_vacation_delete',
        requirements: ['id' => "\d+"])]
    public function delete(Vacation $vacation, VacationRepository $vacationRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Veto) {
            throw $this->createAccessDeniedException();
        } elseif ($vacation->getAgenda() !== $user->getAgenda()) {
            throw $this->createAccessDeniedException();
        }

        $vacationRepository->remove($vacation, true);

        return $this->redirectToRoute('app_planning_edit', ['id' => $vacation->getAgenda()->getId()]);
    }
}
