<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\AnimalRecord;
use App\Entity\Client;
use App\Entity\Veto;
use App\Form\AnimalRecordFormType;
use App\Repository\AnimalRecordRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class RecordAnimalController extends AbstractController
{
    #[Route('/record/{id}', name: 'app_record_animal')]
    public function index(AnimalRecordRepository $animalRecordRepository, Animal $animal): Response
    {
        $user = $this->getUser();
        $animalId = $animal->getId();
        if ($user instanceof Client && $animal->getClientAnimal() !== $user) {
            throw $this->createAccessDeniedException();
        }
        $records = $animalRecordRepository->findByAnimal($animalId);

        return $this->render('record_animal/index.html.twig', [
            'records' => $records,
            'animal' => $animal,
        ]);
    }

    #[Route('/record/update/{id}', requirements: ['id' => "\d+"])]
    public function update(AnimalRecord $animalRecord, Request $request, AnimalRecordRepository $recordRepository, Animal $animal): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Veto) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(AnimalRecordFormType::class, $animalRecord);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recordRepository->save($animalRecord, true);

            return $this->redirectToRoute('app_animal');
        }

        return $this->renderForm('record_animal/update.twig', [
            'record' => $animalRecord,
            'form' => $form,
            'animal' => $animal,
        ]);
    }

    #[Route('/record/create', name: 'app_record_create', priority: 2)]
    public function create(Request $request, AnimalRecordRepository $animalRecordRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Veto) {
            throw $this->createAccessDeniedException();
        }
        $record = new AnimalRecord();
        $form = $this->createForm(AnimalRecordFormType::class, $record);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var AnimalRecord $handledRecord * */
            $handledRecord = $form->getData();
            $animalRecordRepository->save($handledRecord, true);

            return $this->redirectToRoute('app_animal');
        }

        return $this->renderForm('record_animal/create.twig', [
            'form' => $form,
        ]);
    }
}
