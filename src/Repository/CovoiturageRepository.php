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

    public function rechercherTrajets(string $depart, string $arrivee, ?\DateTime $date = null, ?float $prixMax = null, ?float $noteMin = null, ?string $energie = null): array
    {
        $qb = $this->createQueryBuilder('c')
        ->andWhere('LOWER(c.lieu_depart) = :depart')
        ->andWhere('LOWER(c.lieu_arrivee) = :arrivee')
        ->andWhere('c.statut = :statut')
        ->setParameter('depart', strtolower($depart))
        ->setParameter('arrivee', strtolower($arrivee))
        ->setParameter('statut', CovoiturageStatut::PUBLISHED);
    
    if ($date) {
        $start = (clone $date)->setTime(0, 0);
        $end = (clone $date)->modify('+1 day')->setTime(0, 0);
        $qb->andWhere('c.date_depart >= :start AND c.date_depart < :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end);
    }
    
    if ($prixMax !== null) {
        $qb->andWhere('c.prix_personne <= :prix')
            ->setParameter('prix', $prixMax);
    }
    
    if ($noteMin !== null) {
        $qb->andWhere('(SELECT AVG(a.note) FROM App\Entity\Avis a WHERE a.user = c.createdBy AND a.statut = :valide) >= :note')
            ->setParameter('valide', 'VALIDÉ')
            ->setParameter('note', $noteMin);
    }

    if ($energie) {
        $qb->join('c.voiture', 'v')
           ->andWhere('v.energie = :energie')
           ->setParameter('energie', $energie);
    }
    
    return $qb->orderBy('c.date_depart', 'ASC')->getQuery()->getResult();
    }
}
