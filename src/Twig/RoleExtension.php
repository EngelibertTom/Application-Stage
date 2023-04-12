<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RoleExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('labelRoles', [$this, 'labelRoles']),
        ];
    }

    public function labelRoles(array $roles)
    {
        $labels = '';

        foreach ($roles as $role)
        {
            switch($role)
            {
                case 'ROLE_VOLUNTEER':
                    $labelColor = 'info';
                    $libelle = 'Bénévole';
                    break;

                case 'ROLE_SPECIES_MANAGER':
                    $labelColor = 'warning';
                    $libelle = 'Gérant des espèces';
                    break;

                case 'ROLE_MANAGER':
                    $labelColor = 'danger';
                    $libelle = 'Gérant';
                    break;

                case 'ROLE_SUPER_ADMIN':
                    $labelColor = 'dark';
                    $libelle = 'Super Admin';
                    break;
            }

            if (isset($labelColor, $libelle)) {
                $labels .= sprintf('<div class="mb-2 mr-2 p-2 badge badge-%s">%s</div>', $labelColor, $libelle);
            }
        }

        return $labels;
    }
}
