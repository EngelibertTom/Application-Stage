<?php

namespace App\Manager;

use App\Entity\TypeHistoryTree;
use App\Repository\TypeHistoryTreeRepository;
use Doctrine\ORM\EntityManagerInterface;

class TypeHistoryTreeManager
{
    private $em;
    private $typeHistoryTreeRepository;

    public function __construct(EntityManagerInterface $entityManager, TypeHistoryTreeRepository $typeHistoryTreeRepository)
    {
        $this->em = $entityManager;
        $this->typeHistoryTreeRepository = $typeHistoryTreeRepository;
    }

    public function getTypeHistoryTree(int $id): ?TypeHistoryTree
    {
        return $this->typeHistoryTreeRepository->find($id);
    }
}
