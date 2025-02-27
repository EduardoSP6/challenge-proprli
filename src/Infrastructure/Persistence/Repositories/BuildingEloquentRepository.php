<?php

namespace Infrastructure\Persistence\Repositories;

use Application\Repositories\BuildingRepository;
use Domain\Core\Entity\Building;
use Domain\Core\Entity\Owner;
use Domain\Shared\ValueObject\Uuid;
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

    public function find(string $id): ?Building
    {
        /** @var BuildingModel $buildingModel */
        $buildingModel = BuildingModel::query()
            ->firstWhere('id', '=', $id);

        if (!$buildingModel) return null;

        $owner = new Owner(
            id: new Uuid($buildingModel->owner->id),
            name: $buildingModel->owner->name,
            email: $buildingModel->owner->email,
        );

        return new Building(
            id: new Uuid($buildingModel->id),
            owner: $owner,
            name: $buildingModel->name,
            address: $buildingModel->address,
            createdAt: $buildingModel->created_at,
            updatedAt: $buildingModel->updated_at
        );
    }
}
