<?php

namespace App\Twig;

use App\Entity\Nursery;
use App\Entity\User;
use App\Service\UserService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class NurseryExtension extends AbstractExtension
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('mainNursery', [$this, 'mainNursery']),
            new TwigFilter('nurseries', [$this, 'getNurseries'])
        ];
    }

    /**
     * Retourne la pépinière principale d'un utilisateur.
     *
     * @param User $user
     * @return Nursery|null
     */
    public function mainNursery(User $user): ?Nursery
    {
        return $this->userService->getMainNursery($user);
    }

    /**
     * Retourne le tableau de toutes les pépinières pour un utilisateur.
     *
     * @param User $user
     * @return array
     */
    public function getNurseries(User $user): array
    {
        return $this->userService->getNurseries($user);
    }
}
