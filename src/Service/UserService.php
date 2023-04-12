<?php

namespace App\Service;

use App\Entity\Nursery;
use App\Entity\User;

class UserService
{
    private $nurseryService;

    public function __construct(NurseryService $nurseryService)
    {
        $this->nurseryService = $nurseryService;
    }

    /**
     * Retourne la pépnière principale d'un utilisateur.
     *
     * @param User $user
     * @return Nursery|null
     */
    public function getMainNursery(User $user): ?Nursery
    {
        return $this->nurseryService->getMainNursery($user->getManagementNurseries()->toArray());
    }

    /**
     * Retourne la liste des pépinières dont l'utilisateur est responsable.
     *
     * @param User $user
     * @return array
     */
    public function getNurseries(User $user): array
    {
        $nurseries = [];

        foreach ($user->getManagementNurseries() as $managementNursery)
        {
            $nurseries[] = $managementNursery->getNursery();
        }

        return $nurseries;
    }
}
