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
use OpenApi\Annotations as OA;

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
     * @OA\Get(
     *     path="/api/tasks",
     *     operationId="listTasks",
     *     tags={"Tasks"},
     *     summary="Get a paginated list of tasks.",
     *     description="Get a paginated list of tasks.",
     *
     *     @OA\Parameter(
     *          name="filter[assigned_user]",
     *          in="query",
     *          description="Filter by assigned user name",
     *          @OA\Schema(type="string", example="John Doe"),
     *     ),
     *
     *     @OA\Parameter(
     *          name="filter[building]",
     *          in="query",
     *          description="Filter by building name",
     *          @OA\Schema(type="string", example="Burj Khalifa"),
     *     ),
     *
     *     @OA\Parameter(
     *          name="filter[status]",
     *          in="query",
     *          description="Filter by task status",
     *          @OA\Schema(
     *              type="string",
     *              enum={"opened", "in_progress", "completed", "rejected"},
     *              example="opened"
     *          ),
     *     ),
     *
     *     @OA\Parameter(
     *          name="filter[created_at]",
     *          in="query",
     *          description="Filter by period. Send two dates separeted by comma with the format: YYYY-MM-DD",
     *          @OA\Schema(type="string", example="2025-02-01,2025-02-27"),
     *     ),
     *
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/TaskResource")
     *          ),
     *    ),
     * )
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $tasks = (new ListTasksUseCase($this->taskEloquentRepository))->execute();

        return TaskResource::collection($tasks);
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     operationId="createTask",
     *     tags={"Tasks"},
     *     summary="Create a new task.",
     *     description="Create a new task.",
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"building_id", "created_by", "assigned_to", "title"},
     *                  @OA\Property(
     *                      property="building_id",
     *                      type="string",
     *                      format="uuid",
     *                      description="Building ID",
     *                  ),
     *                  @OA\Property(
     *                      property="created_by",
     *                      type="string",
     *                      format="uuid",
     *                      description="Created by user ID (building owner)",
     *                  ),
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      description="Task title",
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="Task description",
     *                      nullable=true,
     *                  ),
     *              ),
     *          ),
     *     ),
     *
     *     @OA\Response(
     *          response="201",
     *          description="Record created successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Task created successfully",
     *              ),
     *          ),
     *     ),
     *
     *     @OA\Response(
     *          response="422",
     *          description="Unprocessable Entity",
     *     ),
     * )
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
     * @OA\Get(
     *     path="/api/tasks/{task}",
     *     operationId="showTask",
     *     tags={"Tasks"},
     *     summary="Show a task.",
     *     description="Show a task.",
     *
     *     @OA\Parameter(
     *          in="path",
     *          name="task",
     *          required=true,
     *          description="Task ID",
     *          @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/TaskResource"
     *          ),
     *     ),
     *
     *     @OA\Response(
     *          response="404",
     *          description="Record not found",
     *     ),
     * )
     * @param Task $task
     * @return TaskResource
     */
    public function show(Task $task): TaskResource
    {
        return TaskResource::make($task);
    }
}
