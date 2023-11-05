<?php

namespace App\Factory;

use App\Entity\Thread;
use App\Repository\ThreadRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Thread>
 *
 * @method static Thread|Proxy                     createOne(array $attributes = [])
 * @method static Thread[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Thread[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Thread|Proxy                     find(object|array|mixed $criteria)
 * @method static Thread|Proxy                     findOrCreate(array $attributes)
 * @method static Thread|Proxy                     first(string $sortedField = 'id')
 * @method static Thread|Proxy                     last(string $sortedField = 'id')
 * @method static Thread|Proxy                     random(array $attributes = [])
 * @method static Thread|Proxy                     randomOrCreate(array $attributes = [])
 * @method static Thread[]|Proxy[]                 all()
 * @method static Thread[]|Proxy[]                 findBy(array $attributes)
 * @method static Thread[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static Thread[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ThreadRepository|RepositoryProxy repository()
 * @method        Thread|Proxy                     create(array|callable $attributes = [])
 */
final class ThreadFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'lib' => self::faker()->realTextBetween(50, 254).'?',
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'message' => self::faker()->text(1024),
            'resolved' => self::faker()->boolean(),
        ];
    }

    protected function initialize(): self
    {
        return $this;
    }

    protected static function getClass(): string
    {
        return Thread::class;
    }
}
