<?php

namespace App\Service;

use App\Entity\Species;
use App\Entity\User;
use App\Manager\UserManager;

class SpeciesService
{
    private $mailerService;
    private $userManager;

    public function __construct(MailerService $mailerService, UserManager $userManager)
    {
        $this->mailerService = $mailerService;
        $this->userManager = $userManager;
    }

    public function sendMailsValidation(Species $species): void
    {
        $speciesManagers = $this->userManager->getUsersByRole(['ROLE_SPECIES_MANAGER']);

        /** @var User $speciesManager */
        foreach ($speciesManagers as $speciesManager)
        {
            $this->mailerService->sendSpeciesValidation($speciesManager, $species);
        }
    }
}
