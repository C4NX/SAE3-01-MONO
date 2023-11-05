<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AvatarChangeFormType;
use App\Form\UserInfoChangeFormType;
use App\Form\PasswordChangeFormType;
use App\Repository\UserRepository;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    private Imagine $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/me', name: 'app_me')]
    public function index(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $avatarChangeForm = $this->createForm(AvatarChangeFormType::class);
        $avatarChangeForm->handleRequest($request);

        $passwordChangeForm = $this->createForm(PasswordChangeFormType::class);
        $passwordChangeForm->handleRequest($request);

        $userInfoChangeForm = $this->createForm(UserInfoChangeFormType::class, $user);
        $userInfoChangeForm->handleRequest($request);

        if ($avatarChangeForm->isSubmitted() && $avatarChangeForm->isValid()) {
            /** @var UploadedFile $avatarFile */
            $avatarFile = $avatarChangeForm->get('avatar')->getData();

            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.webp';
                $realFilename = $avatarFile->getRealPath();

                // resize at fixed size of 512x512.
                $image = $this->imagine->open($realFilename)
                    ->thumbnail(new Box(512, 512), ManipulatorInterface::THUMBNAIL_OUTBOUND);

                // save the image to webp format
                $image->save($realFilename, [
                    'format' => 'webp',
                ]);

                // move the resized image to the 'avatar_directory'
                try {
                    $avatarFile->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $user->setProfilePicPath($newFilename);
                $userRepository->save($user, true);
            }
        } elseif ($passwordChangeForm->isSubmitted() && $passwordChangeForm->isValid()) {
            // get the current password the user need to have.
            $currentPassword = $passwordChangeForm->get('current')->getData();

            // check if the password 'current' is the same as the user.
            if (!$currentPassword || !$userPasswordHasher->isPasswordValid($user, $currentPassword)) {
                // if not render an error
                return $this->renderForm('me/index.html.twig', [
                    'avatarChangeForm' => $avatarChangeForm,
                    'passwordChangeForm' => $passwordChangeForm,
                    'userInfoChangeForm' => $userInfoChangeForm,
                    'password_error' => 'Le mot de passe ne correspond pas au mot de passe actuel !',
                ]);
            } else {
                // get the new password.
                $newPassword = $passwordChangeForm->get('password')->getData();

                if ($newPassword) {
                    // change the new password with the hashed one and save/flush.
                    $user->setPassword($userPasswordHasher->hashPassword($user, $newPassword));
                    $userRepository->save($user, true);

                    // render the success !
                    return $this->renderForm('me/index.html.twig', [
                        'avatarChangeForm' => $avatarChangeForm,
                        'passwordChangeForm' => $passwordChangeForm,
                        'userInfoChangeForm' => $userInfoChangeForm,
                        'password_success' => 'Le mot de passe a été modifié avec succès !',
                    ]);
                }
            }
        } elseif ($userInfoChangeForm->isSubmitted() && $userInfoChangeForm->isValid()) {
            $updatedUser = $userInfoChangeForm->getData();
            $userRepository->save($updatedUser, true);
        }

        return $this->renderForm('me/index.html.twig', [
            'avatarChangeForm' => $avatarChangeForm,
            'passwordChangeForm' => $passwordChangeForm,
            'userInfoChangeForm' => $userInfoChangeForm,
        ]);
    }

    #[Route('/me/delete', name: 'app_me_delete')]
    public function delete(UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        // TODO: Delete form !

        // disconnect the user
        $session = new Session();
        $session->invalidate();

        // remove the user and redirect to app_home.
        $userRepository->remove($user, true);

        return $this->redirectToRoute('app_home');
    }
}
