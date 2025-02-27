<?php

namespace Database\Factories;

use Domain\Core\Enum\TaskStatus;
use Domain\Core\Enum\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Infrastructure\Persistence\Models\Building;
use Infrastructure\Persistence\Models\Owner;
use Infrastructure\Persistence\Models\Task;
use Infrastructure\Persistence\Models\User;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "id" => Str::orderedUuid()->toString(),
            "building_id" => Building::factory(),
            "created_by" => Owner::factory(),
            "assigned_to" => User::factory()->create(['role' => UserRole::TEAM_MEMBER->value]),
            "title" => fake()->title(),
            "description" => fake()->realText(),
            "status" => fake()->randomElement([
                TaskStatus::OPENED->value,
                TaskStatus::IN_PROGRESS->value,
                TaskStatus::COMPLETED->value,
                TaskStatus::REJECTED->value,
            ])
        ];
    }
}
