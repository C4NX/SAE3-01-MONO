<?php

namespace App\Repository;

use App\Entity\Agenda;
use App\Entity\TypeAppointment;
use App\Entity\Unavailability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Unavailability>
 *
 * @method Unavailability|null find($id, $lockMode = null, $lockVersion = null)
 * @method Unavailability|null findOneBy(array $criteria, array $orderBy = null)
 * @method Unavailability[]    findAll()
 * @method Unavailability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnavailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unavailability::class);
    }

    public function save(Unavailability $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Unavailability $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
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

//    /**
//     * @return Unavailability[] Returns an array of Unavailability objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Unavailability
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function getUnavailabilityAt(\DateTime $start, TypeAppointment $type, Agenda $agenda): Unavailability|null
    {
        $end = (clone $start)->add(new \DateInterval("PT{$type->getDuration()}M"));

        return $this->createQueryBuilder('u')
            ->where('u.agenda = :agenda')
            ->andWhere('(:start BETWEEN u.dateDeb AND u.dateEnd) OR (:end BETWEEN u.dateDeb AND u.dateEnd)')
            ->getQuery()
            ->setParameter('agenda', $agenda)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getOneOrNullResult();
    }

    /**
     * Get all Unavailability of a date.
     *
     * @return Unavailability[]
     */
    public function findAllOnDate(Agenda $agenda, \DateTime $date): array
    {
        // we take only the date between, not the time, the difference between is 1 day.
        $dateDayStart = (clone $date)->setTime(0, 0);
        $dateDayEnd = (clone $date)->setTime(23, 59, 59);

        return $this->findAllBetweenDate($agenda, $dateDayStart, $dateDayEnd);
    }

    /**
     * Get all Unavailability between two date.
     *
     * @see https://stackoverflow.com/questions/25998255/using-date-in-doctrine-querybuilder
     *
     * @return Unavailability[]
     */
    public function findAllBetweenDate(Agenda $agenda, \DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.agenda = :agenda')
            ->andWhere('(u.isRepeated = TRUE AND (DAYOFWEEK(:date_day_start) BETWEEN DAYOFWEEK(u.dateDeb) AND DAYOFWEEK(u.dateEnd)) OR (DAYOFWEEK(:date_day_end) BETWEEN DAYOFWEEK(u.dateDeb) AND DAYOFWEEK(u.dateEnd)))')
            ->orWhere('(u.dateDeb >= :date_day_start AND u.dateDeb <= :date_day_end) OR (u.dateEnd >= :date_day_start AND u.dateEnd <= :date_day_end)')
            ->getQuery()
            ->setParameter('agenda', $agenda)
            ->setParameter('date_day_start', $startDate)
            ->setParameter('date_day_end', $endDate)
            ->getArrayResult();
    }

    /**
     * Get all Unavailability of a datetime week.
     *
     * @return Unavailability[]
     */
    public function findAllOnWeek(Agenda $agenda, \DateTime $week): array
    {
        // Calculate the timestamp for the start of the week.
        $startOfWeekTimestamp = time() - date('w', time()) * 86400 + 86400;

        // convert timestamp to date.
        $startOfWeek = new \DateTime(date('Y-m-d', $startOfWeekTimestamp));
        $endOfWeek = (new \DateTime(date('Y-m-d', $startOfWeekTimestamp + 6 * 86400 - 1)))
            ->setTime(23, 59, 59); // pickup all the last day of week.

        return $this->findAllBetweenDate($agenda, $startOfWeek->setTime(23, 59, 59), $endOfWeek);
    }
}
