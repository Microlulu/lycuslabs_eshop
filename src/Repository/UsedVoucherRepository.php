<?php

namespace App\Repository;

use App\Entity\UsedVoucher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsedVoucher|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsedVoucher|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsedVoucher[]    findAll()
 * @method UsedVoucher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsedVoucherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsedVoucher::class);
    }

    // /**
    //  * @return UsedVoucher[] Returns an array of UsedVoucher objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsedVoucher
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
