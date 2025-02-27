<?php

namespace Database\Factories;

use Domain\Core\Enum\TeamRole;
use Domain\Core\Enum\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Infrastructure\Persistence\Models\Team;
use Infrastructure\Persistence\Models\TeamMember;
use Infrastructure\Persistence\Models\User;

/**
 * @extends Factory<TeamMember>
 */
class TeamMemberFactory extends Factory
{
    protected $model = TeamMember::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::orderedUuid()->toString(),
            'team_id' => Team::factory(),
            'user_id' => User::factory()->create(['role' => UserRole::TEAM_MEMBER->value]),
            'role' => $this->faker->randomElement([TeamRole::MANAGER->value, TeamRole::WORKER->value]),
        ];
    }
}
