<?php

namespace App\DataFixtures;

use App\Factory\ClientFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // admin !
        ClientFactory::createOne([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'admin@take.vet',
            'password' => 'admin',
            'roles' => [
                'ROLE_ADMIN',
            ],
        ]);

        $manager->flush();
    }
}
