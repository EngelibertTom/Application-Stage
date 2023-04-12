<?php

namespace App\Repository;

use App\Entity\CultureTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CultureTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method CultureTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method CultureTable[]    findAll()
 * @method CultureTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CultureTableRepository extends GeneralRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CultureTable::class);
    }

    public function findPaginator(array $filter = null, array $order = null, int $start = null, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('ct');

        $this->addOptions($qb, $filter, $order, $start, $limit);

        return $qb->getQuery()->getResult();
    }
}
