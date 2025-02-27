<?php

namespace Infrastructure\Persistence\Repositories;

use Application\Repositories\TaskRepository;
use Domain\Core\Entity\Building;
use Domain\Core\Entity\Comment;
use Domain\Core\Entity\Owner;
use Domain\Core\Entity\Task;
use Domain\Core\Entity\User;
use Domain\Core\Enum\TaskStatus;
use Domain\Core\Enum\UserRole;
use Domain\Shared\ValueObject\Uuid;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Infrastructure\Persistence\Models\Task as TaskModel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskEloquentRepository implements TaskRepository
{

    public function paginate(int $page = 1, int $perPage = 15, array $columns = array('*')): LengthAwarePaginator
    {
        return QueryBuilder::for(TaskModel::class)
            ->with([
                'comments',
                'building',
                'createdBy',
                'assignedTo',
            ])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::partial('assigned_user', 'assignedTo.name'),
                AllowedFilter::partial('building', 'building.name'),
                AllowedFilter::callback('created_at', function (Builder $query, array $value) {
                    $query->whereDate('created_at', '>=', $value[0])
                        ->whereDate('created_at', '<=', $value[1]);
                }),
            ])
            ->allowedSorts(['created_at', 'status'])
            ->defaultSort('-created_at')
            ->allowedIncludes(['building', 'assignedTo'])
            ->paginate($perPage, $columns, 'page', $page);
    }

    public function create(Task $task): void
    {
        try {
            DB::beginTransaction();

            /** @var TaskModel $newTask */
            $newTask = TaskModel::query()->create([
                "id" => $task->getId()->value(),
                "building_id" => $task->getBuilding()->getId()->value(),
                "created_by" => $task->getCreatedBy()->getId()->value(),
                "assigned_to" => $task->getAssignedTo()->getId()->value(),
                "title" => $task->getTitle(),
                "description" => $task->getDescription(),
                "status" => $task->getStatus()->value,
            ]);

            if (count($task->getComments()) > 0) {
                $comments = [];
                foreach ($task->getComments() as $comment) {
                    $comments[] = [
                        'id' => $comment->getId()->value(),
                        'task_id' => $newTask->id,
                        'user_id' => $comment->getUser()->getId()->value(),
                        'content' => $comment->getContent(),
                    ];
                }

                $newTask->comments()->create($comments);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error to persist team", [
                'class' => get_class($this),
                'method' => 'create',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function addComment(Comment $comment): void
    {
        /** @var TaskModel $task */
        $task = TaskModel::query()
            ->where('id', '=', $comment->getTask()->getId()->value())
            ->firstOrFail();

        $task->comments()->create([
            'id' => $comment->getId()->value(),
            'task_id' => $task->id,
            'user_id' => $comment->getUser()->getId()->value(),
            'content' => $comment->getContent(),
        ]);
    }

    public function find(string $id): ?Task
    {
        /** @var TaskModel $taskModel */
        $taskModel = TaskModel::query()
            ->firstWhere('id', '=', $id);

        if (!$taskModel) return null;

        $owner = new Owner(
            id: new Uuid($taskModel->createdBy->id),
            name: $taskModel->createdBy->name,
            email: $taskModel->createdBy->email,
        );

        $building = new Building(
            id: new Uuid($taskModel->building->id),
            owner: $owner,
            name: $taskModel->building->name,
            address: $taskModel->building->address,
            createdAt: $taskModel->building->created_at,
            updatedAt: $taskModel->building->updated_at
        );

        $assignedUser = null;
        if ($taskModel->assignedTo) {
            $assignedUser = new User(
                id: new Uuid($taskModel->assignedTo->id),
                name: $taskModel->assignedTo->name,
                email: $taskModel->assignedTo->email,
                role: UserRole::from($taskModel->assignedTo->role),
                createdAt: $taskModel->assignedTo->created_at,
                updatedAt: $taskModel->assignedTo->updated_at
            );
        }

        $task = new Task(
            id: new Uuid($taskModel->id),
            building: $building,
            createdBy: $owner,
            title: $taskModel->title,
            description: $taskModel->description,
            createdAt: $taskModel->created_at,
            updatedAt: $taskModel->updated_at,
            assignedTo: $assignedUser,
            status: TaskStatus::from($taskModel->status)
        );

        if (count($taskModel->comments) > 0) {
            foreach ($taskModel->comments as $comment) {
                $commentUser = new User(
                    id: new Uuid($comment->user->id),
                    name: $comment->user->name,
                    email: $comment->user->email,
                    role: UserRole::from($comment->user->role),
                    createdAt: $comment->user->created_at,
                    updatedAt: $comment->user->updated_at,
                );

                $taskComment = new Comment(
                    id: new Uuid($comment->id),
                    task: $task,
                    user: $commentUser,
                    content: $comment->content,
                    createdAt: $comment->created_at,
                    updatedAt: $comment->updated_at
                );

                $task->addComment($taskComment);
            }
        }

        return $task;
    }
}
