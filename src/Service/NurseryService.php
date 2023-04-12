<?php

namespace App\Service;

use App\Entity\ManagementNursery;
use App\Entity\Nursery;

class NurseryService
{
    /**
     * Retourne la pépinière principale d'une liste de pépinière.
     *
     * @param array $managementNurseries
     * @return Nursery|null
     */
    public function getMainNursery(array $managementNurseries): ?Nursery
    {
        $nursery = null;

        /** @var ManagementNursery $managementNursery */
        foreach ($managementNurseries as $managementNursery)
        {
            if ($managementNursery->getDefaultNursery())
            {
                return $managementNursery->getNursery();
            }
        }

        if (isset($managementNurseries[0]))
        {
            $nursery = $managementNurseries[0]->getNursery();
        }

        return $nursery;
    }
}
