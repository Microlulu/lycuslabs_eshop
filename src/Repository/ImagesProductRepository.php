<?php

namespace App\Repository;

use App\Entity\ImagesProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImagesProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImagesProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImagesProduct[]    findAll()
 * @method ImagesProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImagesProduct::class);
    }

    // /**
    //  * @return ImagesProduct[] Returns an array of ImagesProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImagesProduct
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
