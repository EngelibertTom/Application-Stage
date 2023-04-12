<?php

namespace App\Manager;

use App\Entity\Adoption;
use App\Repository\AdoptionRepository;
use Doctrine\ORM\EntityManagerInterface;

class AdoptionManager
{
    private $adoptionRepository;
    private $em;

    public function __construct(AdoptionRepository $adoptionRepository, EntityManagerInterface $entityManager)
    {
        $this->adoptionRepository = $adoptionRepository;
        $this->em = $entityManager;
    }

    public function save(Adoption $adoption): void
    {
        $this->em->persist($adoption);
        $this->em->flush();
    }

    public function getAdoption(array $filter): ?Adoption
    {
        return $this->adoptionRepository->findOneBy($filter);
    }

    public function delete(Adoption $adoption): void
    {
        $tree = $adoption->getTree();

        $status = [ TreeStatusManager::ADOPT, TreeStatusManager::SPONSOR ];

        if ($tree && in_array($tree->getStatus(), $status))
        {
            $tree->setStatus(TreeStatusManager::ADOPTABLE);
            $this->em->persist($tree);
        }

        $this->em->remove($adoption);
        $this->em->flush();
    }

    public function annualStat(int $year): array
    {
        $stat = [];

        for ($month = 1; $month < 13; $month++)
        {
            $stat[] = $this->adoptionRepository->getMonthStat($year, $month);
        }

        return $stat;
    }

}
