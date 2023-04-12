<?php

namespace App\EventListener;

use App\Entity\DeadTree;
use App\Manager\TreeStatusManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

class DeadTreeListener
{
    public function prePersist(DeadTree $deadTree, LifecycleEventArgs $event): void
    {
        $tree = $deadTree->getTree();

        if ($tree)
        {
            $tree->setOutput(true);
            $tree->setStatus(TreeStatusManager::DEAD);
        }
    }
}
