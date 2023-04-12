<?php

namespace App\EventListener;

use App\Entity\Lot;
use App\Service\LotService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Filesystem\Filesystem;

class LotListener
{
    private $lotService;
    private $filesystem;

    public function __construct(LotService $lotService, Filesystem $filesystem)
    {
        $this->lotService = $lotService;
        $this->filesystem = $filesystem;
    }

    public function postPersist(Lot $lot, LifecycleEventArgs $event)
    {
        /** @var EntityManagerInterface $em */
        $em = $event->getEntityManager();

        $fileQrCode = $this->lotService->generateQrCode($lot);
        $lot->setQrCode($fileQrCode);

        if (!$lot->getName())
        {
            $lot->setName('Lot #' . $lot->getId());
        }

        $em->persist($lot);
        $em->flush();
    }

    public function preRemove(Lot $lot, LifecycleEventArgs $event)
    {
        $this->lotService->removeQrCode($lot);
    }
}
