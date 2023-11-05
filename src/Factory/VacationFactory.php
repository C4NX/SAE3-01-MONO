<?php

namespace App\Factory;

use App\Entity\Vacation;
use App\Repository\VacationRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Vacation>
 *
 * @method static Vacation|Proxy                     createOne(array $attributes = [])
 * @method static Vacation[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Vacation[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Vacation|Proxy                     find(object|array|mixed $criteria)
 * @method static Vacation|Proxy                     findOrCreate(array $attributes)
 * @method static Vacation|Proxy                     first(string $sortedField = 'id')
 * @method static Vacation|Proxy                     last(string $sortedField = 'id')
 * @method static Vacation|Proxy                     random(array $attributes = [])
 * @method static Vacation|Proxy                     randomOrCreate(array $attributes = [])
 * @method static Vacation[]|Proxy[]                 all()
 * @method static Vacation[]|Proxy[]                 findBy(array $attributes)
 * @method static Vacation[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static Vacation[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static VacationRepository|RepositoryProxy repository()
 * @method        Vacation|Proxy                     create(array|callable $attributes = [])
 */
final class VacationFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        $startDate = self::faker()->dateTimeBetween('now', '+1 year');
        $endDate = clone $startDate;
        date_modify($endDate, '+2 months'); // 2 months from $startDate

        return [
            'libVacation' => self::faker()->word(),
            'dateStart' => $startDate,
            'dateEnd' => $endDate,
        ];
    }

    protected static function getClass(): string
    {
        return Vacation::class;
    }
}
