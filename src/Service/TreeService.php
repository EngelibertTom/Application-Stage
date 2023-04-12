<?php

namespace App\Service;

use App\Entity\QrCode;
use App\Entity\Tree;
use App\Entity\TreePicture;

class TreeService extends QrCodeService
{
    /**
     * @return string
     */
    protected function getNameRouteRedirectQrCode(): string
    {
        return 'app_tree_detail';
    }

    /**
     * @param QrCode $entity
     * @return string
     */
    protected function getNameFileQrCode(QrCode $entity): string
    {
        return '/qrCode/qrCode-tree-' . $entity->getId() . '.png';
    }

    /**
     * Retourne la photo principale de l'arbre.
     *
     * @param Tree $tree
     * @return TreePicture|null
     */
    public function getMainPicture(Tree $tree): ?TreePicture
    {
        $treePicture = $tree->getTreePictures()->filter(static function(TreePicture $treePicture) {
            return $treePicture->getFeatured();
        })->first();

        if (!$treePicture)
        {
            $treePicture = $tree->getTreePictures()->first();
        }

        return $treePicture instanceof TreePicture ? $treePicture : null;
    }
}
