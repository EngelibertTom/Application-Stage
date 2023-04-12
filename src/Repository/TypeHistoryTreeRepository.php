<?php

namespace App\Repository;

use App\Entity\TypeHistoryTree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeHistoryTree|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeHistoryTree|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeHistoryTree[]    findAll()
 * @method TypeHistoryTree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeHistoryTreeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeHistoryTree::class);
    }

    // /**
    //  * @return TypeHistoryTree[] Returns an array of TypeHistoryTree objects
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
    public function findOneBySomeField($value): ?TypeHistoryTree
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
