<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Resources\TaskResource;
use Application\UseCases\Tasks\Create\CreateTaskInputDto;
use Application\UseCases\Tasks\Create\CreateTaskUseCase;
use Application\UseCases\Tasks\Paginate\ListTasksUseCase;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Infrastructure\Persistence\Models\Task;
use Infrastructure\Persistence\Repositories\BuildingEloquentRepository;
use Infrastructure\Persistence\Repositories\TaskEloquentRepository;

class TaskController extends Controller
{
    private TaskEloquentRepository $taskEloquentRepository;
    private BuildingEloquentRepository $buildingEloquentRepository;

    /**
     * @param TaskEloquentRepository $taskEloquentRepository
     * @param BuildingEloquentRepository $buildingEloquentRepository
     */
    public function __construct(
        TaskEloquentRepository     $taskEloquentRepository,
        BuildingEloquentRepository $buildingEloquentRepository
    )
    {
        $this->taskEloquentRepository = $taskEloquentRepository;
        $this->buildingEloquentRepository = $buildingEloquentRepository;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $tasks = (new ListTasksUseCase($this->taskEloquentRepository))->execute();

        return TaskResource::collection($tasks);
    }

    /**
     * @param CreateTaskRequest $request
     * @return JsonResponse
     */
    public function store(CreateTaskRequest $request): JsonResponse
    {
        try {
            $inputDto = new CreateTaskInputDto(
                title: $request->validated('title'),
                description: $request->validated('description'),
                buildingId: $request->validated('building_id'),
                ownerId: $request->validated('created_by'),
                assignedUserId: $request->validated('assigned_to'),
            );

            (new CreateTaskUseCase($this->taskEloquentRepository, $this->buildingEloquentRepository))
                ->execute($inputDto);

            return response()->json(["message" => "Task created successfully"], 201);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], 422);
        }
    }

    /**
     * @param Task $task
     * @return TaskResource
     */
    public function show(Task $task): TaskResource
    {
        return TaskResource::make($task);
    }
}
