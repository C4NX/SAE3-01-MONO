<?php

namespace App\DataFixtures;

use App\Factory\VetoFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VetoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        VetoFactory::createMany(15, function () {
            return [
                'tel' => VetoFactory::faker()->boolean() ? VetoFactory::faker()->phoneNumber() : null,
            ];
        });
    }
}
