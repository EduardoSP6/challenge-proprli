<?php

namespace Application\Repositories;

use Domain\Core\Entity\Comment;
use Domain\Core\Entity\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskRepository
{
    public function paginate(int $page = 1, int $perPage = 15, array $columns = array('*')): LengthAwarePaginator;

    public function create(Task $task): void;

    public function addComment(Comment $comment): void;
}
