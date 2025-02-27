<?php

namespace Database\Factories;

use Domain\Core\Enum\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Infrastructure\Persistence\Models\Building;
use Infrastructure\Persistence\Models\User;

/**
 * @extends Factory<Building>
 */
class BuildingFactory extends Factory
{
    protected $model = Building::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "id" => Str::orderedUuid()->toString(),
            "name" => fake()->company(),
            "address" => fake()->address(),
            "owner_id" => User::factory()->create([
                "role" => UserRole::OWNER->value,
            ]),
        ];
    }
}
