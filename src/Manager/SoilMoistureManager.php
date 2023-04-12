<?php

namespace App\Manager;

class SoilMoistureManager extends DataManager
{
    public const VERY_FRESH = 1;
    public const FRESH = 2;
    public const DRY = 3;
    public const VERY_DRY = 4;

    public function getList(bool $reverse = false) : array
    {
        $list = [
            self::VERY_FRESH    => 'Très frais',
            self::FRESH         => 'Frais',
            self::DRY           => 'Sec',
            self::VERY_DRY      => 'Très sec',
        ];

        return $reverse ? array_flip($list) : $list;
    }
}
