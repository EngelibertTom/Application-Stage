<?php

namespace App\Service\Datatable;

use App\Entity\User;
use App\Manager\TreeWorkManager;
use App\Service\NurseryService;
use Symfony\Component\Security\Core\Security;

class TreeWorksDT implements IDatatable
{

    private $treeWorkManager;
    private $security;
    private $nurseryService;

    public function __construct(TreeWorkManager $treeWorkManager, Security $security, NurseryService $nurseryService)
    {
        $this->treeWorkManager = $treeWorkManager;
        $this->security = $security;
        $this->nurseryService = $nurseryService;
    }

    /**
     * Retourne la liste des données.
     *
     * @param array $columns
     * @param null $order
     * @param null $search
     * @return array
     */
    public function all(array $columns, $order = null, $search = null): array
    {
        $filter = $this->managerSearch($search, $columns);
        $this->applyGranted($filter);

        return $this->treeWorkManager->getTreeWorks($filter, $this->manageOrderList($order));
    }

    /**
     * Retourne un tableau formaté pour gérer le trie des tableaux.
     *
     * @param array $order
     * @return array|null
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

                case 1:
                    $field = 'tree.id';
                    break;

                case 2:
                    $field = 'work.name';
                    break;

                case 3:
                    $field = 'species.name';
                    break;

                case 4:
                    $field = 'greenhouse.name';
                    break;

                case 5:
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

    /**
     * Permet de gérer les filtres par élèment.
     *
     * @param string $search
     * @param array $columns
     * @return array
     */
    public function managerSearch(string $search, array $columns): array
    {
        $filter = [];

        if (!empty($search)) {
            $filter['tree.id'] = $search;
            $filter['species.name'] = $search;
            $filter['user.username'] = $search;
            $filter['greenhouse.name'] = $search;
            $filter['work.name'] = $search;
        }

        foreach ($columns as $column) {
            if ($column['data'] === 'todo')
            {
                $filter['equal']['tw.todo'] = (bool)$column['search']['value'];
            }
        }

        return $filter;
    }

    /**
     * Retourne la liste d'un type de filtre.
     *
     * @param string $type
     * @return array
     */
    public function getFilterList(string $type): array
    {
        return [];
    }

    /**
     * Ajouter le filtre de la pépinière en fonction de l'utilisateur courant.
     *
     * @param array $filter
     */
    private function applyGranted(array &$filter): void
    {
        // Si l'utilisateur n'est pas super admin alors on filtre sur sa pépinière par défaut.
        if (!$this->security->isGranted('ROLE_SUPER_ADMIN'))
        {
            /** @var User $user */
            $user = $this->security->getUser();

            $mainNursery = $this->nurseryService->getMainNursery($user->getManagementNurseries()->toArray());

            if ($mainNursery)
            {
                $filter['equal']['nursery.id'] = $mainNursery->getId();
            } else {
                $filter['equal']['nursery.id'] = 0;
            }
        }
    }
}
