<?php

namespace App\Repository;

use App\Entity\HistoryTree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HistoryTree|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoryTree|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoryTree[]    findAll()
 * @method HistoryTree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryTreeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoryTree::class);
    }

    // /**
    //  * @return HistoryTree[] Returns an array of HistoryTree objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistoryTree
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
