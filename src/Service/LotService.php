<?php

namespace App\Service;

use App\Entity\QrCode;

class LotService extends QrCodeService
{
    /**
     * @return string
     */
    protected function getNameRouteRedirectQrCode(): string
    {
        return 'app_admin_lot_edit';
    }

    /**
     * @param QrCode $entity
     * @return string
     */
    protected function getNameFileQrCode(QrCode $entity): string
    {
        return '/qrCode/qrCode-lot-' . $entity->getId() . '.png';
    }
}
