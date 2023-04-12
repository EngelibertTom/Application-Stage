<?php

namespace App\Service\Datatable;

interface IDatatable
{
    /**
     * Retourne la liste des données.
     *
     * @param array $columns
     * @param null $order
     * @param null $search
     * @return array
     */
    public function all(array $columns, $order = null, $search = null): array;

    /**
     * Retourne un tableau formaté pour gérer le trie des tableaux.
     *
     * @param array $order
     * @return array|null
     */
    public function manageOrderList(array $order): ?array;

    /**
     * Permet de gérer les filtres par élèment.
     *
     * @param string $search
     * @param array $columns
     * @return array
     */
    public function managerSearch(string $search, array $columns): array;

    /**
     * Retourne la liste d'un type de filtre.
     *
     * @param string $type
     * @return array
     */
    public function getFilterList(string $type): array;
}
