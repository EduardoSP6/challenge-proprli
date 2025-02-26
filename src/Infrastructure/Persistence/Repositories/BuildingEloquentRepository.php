<?php

namespace Infrastructure\Persistence\Repositories;

use Application\Repositories\BuildingRepository;
use Domain\Core\Entity\Building;
use Infrastructure\Persistence\Models\Building as BuildingModel;

class BuildingEloquentRepository implements BuildingRepository
{
    /**
     * Create a new building.
     *
     * @param Building $building
     * @return void
     */
    public function create(Building $building): void
    {
        BuildingModel::query()->create([
            'id' => $building->getId()->value(),
            'owner_id' => $building->getOwner()->getId()->value(),
            'name' => $building->getName(),
            'address' => $building->getAddress(),
        ]);
    }
}
