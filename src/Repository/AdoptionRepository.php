<?php

namespace App\Repository;

use App\Entity\Adoption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Adoption|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adoption|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adoption[]    findAll()
 * @method Adoption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdoptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adoption::class);
    }

    /**
     * Retourne le nombre d'adoption pour une année et un mois donnée.
     */
    public function getMonthStat(int $year, int $month): int
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->andWhere('YEAR(a.date) = :year')
            ->andWhere('MONTH(a.date) = :month')
            ->setParameters([
                'year' => $year,
                'month' => $month
            ])
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
    
}
