<?php

namespace App\DataFixtures;

use App\Factory\AddressFactory;
use App\Factory\ClientFactory;
use App\Factory\ThreadFactory;
use App\Factory\ThreadMessageFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ClientFactory::createMany(15, function () {
            return [
                'tel' => ClientFactory::faker()->boolean() ? ClientFactory::faker()->phoneNumber() : null,
                'adresses' => AddressFactory::createMany(ClientFactory::faker()->numberBetween(1, 3)),
            ];
        });
    }
}
