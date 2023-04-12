<?php

namespace App\Repository;

use App\Entity\ManagementNursery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ManagementNursery|null find($id, $lockMode = null, $lockVersion = null)
 * @method ManagementNursery|null findOneBy(array $criteria, array $orderBy = null)
 * @method ManagementNursery[]    findAll()
 * @method ManagementNursery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManagementNurseryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ManagementNursery::class);
    }

    // /**
    //  * @return ManagementNursery[] Returns an array of ManagementNursery objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ManagementNursery
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
