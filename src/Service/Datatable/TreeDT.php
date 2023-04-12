<?php

namespace App\Service\Datatable;

use App\Entity\Greenhouse;
use App\Entity\Species;
use App\Entity\User;
use App\Manager\GreenhouseManager;
use App\Manager\SpeciesManager;
use App\Manager\TreeManager;
use App\Manager\TreeStatusManager;
use App\Service\NurseryService;
use Symfony\Component\Security\Core\Security;

class TreeDT implements IDatatable
{
    private $treeManager;
    private $treeStatusManager;
    private $security;
    private $nurseryService;
    private $mainNursery;
    private $greenhouseManager;
    private $speciesManager;

    public function __construct(TreeManager $treeManager, TreeStatusManager $treeStatusManager, GreenhouseManager $greenhouseManager,
                                Security $security, NurseryService $nurseryService, SpeciesManager $speciesManager)
    {
        $this->treeManager = $treeManager;
        $this->treeStatusManager = $treeStatusManager;
        $this->greenhouseManager = $greenhouseManager;
        $this->nurseryService = $nurseryService;
        $this->speciesManager = $speciesManager;
        $this->security = $security;

        /** @var User $user */
        $user = $security->getUser();
        $this->mainNursery = $this->nurseryService->getMainNursery($user->getManagementNurseries()->toArray());
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

        $order = $this->manageOrderList($order);

        return $this->treeManager->getTrees($filter, $order);
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

        if ($order && isset($order['column'], $order['dir'])) {
            switch ($order['column'])
            {
                case 3:
                    $field = 'species.name';
                    break;

                case 4:
                    $field = 'greenhouse.name';
                    break;

                case 5:
                    $field = 'cultureTable.name';
                    break;

                case 6:
                    $field = 'segment.name';
                    break;

                case 7:
                    $field = 'tableColumn.name';
                    break;

                case 8:
                    $field = 'columnRow.name';
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
            $filter['t.id'] = $search;
            $filter['species.name'] = $search;
        }

        foreach ($columns as $column)
        {
            $search = $column['search'];
            $value = str_replace(str_split('^$'), '', $search['value']);

            if ($search['regex'] === 'true' && !empty($value))
            {
                switch ($column['data'])
                {
                    case 'name':
                        $filter['equal']['species.name'] = $value;
                        break;

                    case 'status':
                        $filter['equal']['t.status'] = $value;
                        break;

                    case 'greenhouse':
                        $filter['equal']['greenhouse.name'] = $value;
                        break;
                }
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
        $filterList = [];

        switch ($type)
        {
            case 'status':
                $filterList = $this->treeStatusManager->getList();
                break;

            case 'species':
                $filterList = [];

                $species = $this->speciesManager->getSpecies();

                /** @var Species $specie */
                foreach ($species as $specie)
                {
                    $filterList[] = [ $specie->getName() ];
                }

                break;

            case 'greenhouse':
                $filterList = [];

                if ($this->security->isGranted('ROLE_SUPER_ADMIN'))
                {
                    $greenhouses = $this->greenhouseManager->getGreenhouses();
                } else {
                    $greenhouses = $this->greenhouseManager->getGreenhouses([
                        'nursery' => $this->mainNursery
                    ]);
                }

                /** @var Greenhouse $greenhouse */
                foreach ($greenhouses as $greenhouse)
                {
                    $filterList[] = [ $greenhouse->getName() ];
                }

                break;
        }

        return $filterList;
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
