<?php

namespace App\Twig;

use App\Entity\Tree;
use App\Manager\TreeStatusManager;
use App\Service\TreeService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TreeExtension extends AbstractExtension
{
    private $treeService;
    private $treeStatusManager;

    public function __construct(TreeService $treeService, TreeStatusManager $treeStatusManager)
    {
        $this->treeService = $treeService;
        $this->treeStatusManager = $treeStatusManager;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('mainPicture', [$this, 'getMainPicture']),
            new TwigFilter('status', [$this, 'getStatus']),
        ];
    }

    /**
     * Retourne le chemin de la photo de l'arbre à mettre en avant.
     *
     * @param Tree $tree
     * @return string
     */
    public function getMainPicture(Tree $tree): string
    {
        $picturePath = 'img/no-image-tree.png';

        if ($mainPicture = $this->treeService->getMainPicture($tree))
        {
            $picturePath = $mainPicture->getPath();
        }

        return $picturePath;
    }

    public function getStatus(Tree $tree): string
    {
        return sprintf('%s',
            $this->treeStatusManager->getStatus($tree->getStatus())
        );
    }
}
