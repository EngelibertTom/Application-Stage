<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Retourne la liste des utilisateurs des rôles donnés.
     *
     * @param array $roles
     * @return array
     */
    public function findByRole(array $roles): array
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.active = 1');

        $orWhereRole  = $qb->expr()->orX();

        foreach ($roles as $role)
        {
            $orWhereRole->add(
                $qb->expr()->like('u.roles', $qb->expr()->literal("%$role%"))
            );
        }

        $qb->andWhere($orWhereRole);

        return $qb->getQuery()->getResult();
    }
}
