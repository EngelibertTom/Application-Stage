<?php

namespace App\Repository;

use App\Entity\Species;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Species|null find($id, $lockMode = null, $lockVersion = null)
 * @method Species|null findOneBy(array $criteria, array $orderBy = null)
 * @method Species[]    findAll()
 * @method Species[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpeciesRepository extends GeneralRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Species::class);
    }

    public function findPaginator(array $filter = null, array $order = null, int $start = null, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.statusUicn', 'statusUicn')
            ->leftJoin('s.leafType', 'leafType')
        ;

        $this->addOptions($qb, $filter, $order, $start, $limit);

        return $qb->getQuery()->getResult();
    }
}
