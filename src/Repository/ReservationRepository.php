<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findUpcomingReservations(): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.dateReservation >= :today')
            ->andWhere('r.statut != :cancelled')
            ->setParameter('today', new \DateTime('today'))
            ->setParameter('cancelled', 'annulee')
            ->orderBy('r.dateReservation', 'ASC')
            ->addOrderBy('r.heureReservation', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByUser(int $userId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('r.dateReservation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countByStatus(string $status): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.statut = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findUpcomingByUser($user, int $limit = 3): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->andWhere('r.dateReservation >= :today')
            ->andWhere('r.statut IN (:statuses)')
            ->setParameter('user', $user)
            ->setParameter('today', new \DateTime('today'))
            ->setParameter('statuses', ['en_attente', 'confirmee'])
            ->orderBy('r.dateReservation', 'ASC')
            ->addOrderBy('r.heureReservation', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function countPendingByUser($user): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.user = :user')
            ->andWhere('r.statut = :status')
            ->setParameter('user', $user)
            ->setParameter('status', 'en_attente')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getReservationsByMonth(): array
    {
        return $this->createQueryBuilder('r')
            ->select('YEAR(r.dateReservation) as year, MONTH(r.dateReservation) as month, COUNT(r.id) as count')
            ->groupBy('year, month')
            ->orderBy('year', 'DESC')
            ->addOrderBy('month', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult();
    }

    public function getReservationsByStatus(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.statut as statut, COUNT(r.id) as count')
            ->groupBy('r.statut')
            ->getQuery()
            ->getResult();
    }

    public function getReservationsByUser(int $userId): array
    {
        return $this->createQueryBuilder('r')
            ->select('YEAR(r.dateReservation) as year, MONTH(r.dateReservation) as month, COUNT(r.id) as count')
            ->andWhere('r.user = :userId')
            ->setParameter('userId', $userId)
            ->groupBy('year, month')
            ->orderBy('year', 'DESC')
            ->addOrderBy('month', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult();
    }
}
