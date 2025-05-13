<?php

namespace App\Repository;

use App\Entity\Covoiturage;
use App\Enum\CovoiturageStatut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Covoiturage>
 */
class CovoiturageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Covoiturage::class);
    }

    //    /**
    //     * @return Covoiturage[] Returns an array of Covoiturage objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Covoiturage
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function rechercherTrajets(string $depart, string $arrivee, ?\DateTime $date): array
    {
    $qb = $this->createQueryBuilder('c')
        ->andWhere('LOWER(c.lieu_depart) = :depart')
        ->andWhere('LOWER(c.lieu_arrivee) = :arrivee')
        ->andWhere('c.statut = :statut')
        ->setParameter('depart', strtolower($depart))
        ->setParameter('arrivee', strtolower($arrivee))
        ->setParameter('statut', CovoiturageStatut::PUBLISHED)
        ->orderBy('c.date_depart', 'ASC');

    if ($date) {
        $start = (clone $date)->setTime(0, 0, 0);
        $end = (clone $date)->modify('+1 day')->setTime(0, 0, 0);
        $qb->andWhere('c.date_depart >= :start')
           ->andWhere('c.date_depart < :end')
           ->setParameter('start', $start)
           ->setParameter('end', $end);
    }

    return $qb->getQuery()->getResult();
    }
}
