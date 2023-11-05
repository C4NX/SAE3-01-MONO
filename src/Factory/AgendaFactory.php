<?php

namespace App\Factory;

use App\Entity\Agenda;
use App\Repository\AgendaRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Agenda>
 *
 * @method static Agenda|Proxy                     createOne(array $attributes = [])
 * @method static Agenda[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Agenda[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Agenda|Proxy                     find(object|array|mixed $criteria)
 * @method static Agenda|Proxy                     findOrCreate(array $attributes)
 * @method static Agenda|Proxy                     first(string $sortedField = 'id')
 * @method static Agenda|Proxy                     last(string $sortedField = 'id')
 * @method static Agenda|Proxy                     random(array $attributes = [])
 * @method static Agenda|Proxy                     randomOrCreate(array $attributes = [])
 * @method static Agenda[]|Proxy[]                 all()
 * @method static Agenda[]|Proxy[]                 findBy(array $attributes)
 * @method static Agenda[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static Agenda[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static AgendaRepository|RepositoryProxy repository()
 * @method        Agenda|Proxy                     create(array|callable $attributes = [])
 */
final class AgendaFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Agenda $agenda): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Agenda::class;
    }
}
