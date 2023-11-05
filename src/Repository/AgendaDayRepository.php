<?php

namespace App\Repository;

use App\Entity\Agenda;
use App\Entity\AgendaDay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AgendaDay>
 *
 * @method AgendaDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method AgendaDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method AgendaDay[]    findAll()
 * @method AgendaDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgendaDayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgendaDay::class);
    }

    public function save(AgendaDay $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AgendaDay $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * TODO: Test this !!.
     *
     * @param int       $dayIndex      the day index to find, from 1 to 7 (1 --> monday, 7 --> sunday)
     * @param Agenda    $agenda        the agenda to search in
     * @param \DateTime $datetimeStart the start time of the task
     * @param int       $duration      the duration of the dask (in minutes)
     *
     * @return AgendaDay|null an agenda day or null if no Agenda day was found in the bounds
     *
     * @throws NonUniqueResultException
     */
    public function findAndCheckAt(int $dayIndex, Agenda $agenda, \DateTime $datetimeStart, int $duration): AgendaDay|null
    {
        // add $duration minutes to the start datetime
        $datetimeEnd = (clone $datetimeStart)->add(new \DateInterval("PT{$duration}M"));

        return $this->createQueryBuilder('a')
            ->where('a.day = :index')
            // start time and end time must be in between startHour and endHour
            ->andWhere(':datetimeStart BETWEEN a.startHour AND a.endHour')
            ->andWhere(':datetimeEnd BETWEEN a.startHour AND a.endHour')
            ->andWhere('a.agenda = :agenda')
            ->getQuery()
            // define the bounds
            // FIX: with TimeType::class this is making an error
            ->setParameter('datetimeStart', $datetimeStart->format('H:i:s')) // define start time.
            ->setParameter('datetimeEnd', $datetimeEnd->format('H:i:s')) // define end time.
            ->setParameter('index', $dayIndex) // define the day index, 1 to 7
            // define the agenda to use
            ->setParameter('agenda', $agenda)
            ->getOneOrNullResult();
    }

//    /**
//     * @return AgendaDay[] Returns an array of AgendaDay objects
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

//    public function findOneBySomeField($value): ?AgendaDay
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
