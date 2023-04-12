<?php

namespace App\EventListener;


use App\Entity\TreeWork;
use App\Entity\TypeHistoryTree;
use App\Entity\User;
use App\Manager\TreeManager;
use App\Manager\TypeHistoryTreeManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class TreeWorkListener
{
    private $treeManager;
    private $typeHistoryTreeManager;

    /** @var User $currentUser */
    private $currentUser;

    public function __construct(Security $security, TreeManager $treeManager, TypeHistoryTreeManager $typeHistoryTreeManager)
    {
        $this->treeManager = $treeManager;
        $this->typeHistoryTreeManager = $typeHistoryTreeManager;
        $this->currentUser = $security->getUser();
    }

    public function prePersist(TreeWork $treeWork, LifecycleEventArgs $event): void
    {
        $treeWork->setUser($this->currentUser);
    }

    public function postPersist(TreeWork $treeWork, LifecycleEventArgs $event): void
    {
        $typeHistoryTree = $this->typeHistoryTreeManager->getTypeHistoryTree(TypeHistoryTree::TYPE_WORK);

        if ($typeHistoryTree)
        {
            $contentHistory = sprintf(
                'Travaux <b>%s</b> effectué',
                $treeWork->getWork()->getName()
            );

            $this->treeManager->addHistory($treeWork->getTree(), $contentHistory, $treeWork->getUser(), $typeHistoryTree, false);
        }
    }
}
