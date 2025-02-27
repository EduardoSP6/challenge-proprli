<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Persistence\Models\Comment;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Comment $this */
        return [
            'user' => UserResource::make($this->user),
            'content' => $this->content,
            'created_at' => $this->created_at,
        ];
    }
}
