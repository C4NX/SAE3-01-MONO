<?php

namespace App\Factory;

use App\Entity\TypeAppointment;
use App\Repository\TypeAppointmentRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<TypeAppointment>
 *
 * @method static TypeAppointment|Proxy                     createOne(array $attributes = [])
 * @method static TypeAppointment[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static TypeAppointment[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static TypeAppointment|Proxy                     find(object|array|mixed $criteria)
 * @method static TypeAppointment|Proxy                     findOrCreate(array $attributes)
 * @method static TypeAppointment|Proxy                     first(string $sortedField = 'id')
 * @method static TypeAppointment|Proxy                     last(string $sortedField = 'id')
 * @method static TypeAppointment|Proxy                     random(array $attributes = [])
 * @method static TypeAppointment|Proxy                     randomOrCreate(array $attributes = [])
 * @method static TypeAppointment[]|Proxy[]                 all()
 * @method static TypeAppointment[]|Proxy[]                 findBy(array $attributes)
 * @method static TypeAppointment[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static TypeAppointment[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static TypeAppointmentRepository|RepositoryProxy repository()
 * @method        TypeAppointment|Proxy                     create(array|callable $attributes = [])
 */
final class TypeAppointmentFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        $id = self::faker()->unique()->numberBetween(0, 100);
        return [
            'libTypeApp' => "Type#{$id}",
            'duration' => 10 * self::faker()->numberBetween(1, 22),
        ];
    }

    protected static function getClass(): string
    {
        return TypeAppointment::class;
    }
}
