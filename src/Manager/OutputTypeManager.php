<?php

namespace App\Manager;

use App\Entity\OutputType;
use App\Repository\OutputTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class OutputTypeManager
{
    private $outputTypeRepository;
    private $em;

    public function __construct(OutputTypeRepository $outputTypeRepository, EntityManagerInterface $entityManager)
    {
        $this->outputTypeRepository = $outputTypeRepository;
        $this->em = $entityManager;
    }

    public function save(OutputType $outputType): void
    {
        $this->em->persist($outputType);
        $this->em->flush();
    }

    public function getOutputType($id): ?OutputType
    {
        return $this->outputTypeRepository->find($id);
    }

    public function getOutputTypes(): array
    {
        return $this->outputTypeRepository->findAll();
    }

    public function delete(OutputType $outputType): void
    {
        $this->em->remove($outputType);
        $this->em->flush();
    }
}
