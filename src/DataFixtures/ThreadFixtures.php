<?php

namespace App\DataFixtures;

use App\Factory\ClientFactory;
use App\Factory\ThreadFactory;
use App\Factory\ThreadMessageFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ThreadFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        ThreadFactory::createMany(15, function () {
            return [
                'author' => ClientFactory::random(),
                // create ThreadMessage
                'replies' => ThreadMessageFactory::createMany(ClientFactory::faker()->numberBetween(0, 5), function () {
                    return [
                        // set to a random author
                        'user' => ClientFactory::random(),
                    ];
                }),
            ];
        });

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies()
    {
        return [
            ClientFixtures::class,
        ];
    }
}
