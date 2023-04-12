<?php

namespace App\Manager;

use App\Entity\HistoryTree;
use App\Entity\Tree;
use App\Entity\TypeHistoryTree;
use App\Entity\User;
use App\Repository\TreeRepository;
use Doctrine\ORM\EntityManagerInterface;

class TreeManager
{
    private $treeRepository;
    private $em;

    public function __construct(TreeRepository $treeRepository, EntityManagerInterface $entityManager)
    {
        $this->treeRepository = $treeRepository;
        $this->em = $entityManager;
    }

    public function save(Tree $tree): void
    {
        if ($greenhouse = $tree->getGreenhouse())
        {
            $nursery = $greenhouse->getNursery();
            $tree->setNursery($nursery);
        }

        foreach ($tree->getWorks() as $work)
        {
            $work->setTree($tree);
            $this->em->persist($work);
        }

        foreach ($tree->getObservations() as $observation)
        {
            $observation->setTree($tree);
            $this->em->persist($observation);
        }

        if (($adoption = $tree->getAdoption()) && !$adoption->getOwner())
        {
            $tree->setAdoption(null);
        }

        $this->updateStatus($tree);

        $this->em->persist($tree);
        $this->em->flush();
    }

    public function getTree(int $id): ?Tree
    {
        return $this->treeRepository->find($id);
    }

    public function getTrees(array $filter = [], $order = null, $start = null, $limit = null): array
    {
        return $this->treeRepository->findPaginator($filter, $order, $start, $limit);
    }

    public function getAdoptableTrees(): array
    {
        return $this->getTrees([
            'in' => [
                't.status' => [ TreeStatusManager::ADOPTABLE, TreeStatusManager::ADOPT, TreeStatusManager::SPONSOR, TreeStatusManager::SPONSORABLE ]
            ]
        ]);
    }

    public function count(array $filter = []): int
    {
        return $this->treeRepository->count($filter);
    }

    public function delete(Tree $tree): void
    {
        $this->em->remove($tree);
        $this->em->flush();
    }

    /**
     * Ajoute un historique sur un arbre.
     *
     * @param Tree $tree
     * @param string $content
     * @param User $user
     * @param TypeHistoryTree $typeHistoryTree
     * @param bool $visiblePublic
     */
    public function addHistory(Tree $tree, string $content, User $user, TypeHistoryTree $typeHistoryTree = null, bool $visiblePublic = false): void
    {
        if ($typeHistoryTree)
        {
            $historyTree = new HistoryTree();
            $historyTree->setUser($user);
            $historyTree->setContent($content);
            $historyTree->setTree($tree);
            $historyTree->setVisiblePublic($visiblePublic);
            $historyTree->setType($typeHistoryTree);

            $tree->addHistoryTree($historyTree);

            $this->em->persist($tree);
            $this->em->flush();
        }
    }

    /**
     * Met à jour le statut de l'arbre en fonction de plusieurs critères.
     * @param Tree $tree
     */
    public function updateStatus(Tree $tree): void
    {
        $status = $tree->getStatus();

        if ($status !== TreeStatusManager::SPONSOR && $status !== TreeStatusManager::ADOPT && $tree->getAdoption())
        {
            $status = TreeStatusManager::SPONSOR;
        }

        elseif ($tree->getDeadTree())
        {
            $status = TreeStatusManager::DEAD;
        }

        $tree->setStatus($status);
    }
}
