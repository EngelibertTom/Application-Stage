<?php

namespace App\Manager;

use App\Entity\CultureTable;
use App\Entity\User;
use App\Repository\CultureTableRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;

class CultureTableManager
{
    private $cultureTableRepository;
    private $userService;
    private $em;

    public function __construct(CultureTableRepository $cultureTableRepository, EntityManagerInterface $entityManager,
                                UserService $userService)
    {
        $this->cultureTableRepository = $cultureTableRepository;
        $this->em = $entityManager;
        $this->userService = $userService;
    }

    public function save(CultureTable $cultureTable): void
    {
        $this->em->persist($cultureTable);
        $this->em->flush();
    }

    public function getCultureTable(int $id): ?CultureTable
    {
        return $this->cultureTableRepository->find($id);
    }

    public function getCultureTables(array $filter = [], $order = null, $start = null, $limit = null): array
    {
        return $this->cultureTableRepository->findPaginator($filter, $order, $start, $limit);
    }

    public function find(array $filter): ?CultureTable
    {
        return $this->cultureTableRepository->findOneBy($filter);
    }

    /**
     * Retourne un tableau formaté pour gérer le trie des tableaux.
     *
     * @param array $order
     * @return array
     */
    public function manageOrderList(array $order): ?array
    {
        $field = null;
        $manageOrder = null;

        if ($order && isset($order['column'], $order['dir'])) {
            switch ($order['column'])
            {
                case 0:
                    $field = 'ct.id';
                    break;

                case 1:
                    $field = 'ct.name';
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

    public function delete(CultureTable $cultureTable): void
    {
        $this->em->remove($cultureTable);
        $this->em->flush();
    }
}
