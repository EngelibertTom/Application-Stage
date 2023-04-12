<?php

namespace App\Manager;

use App\Entity\Nursery;
use App\Repository\NurseryRepository;
use Doctrine\ORM\EntityManagerInterface;

class NurseryManager
{
    private $nurseryRepository;
    private $em;

    public function __construct(NurseryRepository $nurseryRepository, EntityManagerInterface $entityManager)
    {
        $this->nurseryRepository = $nurseryRepository;
        $this->em = $entityManager;
    }

    public function save(Nursery $nursery): void
    {
        foreach ($nursery->getLocations() as $location)
        {
            $location->setNursery($nursery);
            $this->em->persist($location);
        }

        $this->em->persist($nursery);
        $this->em->flush();
    }

    public function getNursery(int $id): ?Nursery
    {
        return $this->nurseryRepository->find($id);
    }

    public function getNurseries(array $filter = []): array
    {
        return $this->nurseryRepository->findBy($filter);
    }

    public function delete(Nursery $nursery): void
    {
        $this->em->remove($nursery);
        $this->em->flush();
    }
}
