<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Animal>
 *
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    public function save(Animal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Animal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Animal[]
     */
    public function newFindAll(): array
    {
        $qb = $this->createQueryBuilder('animal')
            ->leftJoin('animal.CategoryAnimal', 'category')
            ->addSelect('category')
            ->orderBy('animal.name', 'ASC');
        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findAllWithUser(int $clientId)
    {
        $qb = $this->createQueryBuilder('animal')
            ->leftJoin('animal.CategoryAnimal', 'category')
            ->leftJoin('animal.ClientAnimal', 'client')
            ->addSelect('category')
            ->where('client.id = :client_id')
            ->setParameter(':client_id', $clientId)
            ->orderBy('animal.name', 'ASC');
        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findById(int $clientId, int $id)
    {
        $qb = $this->createQueryBuilder('animal')
            ->leftJoin('animal.CategoryAnimal', 'category')
            ->leftJoin('animal.ClientAnimal', 'client')
            ->addSelect('category')
            ->where('client.id = :client_id')
            ->andWhere('animal.id = :id')
            ->setParameter(':client_id', $clientId)
            ->setParameter(':id', $id)
            ->orderBy('animal.name', 'ASC');
        $query = $qb->getQuery();

        return $query->execute();
    }
//    /**
//     * @return Animal[] Returns an array of Animal objects
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

//    public function findOneBySomeField($value): ?Animal
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
