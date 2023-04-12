<?php

namespace App\Manager;

use App\Entity\Species;
use App\Repository\SpeciesRepository;
use App\Service\MailerService;
use App\Service\SpeciesService;
use Doctrine\ORM\EntityManagerInterface;

class SpeciesManager
{
    private $speciesRepository;
    private $speciesService;
    private $em;

    public function __construct(SpeciesRepository $speciesRepository, EntityManagerInterface $entityManager,
                                SpeciesService $speciesService)
    {
        $this->speciesRepository = $speciesRepository;
        $this->speciesService = $speciesService;
        $this->em = $entityManager;
    }

    public function save(Species $species): void
    {
        $this->em->persist($species);
        $this->em->flush();

        // Envoie l'email de validation au responsable des espèces.
        if (!$species->getValidate())
        {
            $this->speciesService->sendMailsValidation($species);
        }
    }

    public function getSpecies(array $filter = [], $order = null, $start = null, $limit = null): array
    {
        return $this->speciesRepository->findPaginator($filter, $order, $start, $limit);
    }

    public function getSpecie(array $filter = []): ?Species
    {
        return $this->speciesRepository->findOneBy($filter);
    }

    public function delete(Species $species): void
    {
        $this->em->remove($species);
        $this->em->flush();
    }
}
