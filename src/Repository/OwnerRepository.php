<?php

namespace App\Repository;

use App\Entity\Owner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Owner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Owner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Owner[]    findAll()
 * @method Owner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OwnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Owner::class);
    }

    public function findPaginator(array $order = null, string $search = null, int $start = null, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('o');
       

        if ($limit)
        {
            $qb->setFirstResult($start)
                ->setMaxResults($limit);
        }

        if ($order)
        {
            $qb->orderBy($order['field'], $order['dir']);
        }

        if ($search)
        {
            $qb->andWhere('o.name LIKE :search OR o.email LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        return $qb->getQuery()->getResult();
    }
} 