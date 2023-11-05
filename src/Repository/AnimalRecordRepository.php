<?php

namespace App\Repository;

use App\Entity\AnimalRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnimalRecord>
 *
 * @method AnimalRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnimalRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnimalRecord[]    findAll()
 * @method AnimalRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnimalRecord::class);
    }

    public function save(AnimalRecord $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AnimalRecord $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByAnimal(int $id)
    {
        $qb = $this->createQueryBuilder('record')
            ->leftJoin('record.Animal', 'Animal')
            ->where('Animal.id = :id')
            ->setParameter(':id', $id)
            ->orderBy('record.id', 'ASC');
        $query = $qb->getQuery();

        return $query->execute();
    }
//    /**
//     * @return AnimalRecord[] Returns an array of AnimalRecord objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AnimalRecord
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
