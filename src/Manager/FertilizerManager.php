<?php

namespace App\Manager;

class FertilizerManager extends DataManager
{
    public const RICH = 1;
    public const NORMAL = 2;
    public const POOR = 3;

    public function getList(bool $reverse = false) : array
    {
        $list = [
            self::RICH    => 'Riche',
            self::NORMAL  => 'Mi-ombre',
            self::POOR    => 'Pauvre'
        ];

        return $reverse ? array_flip($list) : $list;
    }
}
