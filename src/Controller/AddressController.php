<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Client;
use App\Entity\User;
use App\Form\AddressFormType;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    #[Route('/address', name: 'app_address')]
    public function index(AddressRepository $repository, Request $request): Response
    {
        $client = $this->getUser();

        // check for only a Client and only the client who created this address for $address.
        if (!$client instanceof User) {
            return $this->redirectToRoute('app_login');
        }
        if (!$client->isClient()) {
            throw $this->createAccessDeniedException();
        }

        $createForm = $this->createForm(AddressFormType::class);
        $createForm->handleRequest($request);

        // handle the address creation
        if ($createForm->isSubmitted() && $createForm->isValid()) {
            /** @var $address Address */
            $address = $createForm->getData();

            $address->setClient($client);
            $repository->save($address, true);
        }

        // get all the addresses of a client.
        $addresses = $repository->findBy(['client' => $client]);

        return $this->renderForm('address/index.html.twig', [
            'addresses' => $addresses,
            'create_form' => $createForm,
        ]);
    }

    #[Route('/address/update/{id}',
        name: 'app_address_update',
        requirements: ['id' => "\d+"])]
    public function update(AddressRepository $repository, Request $request, Address $address): Response
    {
        $client = $this->getUser();

        // check for only a Client and only the client who created this address for $address.
        if (!$client instanceof User) {
            return $this->redirectToRoute('app_login');
        }
        if (!$client->isClient()) {
            throw $this->createAccessDeniedException();
        }

        $updateForm = $this->createForm(AddressFormType::class, $address);
        $updateForm->handleRequest($request);

        // handle the address creation
        if ($updateForm->isSubmitted() && $updateForm->isValid()) {
            /** @var $address Address */
            $address = $updateForm->getData();

            $address->setClient($client);
            $repository->save($address, true);
        }

        return $this->renderForm('address/update.html.twig', [
            'name' => $address->getName(),
            'update_form' => $updateForm,
        ]);
    }

    #[Route('/address/delete/{id}',
        name: 'app_address_delete',
        requirements: ['id' => "\d+"])]
    public function delete(Request $request, AddressRepository $repository, Address $address): Response
    {
        $client = $this->getUser();

        if ($client instanceof Client) {
            if ($address->getClient() === $client) {
                $repository->remove($address, true);
            }
        }

        return $this->redirectToRoute('app_address');
    }
}
