<?php

namespace App\Repository;

use App\Entity\PotType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PotType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PotType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PotType[]    findAll()
 * @method PotType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PotTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PotType::class);
    }

    // /**
    //  * @return PotType[] Returns an array of PotType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PotType
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
