<?php

namespace App\Service;

use App\Manager\ColumnRowManager;
use App\Manager\CultureTableManager;
use App\Manager\GreenhouseManager;
use App\Manager\NurseryManager;
use App\Manager\SegmentManager;
use App\Manager\TableColumnManager;
use App\Manager\TreeManager;

class PositionService
{
    private $greenhouseManager;
    private $nurseryManager;
    private $cultureTableManager;
    private $segmentManager;
    private $tableColumnManager;
    private $columnRowManager;
    private $treeManager;

    public function __construct(GreenhouseManager $greenhouseManager, NurseryManager $nurseryManager,
                                CultureTableManager $cultureTableManager, SegmentManager $segmentManager,
                                TableColumnManager $tableColumnManager, ColumnRowManager $columnRowManager,
                                TreeManager $treeManager)
    {
        $this->greenhouseManager = $greenhouseManager;
        $this->nurseryManager = $nurseryManager;
        $this->cultureTableManager = $cultureTableManager;
        $this->segmentManager = $segmentManager;
        $this->tableColumnManager = $tableColumnManager;
        $this->columnRowManager = $columnRowManager;
        $this->treeManager = $treeManager;
    }

    public function loadList(string $type, ?array $filter = []): array
    {
        $list = [];
        $filter = $filter ?: [];

        switch ($type) {
            case 'nursery':
                $list = $this->nurseryManager->getNurseries($filter);
                break;

            case 'greenhouse':
                $list = $this->greenhouseManager->getGreenhouses($filter);
                break;

            case 'table':
                $list = $this->cultureTableManager->getCultureTables($filter);
                break;

            case 'segment':
                $list = $this->segmentManager->getSegments($filter);
                break;

            case 'tableColumn':
                $list = $this->tableColumnManager->getTableColumns($filter);
                break;

            case 'columnRow':
                $list = $this->columnRowManager->getColumnRows($filter, ['field' => 'c.id', 'dir' => 'ASC']);
                break;
        }

        return $list;
    }

    public function refactorFilters(array &$filter): void
    {
        if (isset($filter['nursery']))
        {
            $nursery = $this->nurseryManager->getNursery( (int) $filter['nursery'] );

            if ($nursery) {
                $filter['nursery'] = $nursery;
            }
        }

        elseif (isset($filter['greenhouse.id']))
        {
            $greenhouse = $this->greenhouseManager->getGreenhouse( (int) $filter['greenhouse.id'] );

            if ($greenhouse) {
                unset($filter['greenhouse.id']);
                $filter['equal']['greenhouse.id'] = $greenhouse;
            }
        }

        elseif (isset($filter['cultureTable.id']))
        {
            $table = $this->cultureTableManager->getCultureTable( (int) $filter['cultureTable.id'] );

            if ($table) {
                unset($filter['cultureTable.id']);
                $filter['equal']['cultureTable.id'] = $table->getId();
            }
        }

        elseif (isset($filter['segment.id']))
        {
            $segment = $this->segmentManager->getSegment( (int) $filter['segment.id'] );

            if ($segment) {
                unset($filter['segment.id']);
                $filter['equal']['segment.id'] = $segment->getId();
            }
        }

        elseif (isset($filter['tableColumn.id']))
        {
            $tableColumn = $this->tableColumnManager->getTableColumn( (int) $filter['tableColumn.id'] );

            if ($tableColumn) {
                unset($filter['tableColumn.id']);
                $filter['equal']['tableColumn.id'] = $tableColumn->getId();
            }
        }
    }

    public function loadPosition(int $id, string $type): array
    {
        $data = [
            'nursery' => null,
            'greenhouse' => null,
            'table' => null
        ];

        switch ($type)
        {
            case 'nursery':
                $nursery = $this->nurseryManager->getNursery($id);

                if ($nursery)
                {
                    $data['nursery'] = $nursery->getId();
                }
                break;

            case 'greenhouse':
                $greenhouse = $this->greenhouseManager->getGreenhouse($id);

                if ($greenhouse)
                {
                    $data['nursery'] = $greenhouse->getNursery()->getId();
                    $data['greenhouse'] = $greenhouse->getId();
                }
                break;


            case 'table':
                $table = $this->cultureTableManager->getCultureTable($id);

                if ($table)
                {
                    $data['nursery'] = $table->getGreenhouse()->getNursery()->getId();
                    $data['greenhouse'] = $table->getGreenhouse()->getId();
                    $data['table'] = $table->getId();
                }

                break;

            case 'segment':
                $segment = $this->segmentManager->getSegment($id);

                if ($segment)
                {
                    $data['nursery'] = $segment->getCultureTable()->getGreenhouse()->getNursery()->getId();
                    $data['greenhouse'] = $segment->getCultureTable()->getGreenhouse()->getId();
                    $data['table'] = $segment->getCultureTable()->getId();
                }

                break;

            case 'tableColumn':
                $tableColumn = $this->tableColumnManager->getTableColumn($id);

                if ($tableColumn)
                {
                    $data['nursery'] = $tableColumn->getSegment()->getCultureTable()->getGreenhouse()->getNursery()->getId();
                    $data['greenhouse'] = $tableColumn->getSegment()->getCultureTable()->getGreenhouse()->getId();
                    $data['table'] = $tableColumn->getSegment()->getCultureTable()->getId();
                    $data['segment'] = $tableColumn->getSegment()->getId();
                }

                break;

            case 'columnRow':
                $columnRow = $this->columnRowManager->getColumnRow($id);

                if ($columnRow)
                {
                    $data['nursery'] = $columnRow->getTableColumn()->getSegment()->getCultureTable()->getGreenhouse()->getNursery()->getId();
                    $data['greenhouse'] = $columnRow->getTableColumn()->getSegment()->getCultureTable()->getGreenhouse()->getId();
                    $data['table'] = $columnRow->getTableColumn()->getSegment()->getCultureTable()->getId();
                    $data['segment'] = $columnRow->getTableColumn()->getSegment()->getId();
                    $data['tableColumn'] = $columnRow->getTableColumn()->getId();
                }

                break;

            case 'tree':
                $tree = $this->treeManager->getTree($id);

                if ($tree)
                {
                    if ($position = $tree->getPosition())
                    {
                        $data['nursery'] = $position->getTableColumn()->getSegment()->getCultureTable()->getGreenhouse()->getNursery()->getId();
                        $data['greenhouse'] = $position->getTableColumn()->getSegment()->getCultureTable()->getGreenhouse()->getId();
                        $data['table'] = $position->getTableColumn()->getSegment()->getCultureTable()->getId();
                        $data['segment'] = $position->getTableColumn()->getSegment()->getId();
                        $data['tableColumn'] = $position->getTableColumn()->getId();
                        $data['columnRow'] = $position->getId();
                    } else {
                        $data['nursery'] = $tree->getNursery()->getId();
                    }

                }
                break;
        }

        return $data;
    }
}
