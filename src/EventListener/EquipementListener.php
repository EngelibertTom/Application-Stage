<?php

namespace App\EventListener;

use App\Entity\Entretien;
use App\Entity\Equipement;
use App\Entity\Maintenance;
use App\Service\EquipementService;
use App\Service\NurseryService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\DateTime;

class EquipementListener
{
    private $equipementService;




    public function __construct(EquipementService $equipementService, Security $security)
    {
        $this->equipementService = $equipementService;

    }

    public function postPersist(Equipement $equipement, LifecycleEventArgs $event): void
    {
        /** @var EntityManagerInterface $em */
        $em = $event->getEntityManager();

        $fileQrCode = $this->equipementService->generateQrCode($equipement);
        $equipement->setQrCode($fileQrCode);


        $em->persist($equipement);
        $em->flush();

    }

}