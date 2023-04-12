<?php

namespace App\Repository;

use App\Entity\DeadTree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeadTree|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeadTree|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeadTree[]    findAll()
 * @method DeadTree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeadTreeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeadTree::class);
    }

    // /**
    //  * @return DeadTree[] Returns an array of DeadTree objects
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
    public function findOneBySomeField($value): ?DeadTree
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
