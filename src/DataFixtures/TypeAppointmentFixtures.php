<?php

namespace App\DataFixtures;

use App\Factory\TypeAppointmentFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeAppointmentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TypeAppointmentFactory::createOne([
            'libTypeApp' => 'Normal',
            'duration' => 10,
        ]);

        TypeAppointmentFactory::createOne([
            'libTypeApp' => 'Blessure',
            'duration' => 30,
        ]);

        TypeAppointmentFactory::createOne([
            'libTypeApp' => 'Operation (3h)',
            'duration' => 180,
        ]);

        TypeAppointmentFactory::createOne([
            'libTypeApp' => 'Operation (2h)',
            'duration' => 120,
        ]);

        TypeAppointmentFactory::createOne([
            'libTypeApp' => 'Operation (1h)',
            'duration' => 60,
        ]);

        TypeAppointmentFactory::createMany(5);

        $manager->flush();
    }
}
