<?php

namespace App\Repository;

use App\Entity\DetailOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DetailOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailOrder[]    findAll()
 * @method DetailOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailOrder::class);
    }

    // /**
    //  * @return DetailOrder[] Returns an array of DetailOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DetailOrder
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
