<?php

namespace App\EventListener;


use App\Entity\Equipement;
use App\Entity\Maintenance;
use App\Entity\Entretien;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;


class EntretienListener
{


private $security;

    public function __construct( Security $security  )
    {
    $this->security = $security;



    }

    public function postPersist( Entretien $entretien,  LifecycleEventArgs $event): void
    {
        /** @var EntityManagerInterface $em */


        $em = $event->getEntityManager();


        $maintenance = new Maintenance();
        $maintenance ->setUrgence($entretien->getUrgence());
        $maintenance ->setDate($entretien->getDateRealisation());
        $maintenance ->setUser($this->security->getUser());
        $maintenance ->setEntretien($entretien);
        $maintenance ->setEquipement($entretien->getEquipement());



        $em->persist($maintenance);
        $em->flush();
    }
}