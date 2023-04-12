<?php

namespace App\Manager;

use App\Entity\ColumnRow;
use App\Entity\User;
use App\Repository\ColumnRowRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ColumnRowManager
{
    private $columnRowRepository;
    private $userService;
    private $security;
    private $em;

    public function __construct(ColumnRowRepository $columnRowRepository, EntityManagerInterface $entityManager,
                                Security $security, UserService $userService)
    {
        $this->columnRowRepository = $columnRowRepository;
        $this->userService = $userService;
        $this->security = $security;
        $this->em = $entityManager;
    }

    public function save(ColumnRow $columnRow): void
    {
        $this->em->persist($columnRow);
        $this->em->flush();
    }

    public function getColumnRow(int $id): ?ColumnRow
    {
        return $this->columnRowRepository->find($id);
    }

    public function getColumnRows(array $filter = [], $order = null, $start = null, $limit = null): array
    {
        return $this->columnRowRepository->findPaginator($filter, $order, $start, $limit);
    }

    public function delete(ColumnRow $columnRow): void
    {
        $this->em->remove($columnRow);
        $this->em->flush();
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
                    $field = 'c.id';
                    break;

                case 1:
                    $field = 'c.name';
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
