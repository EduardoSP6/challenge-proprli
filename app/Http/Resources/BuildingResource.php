<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Persistence\Models\Building;

class BuildingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Building $this */
        return [
            'id' => $this->id,
            'owner_id',
            'name' => $this->name,
            'address' => $this->address,
        ];
    }
}
