<?php

namespace App\Manager;

use App\Entity\LotPicture;
use App\Entity\TreePicture;
use App\Repository\LotPictureRepository;
use App\Repository\TreePictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LotPictureManager
{
    private $em;
    private $parameterBag;

    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag)
    {
        $this->em = $entityManager;
        $this->parameterBag = $parameterBag;
    }


    public function save(LotPicture $lotPicture): void
    {
        $this->em->persist($lotPicture);
        $this->em->flush();
    }

    public function delete(LotPicture $lotPicture): void
    {
        $path = $this->parameterBag->get('kernel.project_dir') . '/public/';

        if (($file = $lotPicture->getPath()) && file_exists($path . $file))
        {
            unlink($path . $file);
        }

        if (($file = $lotPicture->getPathOriginal()) && file_exists($path . $file))
        {
            unlink($path . $file);
        }

        $this->em->remove($lotPicture);
        $this->em->flush();
    }
}
