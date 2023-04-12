<?php

namespace App\Twig;

use App\Entity\User;
use App\Manager\ExposureManager;
use App\Manager\FertilizerManager;
use App\Service\UserService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DataExtension extends AbstractExtension
{
    private $exposureManager;
    private $fertilizerManager;

    public function __construct(ExposureManager $exposureManager, FertilizerManager $fertilizerManager)
    {
        $this->exposureManager = $exposureManager;
        $this->fertilizerManager = $fertilizerManager;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('labelExposure', [$this, 'labelExposure']),
            new TwigFilter('labelFertilizer', [$this, 'labelFertilizer']),
        ];
    }

    public function labelExposure(int $id): string
    {
        return $this->exposureManager->get($id) ?? '';
    }

    public function labelFertilizer(int $id): string
    {
        return $this->fertilizerManager->get($id) ?? '';
    }
}
