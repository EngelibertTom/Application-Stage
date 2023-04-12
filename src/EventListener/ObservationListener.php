<?php

namespace App\EventListener;


use App\Entity\Observation;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class ObservationListener
{
    /** @var User $currentUser */
    private $currentUser;

    public function __construct(Security $security)
    {
        $this->currentUser = $security->getUser();
    }

    public function prePersist(Observation $observation, LifecycleEventArgs $event): void
    {
        $observation->setUser($this->currentUser);
    }
}
