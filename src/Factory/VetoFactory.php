<?php

namespace App\Factory;

use App\Entity\Veto;
use App\Repository\VetoRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Veto>
 *
 * @method static Veto|Proxy                     createOne(array $attributes = [])
 * @method static Veto[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Veto[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Veto|Proxy                     find(object|array|mixed $criteria)
 * @method static Veto|Proxy                     findOrCreate(array $attributes)
 * @method static Veto|Proxy                     first(string $sortedField = 'id')
 * @method static Veto|Proxy                     last(string $sortedField = 'id')
 * @method static Veto|Proxy                     random(array $attributes = [])
 * @method static Veto|Proxy                     randomOrCreate(array $attributes = [])
 * @method static Veto[]|Proxy[]                 all()
 * @method static Veto[]|Proxy[]                 findBy(array $attributes)
 * @method static Veto[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static Veto[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static VetoRepository|RepositoryProxy repository()
 * @method        Veto|Proxy                     create(array|callable $attributes = [])
 */
final class VetoFactory extends UserFactory
{
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct($userPasswordHasher);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        /** @var VetoFactory $self */
        $self = parent::initialize();

        return $self;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        $idUnique = self::faker()->unique()->numerify();

        return array_merge(parent::getDefaults(), [
            'email' => "veto-$idUnique@take.vet",
        ]);
    }

    protected static function getClass(): string
    {
        return Veto::class;
    }
}
