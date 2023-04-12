<?php

namespace App\Manager;

use App\Entity\Style;
use App\Repository\StyleRepository;
use Doctrine\ORM\EntityManagerInterface;

class StyleManager
{
    private $styleRepository;
    private $em;

    public function __construct(StyleRepository $styleRepository, EntityManagerInterface $entityManager)
    {
        $this->styleRepository = $styleRepository;
        $this->em = $entityManager;
    }

    public function save(Style $style): void
    {
        $this->em->persist($style);
        $this->em->flush();
    }

    public function getStyles(): array
    {
        return $this->styleRepository->findAll();
    }

    public function delete(Style $style): void
    {
        $this->em->remove($style);
        $this->em->flush();
    }
}
