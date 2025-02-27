<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Persistence\Models\Building;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="BuildingResource",
 *     type="object",
 *
 *     @OA\Property(
 *          property="id",
 *          type="string",
 *          format="uuid",
 *          description="Building ID"
 *     ),
 *
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Building name"
 *     ),
 *
 *     @OA\Property(
 *          property="address",
 *          type="string",
 *          description="Building address"
 *     ),
 * )
 */
class BuildingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Building $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
        ];
    }
}
