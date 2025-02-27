<?php

namespace App\Http\Resources;

use Domain\Core\Enum\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Persistence\Models\Task;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TaskResource",
 *     type="object",
 *
 *     @OA\Property(
 *          property="id",
 *          type="string",
 *          format="uuid",
 *          description="Task ID",
 *     ),
 *
 *     @OA\Property(
 *          property="building",
 *          type="object",
 *          ref="#/components/schemas/BuildingResource",
 *          description="Task related building",
 *     ),
 *
 *     @OA\Property(
 *          property="created_by",
 *          type="object",
 *          ref="#/components/schemas/OwnerResource",
 *          description="Building owner",
 *     ),
 *
 *     @OA\Property(
 *          property="assigned_to",
 *          type="object",
 *          ref="#/components/schemas/UserResource",
 *          description="Assigned user",
 *     ),
 *
 *     @OA\Property(
 *          property="title",
 *          type="string",
 *          description="Task title",
 *     ),
 *
 *     @OA\Property(
 *          property="description",
 *          type="string",
 *          description="Task description",
 *     ),
 *
 *     @OA\Property(
 *          property="status",
 *          type="string",
 *          description="Task status",
 *          enum={
 *              "opened",
 *              "in_progress",
 *              "completed",
 *              "rejected",
 *          },
 *     ),
 *
 *     @OA\Property(
 *          property="status_description",
 *          type="string",
 *          description="Status readable name",
 *          enum={
 *              "Opened",
 *              "In Progress",
 *              "Completed",
 *              "Rejected",
 *          },
 *     ),
 *
 *     @OA\Property(
 *          property="created_at",
 *          type="string",
 *          description="Task creation datetime",
 *     ),
 *
 *     @OA\Property(
 *          property="comments",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/CommentResource"),
 *          description="Task comments",
 *     ),
 * )
 */
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
