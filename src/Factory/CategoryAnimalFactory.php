<?php

namespace App\Factory;

use App\Entity\CategoryAnimal;
use App\Repository\CategoryAnimalRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<CategoryAnimal>
 *
 * @method static CategoryAnimal|Proxy                     createOne(array $attributes = [])
 * @method static CategoryAnimal[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static CategoryAnimal[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static CategoryAnimal|Proxy                     find(object|array|mixed $criteria)
 * @method static CategoryAnimal|Proxy                     findOrCreate(array $attributes)
 * @method static CategoryAnimal|Proxy                     first(string $sortedField = 'id')
 * @method static CategoryAnimal|Proxy                     last(string $sortedField = 'id')
 * @method static CategoryAnimal|Proxy                     random(array $attributes = [])
 * @method static CategoryAnimal|Proxy                     randomOrCreate(array $attributes = [])
 * @method static CategoryAnimal[]|Proxy[]                 all()
 * @method static CategoryAnimal[]|Proxy[]                 findBy(array $attributes)
 * @method static CategoryAnimal[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static CategoryAnimal[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static CategoryAnimalRepository|RepositoryProxy repository()
 * @method        CategoryAnimal|Proxy                     create(array|callable $attributes = [])
 */
final class CategoryAnimalFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        $name = mb_convert_case(self::faker()->word(), MB_CASE_TITLE);

        return [
            'name' => $name,
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(CategoryAnimal $categoryAnimal): void {})
        ;
    }

    protected static function getClass(): string
    {
        return CategoryAnimal::class;
    }
}
