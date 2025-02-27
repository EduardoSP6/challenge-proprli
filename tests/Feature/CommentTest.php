<?php

namespace Tests\Feature;

use Infrastructure\Persistence\Models\Owner;
use Infrastructure\Persistence\Models\Task;
use Infrastructure\Persistence\Models\User;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function test_it_should_create_add_a_task_comment(): void
    {
        /** @var Owner $owner */
        $owner = Owner::query()->whereHas('tasks')->first();

        $this->assertModelExists($owner);

        /** @var Task $task */
        $task = $owner->tasks()->first();

        $data = [
            'user_id' => $owner->id,
            'content' => "I've offended it again! For the Mouse replied rather impatiently",
        ];

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("/api/tasks/$task->id/comments", $data);

        $response->assertCreated();
    }

    public function test_it_should_fail_to_add_a_task_comment_without_the_owner_user(): void
    {
        /** @var User $user */
        $user = User::query()->first();

        /** @var Task $task */
        $task = Task::query()->first();

        $this->assertModelExists($user);
        $this->assertModelExists($task);

        $data = [
            'user_id' => $user->id,
            'content' => "Comment with invalid task owner",
        ];

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("/api/tasks/$task->id/comments", $data);

        $response->assertUnprocessable();
    }
}
