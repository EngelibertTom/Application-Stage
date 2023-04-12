<?php

namespace App\Service;

use App\Entity\CultureTable;
use App\Entity\Greenhouse;
use App\Entity\ManagementNursery;
use App\Entity\PotType;
use App\Entity\Segment;
use App\Entity\Tree;
use App\Entity\User;
use App\Manager\PotTypeManager;

class GreenhouseService
{
    private const WIDTH_SEGMENT = 960;
    private const HEIGHT_SEGMENT = 640;

    private $potTypeManager;

    public function __construct(PotTypeManager $potTypeManager)
    {
        $this->potTypeManager = $potTypeManager;
    }

    /**
     * Retourne différentes informations de la table d'une serre (liste de arbres, schema des dispos, nb de place dispo)
     *
     * @param Greenhouse $greenhouse
     * @param CultureTable $cultureTable
     * @return array
     */
    public function getInfoSchemaCultureTable(Greenhouse $greenhouse, CultureTable $cultureTable): array
    {
        $trees = $this->getAllTreeCultureTable($greenhouse, $cultureTable);
        $schemaCultureTable = $this->createSchemaCultureTable($trees);

        $info = [
            'trees' => $trees,
            'schemaCultureTable' => $schemaCultureTable,
            'spaceAvailable' => $this->getSpaceAvailable($schemaCultureTable)
        ];

        return $info;
    }

    /**
     * Créer le tableau pour le diagramme qui affiche le pourcentage de place prise en fonction des couleurs des pots.
     *
     * @param Greenhouse $greenhouse
     * @param CultureTable $cultureTable
     * @return array
     */
    public function getInfoDiagramCultureTable(Greenhouse $greenhouse, CultureTable $cultureTable): array
    {
        $info = [
            'All' => [],
            1 => [],
            2 => [],
            3 => [],
            4 => []
        ];

        $infoSegmentWidthPotTypes = $this->getInfoSegmentWidthPotType($info, $greenhouse, $cultureTable);
        $this->calculatePercentagePotType($infoSegmentWidthPotTypes, $info);
        $this->calculateEmptyPercentage($info);
        $this->potTypeThatCanFit($info);
        $this->potTypeOnCultureTable($info, $greenhouse, $cultureTable);

        return $info;
    }

    /**
     *  Retourne la longueur que chaque pot prend sur un segment.
     *
     * @param array $info
     * @param Greenhouse $greenhouse
     * @param CultureTable $cultureTable
     * @return array
     */
    public function getInfoSegmentWidthPotType(array &$info, Greenhouse $greenhouse, CultureTable $cultureTable): array
    {
        $infoSegments = [
            1 => [],
            2 => [],
            3 => [],
            4 => []
        ];

        $potTypes = $this->potTypeManager->getPotTypes();

        foreach ($infoSegments as $key => $infoSegment)
        {
            /** @var PotType $potType */
            foreach ($potTypes as $potType)
            {
                if ($potType->getReference() !== 'RS-70-N')
                {
                    $infoSegments[$key][] = [
                        'potType' => $potType->getReference(),
                        'width' => 0
                    ];
                }
            }
        }

        $trees = $this->getAllTreeCultureTable($greenhouse, $cultureTable);

        /** @var Tree $tree */
        foreach ($trees as $tree)
        {
            if (($segment = $tree->getSegment()) && ($potType = $tree->getPotType()))
            {
                $infoSegment = $infoSegments[(int)$segment->getName()];
                $key = array_search($potType->getReference(), array_column($infoSegment, 'potType'), true);

                if ($key !== false)
                {
                    $info['All']['trees'][] = $tree;
                    $info[(int)$segment->getName()]['trees'][] = $tree;
                    $infoSegments[(int)$segment->getName()][$key]['width'] += $potType->getDiameter() * $potType->getDiameter();
                }
            }
        }

        return $infoSegments;
    }

    /**
     * On calcule le pourcentage que prend chaque pot sur chaque segments et sur la table entière.
     *
     * @param array $infoSegmentWidthPotTypes
     * @param $info
     * @param float $air
     */
    private function calculatePercentagePotType(array $infoSegmentWidthPotTypes, &$info): void
    {
        $air = self::WIDTH_SEGMENT * self::HEIGHT_SEGMENT ;

        $allWidthPotTypes = [];

        foreach ($infoSegmentWidthPotTypes as $segment => $infoSegmentWidthPotType)
        {
            foreach ($infoSegmentWidthPotType as $infoSegment)
            {
                if (isset($infoSegment['potType']))
                {
                    if (!isset($allWidthPotTypes[$infoSegment['potType']]))
                    {
                        $allWidthPotTypes[$infoSegment['potType']] = 0;
                    }

                    $value = ($infoSegment['width'] * 100) / $air;
                    $allWidthPotTypes[$infoSegment['potType']] += $infoSegment['width'];

                    if ($value)
                    {
                        $info[$segment]['series'][] = [
                            'value' => $value,
                            'className' => $infoSegment['potType']
                        ];
                    }
                }
            }
        }

        // On calcule le poucentage que prend chaque pot sur la table entière.
        foreach ($allWidthPotTypes as $potType => $allWidthPotType)
        {
            $value = ($allWidthPotType * 100) / ($air * 4);

            if ($value > 0)
            {
                $info['All']['series'][] = [
                    'value' => $value,
                    'className' => $potType
                ];
            }
        }
    }

