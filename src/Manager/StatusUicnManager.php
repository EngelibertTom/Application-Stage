<?php

namespace App\Manager;

use App\Entity\StatusUicn;
use App\Repository\StatusUicnRepository;
use Doctrine\ORM\EntityManagerInterface;

class StatusUicnManager
{
    private $statusUicnRepository;
    private $em;

    public function __construct(StatusUicnRepository $statusUicnRepository, EntityManagerInterface $entityManager)
    {
        $this->statusUicnRepository = $statusUicnRepository;
        $this->em = $entityManager;
    }

    public function save(StatusUicn $statusUicn): void
    {
        $this->em->persist($statusUicn);
        $this->em->flush();
    }

    public function getStatusUicn(): array
    {
        return $this->statusUicnRepository->findAll();
    }

    public function getUicn(array $filter): ?StatusUicn
    {
        return $this->statusUicnRepository->findOneBy($filter);
    }

    public function delete(StatusUicn $statusUicn): void
    {
        $this->em->remove($statusUicn);
        $this->em->flush();
    }
}
