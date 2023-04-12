<?php

namespace App\Service;

use App\Entity\Tree;
use App\Manager\AdoptionManager;
use App\Manager\MonthManager;
use App\Manager\TreeManager;
use App\Manager\TreeStatusManager;


class DashboardService
{
    private $monthManager;
    private $adoptionManager;
    private $treeManager;

    public function __construct(MonthManager $monthManager, AdoptionManager $adoptionManager, TreeManager $treeManager)
    {
        $this->monthManager = $monthManager;
        $this->adoptionManager = $adoptionManager;
        $this->treeManager = $treeManager;
    }

    /**
     * Retourne les stats des adoptions sur 2 ans.
     *
     * @return array
     * @throws \Exception
     */
    public function annualAdoptionStat(): array
    {
        $months = array_values($this->monthManager->getList(false, true));

        $series = [];
        $now = (new \DateTime())->modify('-1 year');

        for ($i = 0; $i < 2; $i++)
        {
            $total = 0;

            $date = $now->modify('+' . $i . ' years');
            $data = $this->adoptionManager->annualStat($date->format('Y'));

            foreach ($data as $value)
            {
                $total += $value;
            }

            $series[] = [
                'name' => $date->format('Y'),
                'data' => $data,
                'total' => $total
            ];
        }

        $adoptionData = [
            'labels' => $months,
            'series' => $series,
            'percentageChange' => $this->percentageChange($series)
        ];

        return $adoptionData;
    }

    /**
     * Retourne les stats des arbres morts sur 2 ans.
     *
     * @return array
     * @throws \Exception
     */
    public function annualDeadTreeStat(): array
    {
        $months = array_values($this->monthManager->getList(false, true));

        $series = [];
        $now = (new \DateTime())->modify('-1 year');

        for ($i = 0; $i < 2; $i++)
        {
            $total = 0;

            $date = $now->modify('+' . $i . ' years');
            $data = $this->adoptionManager->annualStat($date->format('Y'));

            foreach ($data as $value)
            {
                $total += $value;
            }

            $series[] = [
                'name' => $date->format('Y'),
                'data' => $data,
                'total' => $total
            ];
        }

        $adoptionData = [
            'labels' => $months,
            'series' => $series,
            'percentageChange' => $this->percentageChange($series)
        ];

        return $adoptionData;
    }

    /**
     * Retourne les stats des arbres par statut.
     *
     * @return array
     */
    public function statusTreeStat(): array
    {
        $labels = [];
        $series = [];

        $nbAdoptableTree = $this->treeManager->count([
            'status' => TreeStatusManager::ADOPTABLE
        ]);

        if ($nbAdoptableTree)
        {
            $labels[] = "Adoptables";
            $series[] = $nbAdoptableTree;
        }

        $nbDeadTree = $this->treeManager->count([
            'status' => TreeStatusManager::DEAD
        ]);

        if ($nbDeadTree)
        {
            $labels[] = "Morts";
            $series[] = $nbDeadTree;
        }

        $nbNotAdoptableTree = $this->treeManager->count([
            'status' => TreeStatusManager::NOT_ADOPTABLE
        ]);

        if ($nbNotAdoptableTree)
        {
            $labels[] = "Non Adoptables";
            $series[] = $nbNotAdoptableTree;
        }

        $statusTreeData = [
            'series' => $series,
            'labels' => $labels
        ];

        return $statusTreeData;
    }

    /**
     * Retourne le pourcentage de progression entre deux séries de stats.
     *
     * @param array $series
     * @return float
     */
    private function percentageChange(array $series): float
    {
        $totalY1 = $series[0]['total'];
        $totalY2 = $series[1]['total'];

        $percentageChange = $totalY2 ? 100 : 0;

        if ($totalY1)
        {
            $percentageChange = (($totalY2 - $totalY1) / $totalY1) * 100;
        }

        return $percentageChange;
    }
}

