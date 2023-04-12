<?php

namespace App\Repository;

use App\Entity\Greenhouse;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Greenhouse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Greenhouse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Greenhouse[]    findAll()
 * @method Greenhouse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GreenhouseRepository extends GeneralRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Greenhouse::class);
    }

    public function findByNurseries(array $nurseries, array $filter, $order = null, $start = null, $limit = null)
    {
        $qb = $this->createQueryBuilder('g')
            ->andWhere('g.nursery IN (:nurseries)')
            ->setParameter('nurseries', $nurseries)
            ->orderBy('g.name', 'ASC')
        ;

        $this->addOptions($qb, $filter, $order, $start, $limit);

        return $qb->getQuery()->getResult();
    }
}
