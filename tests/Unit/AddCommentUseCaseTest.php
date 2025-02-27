<?php

namespace Tests\Unit;

use Application\Exceptions\AddCommentNotPermittedException;
use Application\UseCases\Tasks\AddComment\AddCommentInputDto;
use Application\UseCases\Tasks\AddComment\AddCommentUseCase;
use Exception;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Support\Str;
use Infrastructure\Persistence\Models\Owner;
use Infrastructure\Persistence\Models\Task;
use Infrastructure\Persistence\Models\User;
use Infrastructure\Persistence\Repositories\TaskEloquentRepository;
use Tests\TestCase;

class AddCommentUseCaseTest extends TestCase
{
    protected Generator $faker;
    protected AddCommentUseCase $addCommentUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();

        $this->addCommentUseCase = new AddCommentUseCase(new TaskEloquentRepository());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->faker, $this->listTasksUseCase);
    }

    /**
     * @throws Exception
     */
    public function test_it_should_add_a_task_comment_successfully()
    {
        /** @var Owner $owner */
        $owner = Owner::query()->whereHas('tasks')->first();

        $this->assertModelExists($owner);

        /** @var Task $task */
        $task = $owner->tasks()->first();

        $inputDto = new AddCommentInputDto(
            taskId: $task->id,
            userId: $owner->id,
            content: $this->faker->text
        );

        $this->addCommentUseCase->execute($inputDto);
    }

    /**
     * @throws Exception
     */
    public function test_it_should_fail_to_add_a_task_comment_without_the_owner_user(): void
    {
        $this->expectException(AddCommentNotPermittedException::class);
        $this->expectExceptionMessage("Only the task creator can add comments");

        /** @var User $user */
        $user = User::query()->first();

        /** @var Task $task */
        $task = Task::query()->first();

        $this->assertModelExists($user);
        $this->assertModelExists($task);

        $inputDto = new AddCommentInputDto(
            taskId: $task->id,
            userId: $user->id,
            content: $this->faker->text
        );

        $this->addCommentUseCase->execute($inputDto);
    }

    public function test_it_should_fail_to_add_a_task_comment_with_invalid_data(): void
    {
        $this->expectException(Exception::class);

        $inputDto = new AddCommentInputDto(
            taskId: Str::orderedUuid()->toString(),
            userId: Str::orderedUuid()->toString(),
            content: $this->faker->text
        );

        $this->addCommentUseCase->execute($inputDto);
    }
}
