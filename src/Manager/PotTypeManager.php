<?php

namespace App\Manager;

use App\Entity\PotType;
use App\Repository\PotTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class PotTypeManager
{
    private $potTypeRepository;
    private $em;

    public function __construct(PotTypeRepository $potTypeRepository, EntityManagerInterface $entityManager)
    {
        $this->potTypeRepository = $potTypeRepository;
        $this->em = $entityManager;
    }

    public function save(PotType $potType): void
    {
        $this->em->persist($potType);
        $this->em->flush();
    }

    public function getPotTypes(): array
    {
        return $this->potTypeRepository->findAll();
    }

    public function delete(PotType $potType): void
    {
        $this->em->remove($potType);
        $this->em->flush();
    }
}
