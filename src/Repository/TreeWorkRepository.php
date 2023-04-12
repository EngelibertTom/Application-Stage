<?php

namespace App\Repository;

use App\Entity\TreeWork;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TreeWork|null find($id, $lockMode = null, $lockVersion = null)
 * @method TreeWork|null findOneBy(array $criteria, array $orderBy = null)
 * @method TreeWork[]    findAll()
 * @method TreeWork[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TreeWorkRepository extends GeneralRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TreeWork::class);
    }

    public function findPaginator(array $filter = null, array $order = null, int $start = null, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('tw')
            ->leftJoin('tw.work', 'work')
            ->leftJoin('tw.tree', 'tree')
            ->leftJoin('tw.user', 'user')
            ->leftJoin('tree.nursery', 'nursery')
            ->leftJoin('tree.species', 'species')
            ->leftJoin('tree.greenhouse', 'greenhouse')
        ;

        $this->addOptions($qb, $filter, $order, $start, $limit);

        return $qb->getQuery()->getResult();
    }
}
