<?php

namespace App\DataFixtures;

use App\Factory\CategoryAnimalFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryAnimalFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $file = json_decode(file_get_contents(__DIR__.'/data/animals.json'), flags: JSON_OBJECT_AS_ARRAY);
        foreach ($file as $category) {
            CategoryAnimalFactory::createOne(['name' => $category]);
        }
    }
}
