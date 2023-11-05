<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Vaccine;
use App\Entity\Veto;
use App\Form\VaccineFormType;
use App\Repository\VaccineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VaccineController extends AbstractController
{
    #[Route('/vaccine/add/{id}',
        name: 'app_vaccine_add',
        requirements: ['id' => "\d+"])]
    public function add(VaccineRepository $repository, Animal $animal, Request $request): Response
    {
        $createForm = $this->createForm(VaccineFormType::class);
        $createForm->handleRequest($request);

        $user = $this->getUser();
        if (!$user instanceof Veto) {
            throw $this->createAccessDeniedException();
        }

        if ($createForm->isSubmitted() && $createForm->isValid()) {
            /** @var $vaccine Vaccine */
            $vaccine = $createForm->getData();
            $vaccine->setAnimal($animal);
            $repository->save($vaccine, true);
        }

        return $this->renderForm('vaccine/index.html.twig', [
            'create_form' => $createForm,
            'animal' => $animal,
            'client' => $animal->getClientAnimal(),
        ]);
    }

    #[Route('/vaccine/add/{id}',
        name: 'app_vaccine_remove',
        requirements: ['id' => "\d+"])]
    public function remove(VaccineRepository $repository, Vaccine $vaccine): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Veto) {
            throw $this->createAccessDeniedException();
        }

        $animalId = $vaccine->getAnimal()->getId();
        $repository->remove($vaccine, true);
        return $this->redirectToRoute('app_animal', ['id' => $animalId]);
    }
}
