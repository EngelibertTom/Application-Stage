<?php

namespace App\Repository;

use App\Entity\ColumnRow;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ColumnRow|null find($id, $lockMode = null, $lockVersion = null)
 * @method ColumnRow|null findOneBy(array $criteria, array $orderBy = null)
 * @method ColumnRow[]    findAll()
 * @method ColumnRow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColumnRowRepository extends GeneralRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ColumnRow::class);
    }

    public function findPaginator(array $filter = null, array $order = null, int $start = null, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('c');

        $this->addOptions($qb, $filter, $order, $start, $limit);

        return $qb->getQuery()->getResult();
    }
}
