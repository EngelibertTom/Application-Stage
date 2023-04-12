<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class ExceptionListener
{
    private $urlGenerator;
    private $security;

    public function __construct(UrlGeneratorInterface $urlGenerator, Security $security)
    {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }

    public function onKernelException(ExceptionEvent $event) : void
    {
        /** @var NotFoundHttpException $exception */
        if ($this->security->getUser() && ($exception = $event->getThrowable()) instanceof NotFoundHttpException)
        {
            $statusCode = $exception->getStatusCode();

            switch ($statusCode)
            {
                case 404:
                    $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_admin_notfound')));
                    break;
            }
        }
    }
}
