<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Persistence\Models\Owner;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="OwnerResource",
 *     type="object",
 *
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Owner name",
 *     ),
 *
 *     @OA\Property(
 *          property="email",
 *          type="string",
 *          description="Owner email",
 *     ),
 * )
 */
class OwnerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Owner $this */
        return [
            "name" => $this->name,
            "email" => $this->email
        ];
    }
}
