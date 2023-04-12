<?php

namespace App\Manager;

use App\Repository\LocationRepository;

class LocationManager
{
    private $locationRepository;

    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    public function getLocations(array $filter = []): array
    {
        return $this->locationRepository->findBy($filter);
    }
}
