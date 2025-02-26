<?php

namespace Infrastructure\Persistence\Repositories;

use Application\Repositories\TaskRepository;
use Domain\Core\Entity\Comment;
use Domain\Core\Entity\Task;
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
}
