<?php

namespace App\Repository;

use App\Entity\Segment;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Segment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Segment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Segment[]    findAll()
 * @method Segment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SegmentRepository extends GeneralRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Segment::class);
    }

    public function findPaginator(array $filter = null, array $order = null, int $start = null, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('s');

        $this->addOptions($qb, $filter, $order, $start, $limit);

        return $qb->getQuery()->getResult();
    }
}
