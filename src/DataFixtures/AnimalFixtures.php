<?php

namespace App\DataFixtures;

use App\Factory\AnimalFactory;
use App\Factory\CategoryAnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AnimalFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        AnimalFactory::createMany(5, function () {
            return [
                'CategoryAnimal' => CategoryAnimalFactory::random(),
                'ClientAnimal' => ClientFactory::random(),
            ];
        });
    }

    public function getDependencies(): array
    {
        return [
            CategoryAnimalFixtures::class,
            ClientFixtures::class,
        ];
    }
}
