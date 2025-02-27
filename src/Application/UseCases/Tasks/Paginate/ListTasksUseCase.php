<?php

namespace Application\UseCases\Tasks\Paginate;

use Application\Repositories\TaskRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListTasksUseCase
{
    private TaskRepository $taskRepository;

    /**
     * @param TaskRepository $taskRepository
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function execute(): LengthAwarePaginator
    {
        return $this->taskRepository->paginate();
    }
}
