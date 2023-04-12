<?php

namespace App\Repository;

use App\Entity\LeafType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LeafType|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeafType|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeafType[]    findAll()
 * @method LeafType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeafTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeafType::class);
    }

    // /**
    //  * @return LeafType[] Returns an array of LeafType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LeafType
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
