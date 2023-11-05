<?php

namespace App\Factory;

use App\Entity\Unavailability;
use App\Repository\UnavailabilityRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Unavailability>
 *
 * @method static Unavailability|Proxy                     createOne(array $attributes = [])
 * @method static Unavailability[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Unavailability[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Unavailability|Proxy                     find(object|array|mixed $criteria)
 * @method static Unavailability|Proxy                     findOrCreate(array $attributes)
 * @method static Unavailability|Proxy                     first(string $sortedField = 'id')
 * @method static Unavailability|Proxy                     last(string $sortedField = 'id')
 * @method static Unavailability|Proxy                     random(array $attributes = [])
 * @method static Unavailability|Proxy                     randomOrCreate(array $attributes = [])
 * @method static Unavailability[]|Proxy[]                 all()
 * @method static Unavailability[]|Proxy[]                 findBy(array $attributes)
 * @method static Unavailability[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static Unavailability[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static UnavailabilityRepository|RepositoryProxy repository()
 * @method        Unavailability|Proxy                     create(array|callable $attributes = [])
 */
final class UnavailabilityFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        $dateStart = self::faker()->dateTimeBetween('now', '+1 year')
            ->setTime(8, 0);

        $hours = self::faker()->numberBetween(1, 3);
        $dateEnd = (clone $dateStart)->modify("+$hours hours");

        return [
            'lib' => $lib = self::faker()->word(),
            'dateDeb' => $dateStart,
            'dateEnd' => $dateEnd,
            'isRepeated' => false,
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Unavailability $unavailability): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Unavailability::class;
    }
}
