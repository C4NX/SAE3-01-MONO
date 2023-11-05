<?php

namespace App\Repository;

use App\Entity\Thread;
use App\Entity\ThreadMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ThreadMessage>
 *
 * @method ThreadMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThreadMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThreadMessage[]    findAll()
 * @method ThreadMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThreadMessage::class);
    }

    public function save(ThreadMessage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ThreadMessage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Tri les messages d'un thread par date et passe les messages des vétérinaires en premier.
     *
     * @param Thread $thread Le thread
     *
     * @return ThreadMessage[] Les messages du thread
     */
    public function findSortByVeto(Thread $thread): array
    {
        $messages = $this->findBy(['thread' => $thread], ['createdAt' => 'DESC']);

        foreach ($messages as $key => $element) {
            if ($element->getUser()->isVeto()) {
                array_splice($messages, $key, 1); // Remove element from array
                array_unshift($messages, $element); // Add element to beginning of array
            }
        }

        /*usort($messages, function (ThreadMessage $first) {
            return !$first->getUser()->isVeto();
        });*/

        return $messages;
    }

//    /**
//     * @return ThreadMessage[] Returns an array of ThreadMessage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ThreadMessage
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
