<?php

namespace App\Repository;

use App\Entity\RecoveryType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RecoveryType|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecoveryType|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecoveryType[]    findAll()
 * @method RecoveryType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecoveryTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecoveryType::class);
    }

    // /**
    //  * @return RecoveryType[] Returns an array of RecoveryType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RecoveryType
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
