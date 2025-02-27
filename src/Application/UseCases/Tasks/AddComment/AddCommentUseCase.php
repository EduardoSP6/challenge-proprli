<?php

namespace Application\UseCases\Tasks\AddComment;

use Application\Repositories\TaskRepository;
use DateTimeImmutable;
use Domain\Core\Entity\Comment;
use Domain\Shared\ValueObject\Uuid;
use Exception;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\UserService;

class AddCommentUseCase
{
    private TaskRepository $taskRepository;

    /**
     * @param TaskRepository $taskRepository
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function execute(AddCommentInputDto $inputDto): void
    {
        try {
            $task = $this->taskRepository->find($inputDto->taskId);
            $user = UserService::findUserById($inputDto->userId);

            throw_if(
                $user->getId()->value() !== $task->getCreatedBy()->getId()->value(),
                new Exception("You are not the owner of this task")
            );

            $comment = new Comment(
                id: new Uuid(),
                task: $task,
                user: $user,
                content: $inputDto->content,
                createdAt: new DateTimeImmutable()
            );

            $this->taskRepository->addComment($comment);
        } catch (Exception $e) {
            Log::error("Error to add a comment to a task.", [
                'class' => get_class($this),
                'method' => 'execute',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
