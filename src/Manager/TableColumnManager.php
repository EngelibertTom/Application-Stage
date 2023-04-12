<?php

namespace App\Manager;

use App\Entity\TableColumn;
use App\Entity\User;
use App\Repository\TableColumnRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class TableColumnManager
{
    private $tableColumnRepository;
    private $userService;
    private $security;
    private $em;

    public function __construct(TableColumnRepository $tableColumnRepository, EntityManagerInterface $entityManager,
                                UserService $userService, Security $security)
    {
        $this->tableColumnRepository = $tableColumnRepository;
        $this->userService = $userService;
        $this->security = $security;
        $this->em = $entityManager;
    }

    public function save(TableColumn $tableColumn): void
    {
        $this->em->persist($tableColumn);
        $this->em->flush();
    }

    public function getTableColumn(int $id): ?TableColumn
    {
        return $this->tableColumnRepository->find($id);
    }

    public function getTableColumns(array $filter = [], $order = null, $start = null, $limit = null): array
    {
        return $this->tableColumnRepository->findPaginator($filter, $order, $start, $limit);
    }

    public function delete(TableColumn $tableColumn): void
    {
        $this->em->remove($tableColumn);
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
                    $field = 't.id';
                    break;

                case 1:
                    $field = 't.name';
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
