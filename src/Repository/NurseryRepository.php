<?php

namespace App\Repository;

use App\Entity\Nursery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nursery|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nursery|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nursery[]    findAll()
 * @method Nursery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NurseryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nursery::class);
    }

    // /**
    //  * @return Nursery[] Returns an array of Nursery objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Nursery
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
