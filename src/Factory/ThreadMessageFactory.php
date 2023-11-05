<?php

namespace App\Factory;

use App\Entity\ThreadMessage;
use App\Repository\ThreadMessageRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ThreadMessage>
 *
 * @method static ThreadMessage|Proxy                     createOne(array $attributes = [])
 * @method static ThreadMessage[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ThreadMessage[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static ThreadMessage|Proxy                     find(object|array|mixed $criteria)
 * @method static ThreadMessage|Proxy                     findOrCreate(array $attributes)
 * @method static ThreadMessage|Proxy                     first(string $sortedField = 'id')
 * @method static ThreadMessage|Proxy                     last(string $sortedField = 'id')
 * @method static ThreadMessage|Proxy                     random(array $attributes = [])
 * @method static ThreadMessage|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ThreadMessage[]|Proxy[]                 all()
 * @method static ThreadMessage[]|Proxy[]                 findBy(array $attributes)
 * @method static ThreadMessage[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static ThreadMessage[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ThreadMessageRepository|RepositoryProxy repository()
 * @method        ThreadMessage|Proxy                     create(array|callable $attributes = [])
 */
final class ThreadMessageFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'message' => self::faker()->realTextBetween(10, 1024),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    protected function initialize(): self
    {
        return $this;
    }

    protected static function getClass(): string
    {
        return ThreadMessage::class;
    }
}
