<?php

namespace App\Manager;

use App\Entity\LeafType;
use App\Repository\LeafTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class LeafTypeManager
{
    private $leafTypeRepository;
    private $em;

    public function __construct(LeafTypeRepository $leafTypeRepository, EntityManagerInterface $entityManager)
    {
        $this->leafTypeRepository = $leafTypeRepository;
        $this->em = $entityManager;
    }

    public function save(LeafType $leafType): void
    {
        $this->em->persist($leafType);
        $this->em->flush();
    }

    public function getLeafTypes(): array
    {
        return $this->leafTypeRepository->findAll();
    }

    public function getLeafType($filter): ?LeafType
    {
        return $this->leafTypeRepository->findOneBy($filter);
    }

    public function delete(LeafType $leafType): void
    {
        $this->em->remove($leafType);
        $this->em->flush();
    }
}
