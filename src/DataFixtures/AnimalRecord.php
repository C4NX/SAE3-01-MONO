<?php

namespace App\DataFixtures;

use App\Factory\AnimalRecordFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AnimalRecord extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        AnimalRecordFactory::createMany(25);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AnimalFixtures::class,
        ];
    }
}
