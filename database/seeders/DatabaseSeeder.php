<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Infrastructure\Persistence\Models\Building;
use Infrastructure\Persistence\Models\Comment;
use Infrastructure\Persistence\Models\Owner;
use Infrastructure\Persistence\Models\Task;
use Infrastructure\Persistence\Models\Team;
use Infrastructure\Persistence\Models\TeamMember;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Creating 5 owners
        Owner::factory()
            ->count(5)
            ->has(Building::factory()->count(2)) // Each owner has 2 buildings
            ->create();

        // Creating 10 teams
        Team::factory()
            ->count(10)
            ->has(TeamMember::factory()->count(3), 'members') // Each team has 3 members
            ->create();

        // Creating 20 tasks
        Task::factory()
            ->count(20)
            ->has(Comment::factory()->count(2)) // Each task has 2 comments
            ->create();
    }
}
