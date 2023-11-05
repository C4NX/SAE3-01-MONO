<?php

namespace App\Repository;

use App\Entity\Animal;
use App\Entity\Appointment;
use App\Entity\CategoryAnimal;
use App\Entity\Client;
use App\Entity\TypeAppointment;
use App\Entity\Veto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 *
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    public function save(Appointment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Appointment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getAppointmentAt(\DateTime $datetimeStart, TypeAppointment $type, Veto $veto): Appointment|null
    {
        // add $duration minutes to the start datetime
        $datetimeEnd = (clone $datetimeStart)->add(new \DateInterval("PT{$type->getDuration()}M"));

        // get the appointment if it exists.
        return $this->createQueryBuilder('a')
            ->where('a.veto = :veto')
            ->andWhere('(:dtStart BETWEEN a.dateApp AND a.dateEnd) OR (:dtEnd BETWEEN a.dateApp AND a.dateEnd)')
            ->getQuery()
            ->setParameter('veto', $veto)
            ->setParameter('dtStart', $datetimeStart) // define start time.
            ->setParameter('dtEnd', $datetimeEnd) // define end time.
            ->getOneOrNullResult();
    }

    public function findByVetoOnWeek(Veto $veto, int $weekOffset = 0): array
    {
        $start_week = date('Y-m-d', date_modify(new \DateTime('monday this week'), "+{$weekOffset} week")->getTimestamp());
        $end_week = date('Y-m-d', date_modify(new \DateTime('sunday this week'), "+{$weekOffset} week")->getTimestamp());

        return $this->createQueryBuilder('a')
            ->where('a.veto = :veto')
            ->andWhere('a.dateApp >= :start')
            ->andWhere('a.dateApp <= :end')
            ->getQuery()
            ->setParameter('veto', $veto)
            ->setParameter('start', $start_week)
            ->setParameter('end', $end_week)
            ->getArrayResult();
    }

    /**
     * Find all appointment on a given date and if it's completed.
     */
    public function findAllOnDate(Veto $veto, \DateTime $date, bool $getCompleted): array
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->select('a as appointment')
            ->addSelect('ctg.name as animal_type')
            ->addSelect('ta.libTypeApp as appointment_type')
            ->addSelect('an.id as animal_id')
            ->addSelect('cli.id as client_id')
            ->innerJoin(Animal::class, 'an')
            ->innerJoin(CategoryAnimal::class, 'ctg')
            ->innerJoin(Client::class, 'cli')
            ->innerJoin(TypeAppointment::class, 'ta')
            ->where('a.veto = :veto')
            ->andWhere('ctg = an.CategoryAnimal')
            ->andWhere('an = a.animal')
            ->andWhere('cli = a.client')
            ->andWhere('ta = a.type')
            ->andWhere('DATE(a.dateApp) = :date');

        $queryBuilder->andWhere('a.isCompleted = FALSE');

        if ($getCompleted) {
            $queryBuilder->orWhere('a.isCompleted = TRUE');
        }

        return $queryBuilder->getQuery()
            ->setParameter('veto', $veto)
            ->setParameter('date', $date->format('Y-m-d'))
            ->getArrayResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function updateNote(int $appointmentId, string $note): int
    {
        return $this->createQueryBuilder('a')
            ->update()
            ->set('a.note', ':note')
            ->where('a.id = :id')
            ->getQuery()
            ->setParameter('id', $appointmentId)
            ->setParameter('note', $note)
            ->getSingleScalarResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function setComplete(int $appId): int
    {
        return $this->createQueryBuilder('a')
            ->update()
            ->set('a.isCompleted', 'TRUE')
            ->where('a.id = :id')
            ->getQuery()
            ->setParameter('id', $appId)
            ->getSingleScalarResult();
    }
}
