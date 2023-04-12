<?php

namespace App\Service;

use App\Entity\QrCode;

class EquipementService extends QrCodeService

{

    /**
     * @return string
     */
    protected function getNameRouteRedirectQrCode(): string
    {
        return 'app_admin_equipement';
    }

    /**
     * @param QrCode $entity
     * @return string
     */
    protected function getNameFileQrCode(QrCode $entity): string
    {
        return '/qrCode/qrCode-equipement-' . $entity->getId() . '.png';
    }
}