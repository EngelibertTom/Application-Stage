<?php

namespace App\EventListener;

use App\Entity\TreePicture;
use App\Manager\TreePictureManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TreePictureListener
{
    private $treePictureManager;

    public function __construct(TreePictureManager $treePictureManager)
    {
        $this->treePictureManager = $treePictureManager;
    }

    public function postPersist(TreePicture $treePicture, LifecycleEventArgs $event): void
    {
//        $treePicture->setPath('upload/tree/' . $treePicture->getTree()->getId() . '/' .  $treePicture->getId() . '.png');

        $this->treePictureManager->save($treePicture);
    }

}
