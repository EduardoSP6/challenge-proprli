<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Persistence\Models\User;

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
