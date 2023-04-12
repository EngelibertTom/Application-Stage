<?php

namespace App\Manager;

class ExposureManager extends DataManager
{
    public const SUN = 1;
    public const HALF_SHADE = 2;
    public const SHADOW = 3;

    public function getList(bool $reverse = false) : array
    {
        $list = [
            self::SUN           => 'Soleil',
            self::HALF_SHADE    => 'Mi-ombre',
            self::SHADOW        => 'Ombre'
        ];

        return $reverse ? array_flip($list) : $list;
    }
}
