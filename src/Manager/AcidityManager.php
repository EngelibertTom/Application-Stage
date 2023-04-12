<?php

namespace App\Manager;

class AcidityManager extends DataManager
{
    public const VERY_ACIDIC = 1;
    public const ACIDIC  = 2;
    public const ACIDIC2  = 3;
    public const ACIDIC3  = 4;
    public const NEUTRAL = 5;
    public const NEUTRAL2 = 6;
    public const BASIC = 7;
    public const BASIC2 = 8;

    public function getList(bool $reverse = false) : array
    {
        $list = [
            self::VERY_ACIDIC   => '< 4.5',
            self::ACIDIC        => '5',
            self::ACIDIC2       => '5,5',
            self::ACIDIC3       => '6',
            self::NEUTRAL       => '6,5',
            self::NEUTRAL2      => '7',
            self::BASIC         => '7,5',
            self::BASIC2        => '> 8',
        ];

        return $reverse ? array_flip($list) : $list;
    }

    public function getAcidity(string $value) : ?int
    {
        foreach ($this->getList() as $key => $acidity)
        {
            if (str_replace(['< ', '> '], '', $acidity) === $value)
            {
                return $key;
            }
        }

        return null;
    }
}
