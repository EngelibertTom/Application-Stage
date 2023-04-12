<?php

namespace App\Manager;

class TreeStatusManager extends DataManager
{
    public const NOT_ADOPTABLE = 1; // Non adoptable
    public const ADOPTABLE = 2; // Adoptable
    public const SPONSOR = 3; // Parrainer
    public const ADOPT = 4; // Adopter
    public const DEAD = 5; // Mort
    public const SPONSORABLE = 6; // Parrainable

    public function getList(bool $reverse = false) : array
    {
        $list = [
            self::NOT_ADOPTABLE => 'Non adoptable',
            self::ADOPTABLE     => 'Adoptable',
            self::SPONSORABLE   => 'Parrainable',
            self::ADOPT         => 'Adopter',
            self::SPONSOR       => 'Parrainer',
            self::DEAD          => 'Mort'
        ];

        return $reverse ? array_flip($list) : $list;
    }

    public function getStatus(int $id) : string
    {
        return $this->getList()[$id];
    }

    public function getIcon(int $id): string
    {
        $list = $this->getList();
        $icon = '';

        switch ($id)
        {
            case self::NOT_ADOPTABLE:
                $icon = '<span class="material-icons text-danger" title="' . $list[$id] . '">do_not_disturb_on</span>';
                break;

            case self::ADOPTABLE:
                $icon = '<span class="material-icons text-info" title="' . $list[$id] . '">check_circle</span>';
                break;

            case self::SPONSORABLE:
                $icon = '<span class="material-icons text-info" title="' . $list[$id] . '">check_circle</span>';
                break;

            case self::SPONSOR:
                $icon = '<span class="material-icons text-warning" title="' . $list[$id] . '">business_center</span>';
                break;

            case self::ADOPT:
                $icon = '<span class="material-icons text-success" title="' . $list[$id] . '">emoji_emotions</span>';
                break;

            case self::DEAD:
                $icon = '<span class="material-icons text-dark" title="' . $list[$id] . '">sick</span>';
                break;
        }

        return $icon;
    }
}
