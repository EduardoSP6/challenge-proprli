<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Infrastructure\Persistence\Models\Comment;
use Infrastructure\Persistence\Models\Task;
use Infrastructure\Persistence\Models\User;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::orderedUuid()->toString(),
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'content' => fake()->realText(),
        ];
    }
}
