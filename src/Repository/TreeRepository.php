<?php

namespace App\Repository;

use App\Entity\Tree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tree|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tree|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tree[]    findAll()
 * @method Tree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TreeRepository extends GeneralRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tree::class);
    }

    public function findPaginator(array $filter = null, array $order = null, int $start = null, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('t')
            ->leftJoin('t.species', 'species')
            ->leftJoin('t.categories', 'categories')
            ->leftJoin('t.greenhouse', 'greenhouse')
            ->leftJoin('t.cultureTable', 'cultureTable')
            ->leftJoin('t.segment', 'segment')
            ->leftJoin('t.tableColumn', 'tableColumn')
            ->leftJoin('t.columnRow', 'columnRow')
            ->leftJoin('t.nursery', 'nursery');

        $this->addOptions($qb, $filter, $order, $start, $limit);

        return $qb->getQuery()->getResult();
    }
}
