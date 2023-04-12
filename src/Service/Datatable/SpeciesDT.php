<?php

namespace App\Service\Datatable;

use App\Entity\User;
use App\Manager\ObservationManager;
use App\Manager\SpeciesManager;
use App\Manager\TreeWorkManager;
use App\Service\NurseryService;
use Symfony\Component\Security\Core\Security;

class SpeciesDT implements IDatatable
{

    private $speciesManager;

    public function __construct(SpeciesManager $speciesManager)
    {
        $this->speciesManager = $speciesManager;
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

        return $this->speciesManager->getSpecies($filter, $this->manageOrderList($order));
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
                    $field = 's.name';
                    break;

                case 1:
                    $field = 's.latinName';
                    break;

                case 2:
                    $field = 'leafType.name';
                    break;

                case 3:
                    $field = 'statusUicn.name';
                    break;

                case 4:
                    $field = 's.validate';
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
            $filter['s.name'] = $search;
            $filter['s.latinName'] = $search;
            $filter['leafType.name'] = $search;
            $filter['statusUicn.name'] = $search;
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
}
