<?php

namespace Tests\Unit;

use Application\UseCases\Tasks\Paginate\ListTasksUseCase;
use Illuminate\Pagination\LengthAwarePaginator;
use Infrastructure\Persistence\Repositories\TaskEloquentRepository;
use Tests\TestCase;

class ListTaskUseCaseTest extends TestCase
{
    protected ListTasksUseCase $listTasksUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->listTasksUseCase = new ListTasksUseCase(new TaskEloquentRepository());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->listTasksUseCase);
    }

    public function test_it_should_list_tasks_successfully(): void
    {
        $paginatedTasks = $this->listTasksUseCase->execute();

        $this->assertInstanceOf(LengthAwarePaginator::class, $paginatedTasks);

        $this->assertFalse($paginatedTasks->isEmpty());
    }
}
