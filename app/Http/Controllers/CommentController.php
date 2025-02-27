<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCommentRequest;
use Application\Exceptions\AddCommentNotPermittedException;
use Application\UseCases\Tasks\AddComment\AddCommentInputDto;
use Application\UseCases\Tasks\AddComment\AddCommentUseCase;
use Exception;
use Illuminate\Http\JsonResponse;
use Infrastructure\Persistence\Models\Task;
use Infrastructure\Persistence\Repositories\TaskEloquentRepository;
use OpenApi\Annotations as OA;

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
     * @OA\Post(
     *     path="/api/tasks/{task}/comments",
     *     operationId="addTaskComment",
     *     tags={"TaskComments"},
     *     summary="Create a task comment.",
     *     description="Create a new task comment.",
     *
     *     @OA\Parameter(
     *          in="path",
     *          name="task",
     *          required=true,
     *          description="Task ID",
     *          @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"content", "user_id"},
     *                  @OA\Property(
     *                      property="content",
     *                      type="string",
     *                      description="Content",
     *                      maxLength=400,
     *                  ),
     *                  @OA\Property(
     *                      property="user_id",
     *                      type="string",
     *                      format="uuid",
     *                      description="User ID",
     *                  ),
     *              ),
     *          ),
     *     ),
     *
     *     @OA\Response(
     *          response="201",
     *          description="Comment created successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Comment created successfully",
     *              ),
     *          ),
     *     ),
     *
     *     @OA\Response(
     *          response="403",
     *          description="Forbidden",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Only the task creator can add comments",
     *              ),
     *          ),
     *     ),
     *
     *     @OA\Response(
     *          response="422",
     *          description="Unprocessable Entity",
     *     ),
     *
     *     @OA\Response(
     *          response="401",
     *          description="Task not found",
     *     ),
     *
     *     @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Unable to complete the operation. Please try again later",
     *              ),
     *          ),
     *     ),
     * )
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
        } catch (AddCommentNotPermittedException $e) {
            return response()->json(["message" => $e->getMessage()], 403);
        } catch (Exception) {
            return response()->json([
                "message" => "Unable to complete the operation. Please try again later"
            ], 500);
        }
    }
}
