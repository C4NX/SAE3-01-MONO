<?php

namespace App\Repository;

use App\Entity\Thread;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Thread>
 *
 * @method Thread|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thread|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thread[]    findAll()
 * @method Thread[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thread::class);
    }

    public function save(Thread $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Thread $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Return all thread (id, lib, createdAt) with author in 'name' in one SQL request, this adds a search and pagination param.
     *
     * @see https://www.doctrine-project.org/projects/doctrine-orm/en/latest/tutorials/pagination.html
     */
    public function findAllWithName(string $search, int $page, int $perPage): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.id as id')
            ->addSelect('t.lib as lib')
            ->addSelect('t.createdAt as createdAt')
            ->addSelect('t.message as message')
            ->addSelect('t.resolved as resolved')
            ->addSelect('COUNT(replies.id) as count')
            ->addSelect("CONCAT(author.lastName, ' ',author.firstName) as name")
            ->innerJoin('t.author', 'author')
            ->leftJoin('t.replies', 'replies')
            ->where('t.lib LIKE :search OR t.message LIKE :search')
            ->orderBy('t.createdAt', 'DESC')
            ->groupBy('t.id')
            ->setParameter('search', "%$search%")
            ->getQuery()
            ->setFirstResult($page * $perPage)
            ->setMaxResults($perPage)
            ->execute();
    }

    /**
     * Count in the database with a criteria.
     *
     * @see https://stackoverflow.com/questions/19103699/doctrine-counting-an-entitys-items-with-a-condition
     */
    public function countBy(array $criteria): int
    {
        $persister = $this->_em->getUnitOfWork()->getEntityPersister($this->_entityName);

        return $persister->count($criteria);
    }
}
