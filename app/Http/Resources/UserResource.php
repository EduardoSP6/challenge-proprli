<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Persistence\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="User name",
 *     ),
 *
 *     @OA\Property(
 *          property="email",
 *          type="string",
 *          description="User email",
 *     ),
 * )
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var User $this */
        return [
            "name" => $this->name,
            "email" => $this->email
        ];
    }
}