    /**
     * On calcule le pourcentage vide de la table.
     *
     * @param $info
     */
    private function calculateEmptyPercentage(&$info): void
    {
        foreach ($info as $key => $item) {
            $empty = 100;

            if ($item && isset($info[$key]['series'])) {
                foreach ($item['series'] as $serie)
                {
                    $empty -= $serie['value'];
                }

                $info[$key]['available'] = (int)$empty;

                if ($empty > 0) {
                    $info[$key]['series'][] = [
                        'value' => $empty,
                        'label' => "tt",
                        'className' => 'RS-empty'
                    ];
                }
            }
        }
    }

    /**
     * On calcule le nombre de pot qui pourrait encore rentrer sur la table par segment et sur la table entière.
     *
     * @param array $info
     */
    private function potTypeThatCanFit(array &$info)
    {
        $potTypes = $this->potTypeManager->getPotTypes();

        foreach ($info as $key => $item)
        {
            /** @var PotType $potType */
            foreach ($potTypes as $potType) {
                if (isset($info[$key]['available']) && $potType->getReference() !== 'RS-70-N')
                {
                    $airSegmentAvailable = self::WIDTH_SEGMENT * $info[$key]['available'] / 100;

                    if ($key === 'All')
                    {
                        $airSegmentAvailable *= 4;
                    }

                    $belowTheMax = true;
                    $nbPot = 0;

                    do {
                        if ($potType->getDiameter() * ($nbPot + 1) <= $airSegmentAvailable)
                        {
                            $nbPot++;
                        } else {
                            $belowTheMax = false;
                        }
                    } while ($belowTheMax);

                    $info[$key]['potTypeThanCanFits'][$potType->getReference()] = $nbPot;
                }
            }
        }
    }

    /**
     * On calcule le nombre de pot qui sont présent sur la table par segment et sur la table entière.
     *
     * @param array $info
     * @param Greenhouse $greenhouse
     * @param CultureTable $cultureTable
     */
    private function potTypeOnCultureTable(array &$info, Greenhouse $greenhouse, CultureTable $cultureTable): void
    {
        $potTypes = $this->potTypeManager->getPotTypes();

        foreach ($info as $key => $item)
        {
            /** @var PotType $potType */
            foreach ($potTypes as $potType) {
                if (isset($info[$key]['available']) && $potType->getReference() !== 'RS-70-N')
                {
                    if ($key === 'All')
                    {
                        $nbPot = $greenhouse->getTrees()->filter(static function(Tree $tree) use ($cultureTable, $potType) {
                            return $tree->getCultureTable() === $cultureTable && $tree->getPotType() === $potType;
                        })->count();
                    } else {
                        $nbPot = $greenhouse->getTrees()->filter(static function(Tree $tree) use ($cultureTable, $potType, $key) {
                            return $tree->getCultureTable() === $cultureTable && $tree->getPotType() === $potType &&
                                $tree->getSegment() && (int)$tree->getSegment()->getName() === $key;
                        })->count();
                    }

                    $info[$key]['potTypeOnCultureTable'][$potType->getReference()] = $nbPot;
                }
            }
        }
    }

    /**
     * Retourne le tableau de tous les arbres présent dans une serre pour une table de culture donnée.
     *
     * @param Greenhouse $greenhouse
     * @param CultureTable $cultureTable
     * @return array
     */
    public function getAllTreeCultureTable(Greenhouse $greenhouse, CultureTable $cultureTable): array
    {
        return $greenhouse->getTrees()->filter(static function(Tree $tree) use ($cultureTable) {
            return $tree->getCultureTable() === $cultureTable;
        })->toArray();
    }

