<?php

namespace App\Repository;

use App\Entity\OutputType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OutputType|null find($id, $lockMode = null, $lockVersion = null)
 * @method OutputType|null findOneBy(array $criteria, array $orderBy = null)
 * @method OutputType[]    findAll()
 * @method OutputType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutputTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OutputType::class);
    }

    // /**
    //  * @return OutputType[] Returns an array of OutputType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OutputType
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
