<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Client;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class AnimalController extends AbstractController
{
    private Imagine $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }
    #[Route('/animal', name: 'app_animal')]
    public function index(AnimalRepository $animalRepository): Response
    {
        $user = $this->getUser();
        $animals = [];
        $isClient = false;
        if ($user instanceof Client) {
            $isClient = true;
            $id = $user->getId();
            $animals = $animalRepository->findAllWithUser($id);
        }

        return $this->render('animal/index.html.twig', [
            'animals' => $animals,
            'isClient' => $isClient,
        ]);
    }

    #[Route('/animal/{id}/update')]
    #[ParamConverter('animal', class: Animal::class)]
    public function update(Animal $animal, Request $request, AnimalRepository $animalRepository): Response
    {
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $animalRepository->save($animal, true);

            return $this->redirectToRoute('app_animal');
        }

        return $this->renderForm('animal/update.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }

    /**
     * @see https://symfony.com/doc/5.4/controller/upload_file.html
     */
    #[Route('/animal/create')]
    public function create(Request $request, AnimalRepository $animalRepository, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Client) {
            throw $this->createNotFoundException();
        }

        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photo')->getData();

            /** @var Animal $handledAnimal * */
            $handledAnimal = $form->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();
                $realFilename = $photoFile->getRealPath();
                // resize at fixed size of 200x200.
                $image = $this->imagine->open($realFilename)
                    ->thumbnail(new Box(200, 200), ManipulatorInterface::THUMBNAIL_OUTBOUND);

                // save the image to webp format
                $image->save($realFilename, [
                    'format' => 'webp',
                ]);
                try {
                    $photoFile->move(
                        $this->getParameter('animal_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $handledAnimal->setPhotoPath($newFilename);
            }

            $handledAnimal->setClientAnimal($user);
            $animalRepository->save($handledAnimal, true);

            return $this->redirectToRoute('app_animal');
        }

        return $this->renderForm('animal/create.twig', [
           'form' => $form,
        ]);
    }

    #[Route('/animal/{id}/delete')]
    #[ParamConverter('animal', class: Animal::class)]
    public function delete(Request $request, Animal $animal, AnimalRepository $animalRepository): Response
    {
        $form = $this->createFormBuilder($animal)
            ->add('delete', SubmitType::class, ['label' => 'Oui, supprimer cet animal'])
            ->add('cancel', SubmitType::class, ['label' => 'Non, annuler'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var SubmitButton $button */
            $button = $form->get('delete');
            if ($button->isClicked()) {
                $animalRepository->remove($animal, true);

                return $this->redirectToRoute('app_animal');
            }

            return $this->redirectToRoute('app_animal');
        }

        return $this->renderForm('animal/delete.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }
}
