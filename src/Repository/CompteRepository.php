<?php

namespace App\Repository;

use App\Entity\Compte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Compte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compte[]    findAll()
 * @method Compte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compte::class);
    }

    // /**
    //  * @return Compte[] Returns an array of Compte objects
    //  */

    public function filterID($type)
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', $type)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Compte[] Returns an array of Compte objects
    //  */

    public function filterSaldo($type)
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.saldo', $type)
            ->getQuery()
            ->getResult()
            ;
    }



   /* public function findOneBySomeField($value): ?Compte
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }*/



}
