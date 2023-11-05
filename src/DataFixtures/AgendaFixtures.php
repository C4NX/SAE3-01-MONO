<?php

namespace App\DataFixtures;

use App\Factory\AgendaDayFactory;
use App\Factory\AgendaFactory;
use App\Factory\UnavailabilityFactory;
use App\Factory\VacationFactory;
use App\Factory\VetoFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AgendaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        AgendaFactory::createMany(8, function () {
            return [
                'days' => AgendaDayFactory::createWeek(8, 18), // 8h to 18h, all days
                'vacations' => VacationFactory::createMany(2),
                'unavailabilities' => UnavailabilityFactory::createMany(5),
                'veto' => VetoFactory::random(),
            ];
        });
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            VetoFixtures::class,
        ];
    }
}
