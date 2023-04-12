<?php

namespace App\EventListener;

use App\Entity\Tree;
use App\Entity\TypeHistoryTree;
use App\Entity\User;
use App\Manager\NurseryManager;
use App\Manager\TreeManager;
use App\Manager\TypeHistoryTreeManager;
use App\Service\NurseryService;
use App\Service\TreeService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class TreeListener
{
    private $treeService;
    private $treeManager;
    private $typeHistoryTreeManager;
    private $nurseryService;

    /** @var User $currentUser */
    private $currentUser;

    public function __construct(TreeService $treeService, TreeManager $treeManager, TypeHistoryTreeManager $typeHistoryTreeManager,
                                Security $security, NurseryService $nurseryService)
    {
        $this->treeService = $treeService;
        $this->treeManager = $treeManager;
        $this->typeHistoryTreeManager = $typeHistoryTreeManager;
        $this->currentUser = $security->getUser();
        $this->nurseryService = $nurseryService;
    }

    public function prePersist(Tree $tree, LifecycleEventArgs $event): void
    {
        // Si la pépinière n'a pas pu être renseignée alors on prend celle par défaut de l'utilisateur courant.
        if (!$tree->getNursery())
        {
            $nursery = $this->nurseryService->getMainNursery($this->currentUser->getManagementNurseries()->toArray());

            $tree->setNursery($nursery);
        }
    }

    public function postPersist(Tree $tree, LifecycleEventArgs $event): void
    {
        /** @var EntityManagerInterface $em */
        $em = $event->getEntityManager();

        $fileQrCode = $this->treeService->generateQrCode($tree);
        $tree->setQrCode($fileQrCode);

        if ($this->currentUser)
        {
            $typeHistoryTree = $this->typeHistoryTreeManager->getTypeHistoryTree(TypeHistoryTree::TYPE_CREATE);

            $contentHistory = sprintf('Ajout de l\'arbre dans la pépinière "<b>%s</b>"', (string)$tree->getNursery());
            $this->treeManager->addHistory($tree, $contentHistory, $this->currentUser, $typeHistoryTree, true);
        }

        $this->treeManager->updateStatus($tree);

        $em->persist($tree);
        $em->flush();
    }

    public function postUpdate(Tree $tree, LifecycleEventArgs $event): void
    {
        $dataUpdate = $event->getEntityManager()->getUnitOfWork()->getEntityChangeSet($tree);

        if ((isset($dataUpdate['columnRow']) && $dataUpdate['columnRow'][0]) ||
            (isset($dataUpdate['tableColumn']) && $dataUpdate['tableColumn'][0]) ||
            (isset($dataUpdate['segment']) && $dataUpdate['segment'][0]) ||
            (isset($dataUpdate['cultureTable']) && $dataUpdate['cultureTable'][0]) ||
            (isset($dataUpdate['greenhouse']) && $dataUpdate['greenhouse'][0])
        )
        {
            $typeHistoryTree = $this->typeHistoryTreeManager->getTypeHistoryTree(TypeHistoryTree::TYPE_MOVE);
            $this->treeManager->addHistory($tree,
                sprintf("L'arbre a été déplacé de <b>%s-%s-%s-%s-%s</b> vers <b>%s-%s-%s-%s-%s</b>",
                    $dataUpdate['greenhouse'][0] ?? $tree->getGreenhouse(),
                    $dataUpdate['cultureTable'][0] ?? $tree->getCultureTable(),
                    $dataUpdate['segment'][0] ?? $tree->getSegment(),
                    $dataUpdate['tableColumn'][0] ?? $tree->getTableColumn(),
                    $dataUpdate['columnRow'][0] ?? $tree->getColumnRow(),
                    $tree->getGreenhouse(),
                    $tree->getCultureTable(),
                    $tree->getSegment(),
                    $tree->getTableColumn(),
                    $tree->getColumnRow()
                ),
                $this->currentUser,
                $typeHistoryTree
            );
        }
    }

    public function preRemove(Tree $tree, LifecycleEventArgs $event): void
    {
        $this->treeService->removeQrCode($tree);
    }
}
