<?php

namespace App\Controller;

use App\Service\FlashBagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GeneralController extends AbstractController
{
    private $flashService;

    public function __construct(FlashBagService $flashBagService)
    {
        $this->flashService = $flashBagService;
    }

    /**
     * Créer un message de succès
     */
    protected function flashSuccess(string $message): void
    {
        if (!$this->container->has('session')) {
            throw new \LogicException('You can not use the addFlash method if sessions are disabled. Enable them in "config/packages/framework.yaml".');
        }

        $this->flashService->addSuccess($message);
    }

    protected function flashError(string $message): void
    {
        if (!$this->container->has('session')) {
            throw new \LogicException('You can not use the addFlash method if sessions are disabled. Enable them in "config/packages/framework.yaml".');
        }

        $this->flashService->addError($message);
    }

    protected function flashWarning(string $message): void
    {
        if (!$this->container->has('session')) {
            throw new \LogicException('You can not use the addFlash method if sessions are disabled. Enable them in "config/packages/framework.yaml".');
        }

        $this->flashService->addWarning($message);
    }
}