    /**
     * Construit le tableau des emplacements disponibles sur une table en fonction d'un tableau d'arbre.
     *
     * @param array $trees
     * @return array
     */
    public function createSchemaCultureTable(array $trees): array
    {
        $schemaCultureTable = [
            [
                [false, false, false, false, false, false],
                [false, false, false, false, false, false],
                [false, false, false, false, false, false],
                [false, false, false, false, false, false],
            ],
            [
                [false, false, false, false, false, false],
                [false, false, false, false, false, false],
                [false, false, false, false, false, false],
                [false, false, false, false, false, false],
            ],
            [
                [false, false, false, false, false, false],
                [false, false, false, false, false, false],
                [false, false, false, false, false, false],
                [false, false, false, false, false, false],
            ],
            [
                [false, false, false, false, false, false],
                [false, false, false, false, false, false],
                [false, false, false, false, false, false],
                [false, false, false, false, false, false],
            ]
        ];

        /** @var Tree $tree */
        foreach ($trees as $tree)
        {
            $segment = $tree->getSegment();
            $tableColumn = $tree->getTableColumn();
            $columnRow = $tree->getColumnRow();

            if ($tableColumn && $columnRow && $segment)
            {
                $segment = (int)$segment->getName() - 1;
                $tableColumn = (int)$tableColumn->getName() - 1;
                $columnRow = (int)$columnRow->getName() - 1;

                if (isset($schemaCultureTable[$segment][$tableColumn][$columnRow]))
                {
                    $schemaCultureTable[$segment][$tableColumn][$columnRow] = true;
                }

                // Vérifier la taille du pot, celui-çi peut prendre plusieurs cases.
                if ($potType = $tree->getPotType()) {
                    $this->applyPotPosition($schemaCultureTable, $potType->getReference(), $segment, $tableColumn, $columnRow);
                }

            }
        }

        return $schemaCultureTable;
    }

    /**
     * Retourne le nombre de place disponible sur une table.
     *
     * @param array $schemaCultureTable
     * @return int
     */
    private function getSpaceAvailable(array $schemaCultureTable): int
    {
        $spaceAvailable = 0;

        foreach ($schemaCultureTable as $segment)
        {
            foreach ($segment as $tableColumn)
            {
                foreach ($tableColumn as $columRow)
                {
                    if (!$columRow) {
                        $spaceAvailable++;
                    }
                }
            }
        }

        return $spaceAvailable;
    }

    /**
     * Modifie les places dispo d'une table en fonction des dimentions des pots.
     *
     * @param array $schemaCultureTable
     * @param string $referencePotType
     * @param int $segment
     * @param int $tableColumn
     * @param $columnRow
     */
    private function applyPotPosition(array &$schemaCultureTable, string $referencePotType, int $segment, int $tableColumn, $columnRow): void
    {
        switch ($referencePotType)
        {
            case 'RS-150-O':
                if (isset($schemaCultureTable[$segment][$tableColumn][$columnRow + 1])) {
                    $schemaCultureTable[$segment][$tableColumn][$columnRow + 1] = true;
                }
                break;

            case 'RS-270-R':
            case 'RS-460-J':
            case 'RS-740-V':
                $this->applyPotPosition($schemaCultureTable, 'RS-150-0', $segment, $tableColumn, $columnRow);

                if (isset($schemaCultureTable[$segment][$tableColumn + 1])) {
                    $schemaCultureTable[$segment][$tableColumn + 1][$columnRow] = true;
                }

                if (isset($schemaCultureTable[$segment][$tableColumn][$columnRow + 1])) {
                    $schemaCultureTable[$segment][$tableColumn][$columnRow + 1] = true;
                }

                if (isset($schemaCultureTable[$segment][$tableColumn + 1][$columnRow + 1])) {
                    $schemaCultureTable[$segment][$tableColumn + 1][$columnRow + 1] = true;
                }

                break;

            case 'RS-1050-B':
                $this->applyPotPosition($schemaCultureTable, 'RS-740-V', $segment, $tableColumn, $columnRow);

                if (isset($schemaCultureTable[$segment][$tableColumn][$columnRow + 2])) {
                    $schemaCultureTable[$segment][$tableColumn][$columnRow + 2] = true;
                }

                if (isset($schemaCultureTable[$segment][$tableColumn + 1][$columnRow + 2])) {
                    $schemaCultureTable[$segment][$tableColumn + 1][$columnRow + 2] = true;
                }

                break;

            case 'RS-1830-N':
                $this->applyPotPosition($schemaCultureTable, 'RS-1050-B', $segment, $tableColumn, $columnRow);

                if (isset($schemaCultureTable[$segment][$tableColumn + 2][$columnRow])) {
                    $schemaCultureTable[$segment][$tableColumn + 2][$columnRow] = true;
                }

                if (isset($schemaCultureTable[$segment][$tableColumn + 2][$columnRow + 1])) {
                    $schemaCultureTable[$segment][$tableColumn + 2][$columnRow + 1] = true;
                }

                if (isset($schemaCultureTable[$segment][$tableColumn + 2][$columnRow + 2])) {
                    $schemaCultureTable[$segment][$tableColumn + 2][$columnRow + 2] = true;
                }

                break;
        }
    }

    /**
     * Retourne si l'utilisateur a le droit de voir une serre donnée.
     *
     * @param User $user
     * @param Greenhouse $greenhouse
     * @return bool
     */
    public function userGrantedGreenhouse(User $user, Greenhouse $greenhouse): bool
    {
        if (in_array('ROLE_SUPER_ADMIN', $user->getRoles(), true))
        {
            return true;
        }

        return !$user->getManagementNurseries()->filter(static function(ManagementNursery $managementNursery) use ($greenhouse) {
           return $managementNursery->getNursery()->getGreenhouses()->contains($greenhouse);
        })->isEmpty();
    }
}
