<?php

namespace App\Repository;

use App\Entity\Covoiturage;
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

    public function rechercherTrajets(string $depart, string $arrivee, \DateTime $date): array
    {
    return $this->createQueryBuilder('c')
        ->where('c.villeDepart = :depart')
        ->andWhere('c.villeArrivee = :arrivee')
        ->andWhere('DATE(c.dateDepart) = :date')
        ->andWhere('c.placesDisponible > 0')
        ->setParameter('depart', $depart)
        ->setParameter('arrivee', $arrivee)
        ->setParameter('date', $date->format('Y-m-d'))
        ->getQuery()
        ->getResult();
    }

}
