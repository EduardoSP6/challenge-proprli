<?php

namespace Application\Repositories;

use Domain\Core\Entity\Building;

interface BuildingRepository
{
    public function create(Building $building): void;
}
