<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Persistence\Models\Comment;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CommentResource",
 *     type="object",
 *
 *     @OA\Property(
 *          property="user",
 *          type="object",
 *          ref="#/components/schemas/UserResource",
 *          description="User that created the comment",
 *     ),
 *
 *     @OA\Property(
 *          property="content",
 *          type="string",
 *          description="Comment content",
 *     ),
 *
 *     @OA\Property(
 *          property="created_at",
 *          type="string",
 *          description="Comment creation datetime"
 *     ),
 * )
 */
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
