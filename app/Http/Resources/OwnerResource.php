<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Persistence\Models\Owner;

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
