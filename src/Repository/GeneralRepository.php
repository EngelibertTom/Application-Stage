<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class GeneralRepository extends ServiceEntityRepository
{

    protected function addOptions(QueryBuilder $qb, array $filter, $order = null, $start = null, $limit = null): void
    {
        if ($limit)
        {
            $qb->setFirstResult($start)
                ->setMaxResults($limit);
        }

        if ($order)
        {
            $qb->orderBy($order['field'], $order['dir']);
        }

        foreach ($filter as $field => $value)
        {
            if ($field === 'equal')
            {
                $i = 1;

                foreach ($value as $key => $v)
                {
                    $qb->andWhere($key . ' = :search_' . $i)->setParameter('search_' . $i, $v );
                    $i++;
                }
            }

            elseif ($field === 'in')
            {
                $i = 1;

                foreach ($value as $key => $v)
                {
                    $qb->andWhere($key . ' IN (:search_'. $i .')')->setParameter('search_' . $i, $v );
                    $i++;
                }
            }

            else {
                $qb->orWhere($field . ' LIKE :search')->setParameter('search', '%' . $value . '%');
            }
        }
    }
}
