<?php

namespace App\Http\Resources;

use Domain\Core\Enum\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Persistence\Models\Task;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Task $this */
        return [
            "id" => $this->id,
            "building" => BuildingResource::make($this->building),
            "created_by" => OwnerResource::make($this->createdBy),
            "assigned_to" => UserResource::make($this->assignedTo),
            "title" => $this->title,
            "description" => $this->description,
            "status" => $this->status,
            "status_description" => TaskStatus::from($this->status)->getDescription(),
            "created_at" => $this->created_at,
            "comments" => count($this->comments) > 0 ? CommentResource::collection($this->comments) : [],
        ];
    }
}
