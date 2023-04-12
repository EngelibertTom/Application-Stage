<?php

namespace App\Manager;

use App\Entity\Work;
use App\Repository\StyleRepository;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;

class WorkManager
{
    private $workRepository;
    private $em;

    public function __construct(WorkRepository $workRepository, EntityManagerInterface $entityManager)
    {
        $this->workRepository = $workRepository;
        $this->em = $entityManager;
    }

    public function save(Work $work): void
    {
        $this->em->persist($work);
        $this->em->flush();
    }

    public function getWorks(): array
    {
        return $this->workRepository->findAll();
    }

    public function delete(Work $work): void
    {
        $this->em->remove($work);
        $this->em->flush();
    }
}
