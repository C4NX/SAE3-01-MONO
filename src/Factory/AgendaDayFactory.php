<?php

namespace App\Factory;

use App\Entity\AgendaDay;
use App\Repository\AgendaDayRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<AgendaDay>
 *
 * @method static AgendaDay|Proxy                     createOne(array $attributes = [])
 * @method static AgendaDay[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static AgendaDay[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static AgendaDay|Proxy                     find(object|array|mixed $criteria)
 * @method static AgendaDay|Proxy                     findOrCreate(array $attributes)
 * @method static AgendaDay|Proxy                     first(string $sortedField = 'id')
 * @method static AgendaDay|Proxy                     last(string $sortedField = 'id')
 * @method static AgendaDay|Proxy                     random(array $attributes = [])
 * @method static AgendaDay|Proxy                     randomOrCreate(array $attributes = [])
 * @method static AgendaDay[]|Proxy[]                 all()
 * @method static AgendaDay[]|Proxy[]                 findBy(array $attributes)
 * @method static AgendaDay[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static AgendaDay[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static AgendaDayRepository|RepositoryProxy repository()
 * @method        AgendaDay|Proxy                     create(array|callable $attributes = [])
 */
final class AgendaDayFactory extends ModelFactory
{
    protected static function getClass(): string
    {
        return AgendaDay::class;
    }

    public static function createWeek(int $startHour, int $endHour): array
    {
        $dayIndex = 0;
        $week = [];

        for ($i = 0; $i < 7; ++$i) {
            $week[] = self::createOne([
                'startHour' => \DateTimeImmutable::createFromFormat('H', "$startHour"),
                'endHour' => \DateTimeImmutable::createFromFormat('H', "$endHour"),
                'day' => ++$dayIndex,
            ]);
        }

        return $week;
    }

    protected function getDefaults(): array
    {
        return [];
    }
}
