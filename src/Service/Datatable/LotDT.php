<?php

namespace App\Service\Datatable;

use App\Entity\User;
use App\Manager\LotManager;
use App\Service\NurseryService;
use Symfony\Component\Security\Core\Security;

class LotDT implements IDatatable
{
    private $lotManager;
    private $security;
    private $nurseryService;

    public function __construct(LotManager $lotManager, Security $security, NurseryService $nurseryService)
    {
        $this->lotManager = $lotManager;
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

        return $this->lotManager->getLots($filter, $this->manageOrderList($order));
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
                case 1:
                    $field = 'l.id';
                    break;

                case 2:
                    $field = 'l.name';
                    break;

                case 3:
                    $field = 'l.place';
                    break;

                case 4:
                    $field = 'l.postalCode';
                    break;

                case 5:
                    $field = 'l.city';
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
            $filter['l.id'] = $search;
            $filter['l.name'] = $search;
            $filter['l.place'] = $search;
            $filter['l.postalCode'] = $search;
            $filter['l.city'] = $search;
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
