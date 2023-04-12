<?php

namespace App\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class CheckValidSessionListener implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['validSession', 0],
            ]
        ];
    }

    public function validSession(RequestEvent $event)
    {
        // Si le compte utilisateur est desactivée on deconnecte la session.
        if (($user = $this->security->getUser()) && !$user->getActive())
        {
            $session = $event->getRequest()->getSession();
            $session->invalidate(1);
        }
    }
}
