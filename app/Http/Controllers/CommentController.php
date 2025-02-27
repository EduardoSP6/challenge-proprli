<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCommentRequest;
use Application\UseCases\Tasks\AddComment\AddCommentInputDto;
use Application\UseCases\Tasks\AddComment\AddCommentUseCase;
use Exception;
use Illuminate\Http\JsonResponse;
use Infrastructure\Persistence\Models\Task;
use Infrastructure\Persistence\Repositories\TaskEloquentRepository;

class CommentController extends Controller
{
    private TaskEloquentRepository $taskEloquentRepository;

    /**
     * @param TaskEloquentRepository $taskEloquentRepository
     */
    public function __construct(TaskEloquentRepository $taskEloquentRepository)
    {
        $this->taskEloquentRepository = $taskEloquentRepository;
    }

    /**
     * @param AddCommentRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function store(AddCommentRequest $request, Task $task): JsonResponse
    {
        try {
            $inputDto = new AddCommentInputDto(
                taskId: $task->id,
                userId: $request->validated('user_id'),
                content: $request->validated('content')
            );

            (new AddCommentUseCase($this->taskEloquentRepository))->execute($inputDto);

            return response()->json(["message" => "Comment created successfully"], 201);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], 422);
        }
    }
}
