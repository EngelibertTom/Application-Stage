<?php

namespace App\Manager;

use App\Entity\Observation;
use App\Repository\ObservationRepository;
use Doctrine\ORM\EntityManagerInterface;

class ObservationManager
{
    private $observationRepository;
    private $em;

    public function __construct(ObservationRepository $observationRepository, EntityManagerInterface $entityManager)
    {
        $this->observationRepository = $observationRepository;
        $this->em = $entityManager;
    }

    public function save(Observation $observation): void
    {
        $this->em->persist($observation);
        $this->em->flush();
    }

    public function getObservations(array $filter = [], $order = null, $start = null, $limit = null): array
    {
        return $this->observationRepository->findPaginator($filter, $order, $start, $limit);
    }

    public function delete(Observation $observation): void
    {
        $this->em->remove($observation);
        $this->em->flush();
    }
}
