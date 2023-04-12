<?php

namespace App\Repository;

use App\Entity\TreeGrowth;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TreeGrowth|null find($id, $lockMode = null, $lockVersion = null)
 * @method TreeGrowth|null findOneBy(array $criteria, array $orderBy = null)
 * @method TreeGrowth[]    findAll()
 * @method TreeGrowth[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TreeGrowthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TreeGrowth::class);
    }

    // /**
    //  * @return TreeGrowth[] Returns an array of TreeGrowth objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TreeGrowth
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
