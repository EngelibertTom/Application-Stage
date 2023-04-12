<?php

namespace App\Repository;

use App\Entity\TableColumn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TableColumn|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableColumn|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableColumn[]    findAll()
 * @method TableColumn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableColumnRepository extends GeneralRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableColumn::class);
    }

    public function findPaginator(array $filter = null, array $order = null, int $start = null, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('t');

        $this->addOptions($qb, $filter, $order, $start, $limit);

        return $qb->getQuery()->getResult();
    }
}
