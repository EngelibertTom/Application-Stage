<?php

namespace App\Manager;

use App\Entity\TreeWork;
use App\Repository\TreeWorkRepository;
use Doctrine\ORM\EntityManagerInterface;

class TreeWorkManager
{
    private $treeWorkRepository;
    private $em;

    public function __construct(TreeWorkRepository $treeWorkRepository, EntityManagerInterface $entityManager)
    {
        $this->treeWorkRepository = $treeWorkRepository;
        $this->em = $entityManager;
    }

    public function save(TreeWork $treeWork): void
    {
        $this->em->persist($treeWork);
        $this->em->flush();
    }

    public function getTreeWorks(array $filter = [], $order = null, $start = null, $limit = null): array
    {
        return $this->treeWorkRepository->findPaginator($filter, $order, $start, $limit);
    }

    public function delete(TreeWork $treeWork): void
    {
        $this->em->remove($treeWork);
        $this->em->flush();
    }

    /**
     * Retourne un tableau formater pour gérer le trie des tableaux.
     *
     * @param array $order
     * @return array
     */
    public function manageOrderList(array $order): ?array
    {
        $field = null;
        $manageOrder = null;

        if ($order && isset($order['column'], $order['dir']))
        {
            switch ($order['column'])
            {
                case 0:
                    $field = 'tw.date';
                    break;

                case 2:
                    $field = 'tree.id';
                    break;

                case 3:
                    $field = 'work.name';
                    break;

                case 4:
                    $field = 'user.username';
                    break;
            }

            if ($field)
            {
                $manageOrder = [
                    'field' => $field,
                    'dir' => $order['dir']
                ];
            }
        }

        return $manageOrder;
    }
}
