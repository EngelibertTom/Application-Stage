<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FlashBagService
{
    private $session;

    public function __construct(ContainerInterface $container)
    {
        $this->session = $container->get('session');
    }

    public function addError(string $message) {
        $this->session->getFlashBag()->add('danger',
            sprintf('<i class="fa fa-exclamation-circle"></i> <span class="font-weight-bold"> %s! </span> %s',
                'Erreur', $message
            )
        );
    }

    public function addSuccess(string $message) {
        $this->session->getFlashBag()->add('success', sprintf('<i class="fa fa-check-circle"></i> %s', $message));
    }

    public function addWarning(string $message) {
        $this->session->getFlashBag()->add('warning', sprintf('<i class="fa fa-exclamation-triangle"></i> %s', $message));
    }
}
