<?php

namespace App\Manager;

use App\Entity\TreePicture;
use App\Repository\TreePictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TreePictureManager
{
    private $treePictureRepository;
    private $em;
    private $parameterBag;

    public function __construct(TreePictureRepository $treePictureRepository, EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag)
    {
        $this->treePictureRepository = $treePictureRepository;
        $this->em = $entityManager;
        $this->parameterBag = $parameterBag;
    }


    public function save(TreePicture $treePicture): void
    {
        $this->em->persist($treePicture);
        $this->em->flush();
    }

    public function delete(TreePicture $treePicture): void
    {
        $path = $this->parameterBag->get('kernel.project_dir') . '/public/';

        if (($file = $treePicture->getPath()) && file_exists($path . $file))
        {
            unlink($path . $file);
        }

        if (($file = $treePicture->getPathOriginal()) && file_exists($path . $file))
        {
            unlink($path . $file);
        }

        $this->em->remove($treePicture);
        $this->em->flush();
    }
}
