<?php

namespace Application\UseCases\Tasks\AddComment;

use Application\Exceptions\AddCommentNotPermittedException;
use Application\Repositories\TaskRepository;
use DateTimeImmutable;
use Domain\Core\Entity\Comment;
use Domain\Core\Entity\Task;
use Domain\Core\Entity\User;
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

    /**
     * @throws Exception
     */
    public function execute(AddCommentInputDto $inputDto): void
    {
        try {
            $task = $this->findTask($inputDto->taskId);
            $user = $this->findUser($inputDto->userId);

            throw_if(
                $user->getId()->value() !== $task->getCreatedBy()->getId()->value(),
                new AddCommentNotPermittedException()
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
            throw $e;
        }
    }

    /**
     * Find the task by ID.
     *
     * @param string $id
     * @return Task|null
     */
    private function findTask(string $id): ?Task
    {
        $task = $this->taskRepository->find($id);

        throw_if(!$task, new Exception("Task not found"));

        return $task;
    }

    /**
     * Find the user by ID.
     *
     * @param string $id
     * @return User|null
     */
    private function findUser(string $id): ?User
    {
        $user = UserService::findUserById($id);

        throw_if(!$user, new Exception("User not found"));

        return $user;
    }
}
