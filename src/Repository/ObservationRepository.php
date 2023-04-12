<?php

namespace App\Repository;

use App\Entity\Observation;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Observation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Observation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Observation[]    findAll()
 * @method Observation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObservationRepository extends GeneralRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Observation::class);
    }

    public function findPaginator(array $filter = null, array $order = null, int $start = null, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.tree', 'tree')
            ->leftJoin('o.user', 'user')
            ->leftJoin('tree.species', 'species')
            ->leftJoin('tree.greenhouse', 'greenhouse')
            ->leftJoin('tree.nursery', 'nursery')
        ;

        $this->addOptions($qb, $filter, $order, $start, $limit);

        return $qb->getQuery()->getResult();
    }
}
