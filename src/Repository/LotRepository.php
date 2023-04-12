<?php

namespace App\Repository;

use App\Entity\Lot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lot[]    findAll()
 * @method Lot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LotRepository extends GeneralRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lot::class);
    }

    public function findPaginator(array $filter = null, array $order = null, int $start = null, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('l')
            ->leftJoin('l.nursery', 'nursery')
            ->leftJoin('l.recoveryType', 'recoveryType')
        ;

        $this->addOptions($qb, $filter, $order, $start, $limit);

        return $qb->getQuery()->getResult();
    }
}
