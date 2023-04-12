<?php

namespace App\Manager;

use App\Entity\HistoryTree;
use Doctrine\ORM\EntityManagerInterface;

class HistoryTreeManager
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function save(HistoryTree $historyTree): void
    {
        $this->em->persist($historyTree);
        $this->em->flush();
    }

    public function delete(HistoryTree $historyTree): void
    {
        $this->em->remove($historyTree);
        $this->em->flush();
    }
}
