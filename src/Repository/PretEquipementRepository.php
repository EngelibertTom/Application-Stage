<?php

namespace App\Repository;

use App\Entity\PretEquipement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PretEquipement|null find($id, $lockMode = null, $lockVersion = null)
 * @method PretEquipement|null findOneBy(array $criteria, array $orderBy = null)
 * @method PretEquipement[]    findAll()
 * @method PretEquipement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PretEquipementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PretEquipement::class);
    }

    // /**
    //  * @return PretEquipement[] Returns an array of PretEquipement objects
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
    public function findOneBySomeField($value): ?PretEquipement
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
