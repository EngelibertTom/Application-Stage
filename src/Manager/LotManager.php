<?php

namespace App\Manager;

use App\Entity\Lot;
use App\Repository\LotRepository;
use Doctrine\ORM\EntityManagerInterface;

class LotManager
{
    private $lotRepository;
    private $em;

    public function __construct(LotRepository $lotRepository, EntityManagerInterface $entityManager)
    {
        $this->lotRepository = $lotRepository;
        $this->em = $entityManager;
    }

    public function save(Lot $lot): void
    {
        $this->em->persist($lot);
        $this->em->flush();
    }

    public function getLot(int $id): ?Lot
    {
        return $this->lotRepository->find($id);
    }

    public function getLots(array $filter = [], $order = null, $start = null, $limit = null): array
    {
        return $this->lotRepository->findPaginator($filter, $order, $start, $limit);
    }

    public function delete(Lot $lot): void
    {
        $this->em->remove($lot);
        $this->em->flush();
    }
}
