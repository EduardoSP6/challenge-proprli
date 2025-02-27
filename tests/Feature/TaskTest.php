<?php

namespace Tests\Feature;

use Domain\Core\Enum\TaskStatus;
use Domain\Core\Enum\TeamRole;
use Infrastructure\Persistence\Models\Building;
use Infrastructure\Persistence\Models\Owner;
use Infrastructure\Persistence\Models\Team;
use Infrastructure\Persistence\Models\User;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function test_it_should_list_tasks_filtering_by_status(): void
    {
        $status = TaskStatus::OPENED->value;

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get("/api/tasks?filter[status]=$status");

        $response->assertOk()
            ->assertJsonStructure([
                "data" => [
                    "*" => [
                        'id',
                        'building' => [
                            'id',
                            'name',
                            'address',
                        ],
                        'created_by' => [
                            'name',
                            'email'
                        ],
                        'assigned_to' => [
                            'name',
                            'email',
                        ],
                        'title',
                        'description',
                        'status',
                        'status_description',
                        'created_at',
                        'comments' => [
                            '*' => [
                                'user' => [
                                    'name',
                                    'email',
                                ],
                                'content',
                                'created_at',
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function test_it_should_list_tasks_filtering_by_period(): void
    {
        $startDate = now()->subDay()->toDateString();
        $endDate = now()->toDateString();

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get("/api/tasks?filter[created_at]=$startDate,$endDate");

        $response->assertOk()
            ->assertJsonStructure([
                "data" => [
                    "*" => [
                        'id',
                        'building' => [
                            'id',
                            'name',
                            'address',
                        ],
                        'created_by' => [
                            'name',
                            'email'
                        ],
                        'assigned_to' => [
                            'name',
                            'email',
                        ],
                        'title',
                        'description',
                        'status',
                        'status_description',
                        'created_at',
                        'comments' => [
                            '*' => [
                                'user' => [
                                    'name',
                                    'email',
                                ],
                                'content',
                                'created_at',
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function test_it_should_list_tasks_filtering_by_building(): void
    {
        /** @var Building $building */
        $building = Building::query()->whereHas('tasks')->first();

        $this->assertModelExists($building);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get("/api/tasks?filter[building]=$building->name");

        $response->assertOk()
            ->assertJsonStructure([
                "data" => [
                    "*" => [
                        'id',
                        'building' => [
                            'id',
                            'name',
                            'address',
                        ],
                        'created_by' => [
                            'name',
                            'email'
                        ],
                        'assigned_to' => [
                            'name',
                            'email',
                        ],
                        'title',
                        'description',
                        'status',
                        'status_description',
                        'created_at',
                        'comments' => [
                            '*' => [
                                'user' => [
                                    'name',
                                    'email',
                                ],
                                'content',
                                'created_at',
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function test_it_should_list_tasks_filtering_by_assigned_user(): void
    {
        /** @var User $assignedUser */
        $assignedUser = User::query()->whereHas("tasks")->first();

        $this->assertModelExists($assignedUser);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get("/api/tasks?filter[assigned_user]=$assignedUser->name");

        $response->assertOk()
            ->assertJsonStructure([
                "data" => [
                    "*" => [
                        'id',
                        'building' => [
                            'id',
                            'name',
                            'address',
                        ],
                        'created_by' => [
                            'name',
                            'email'
                        ],
                        'assigned_to' => [
                            'name',
                            'email',
                        ],
                        'title',
                        'description',
                        'status',
                        'status_description',
                        'created_at',
                        'comments' => [
                            '*' => [
                                'user' => [
                                    'name',
                                    'email',
                                ],
                                'content',
                                'created_at',
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function test_it_should_create_a_task_successfully(): void
    {
        /** @var Building $building */
        $building = Building::query()->first();

        $this->assertModelExists($building);

        /** @var User $assignedUser */
        $assignedUser = User::query()->first();

        $this->assertModelExists($assignedUser);

        $data = [
            'building_id' => $building->id,
            'created_by' => $building->owner->id,
            'assigned_to' => $assignedUser->id,
            'title' => "Task title",
            'description' => "Task description",
        ];

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("/api/tasks", $data);

        $response->assertCreated();
    }

    public function test_it_should_fail_to_create_a_task_with_user_who_is_not_the_owner_of_the_building(): void
    {
        /** @var Building $building */
        $building = Building::query()->whereHas('team')->first();

        $this->assertModelExists($building);

        /** @var Owner $owner */
        $owner = Owner::query()->firstWhere('id', '!=', $building->owner_id);

        $this->assertModelExists($owner);
        $this->assertNotEquals($owner->id, $building->owner_id);

        /** @var User $assignedUser */
        $assignedUser = $building->team->members()
            ->firstWhere(['role' => TeamRole::WORKER->value])
            ->user;

        $this->assertModelExists($assignedUser);

        $data = [
            'building_id' => $building->id,
            'created_by' => $owner->id,
            'assigned_to' => $assignedUser->id,
            'title' => "Task title",
            'description' => "Task with invalid owner",
        ];

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("/api/tasks", $data);

        $response->assertUnprocessable();
        $response->assertJson([
            "message" => "You are not the owner of this building"
        ]);
    }

    public function test_it_should_fail_to_create_a_task_with_assigned_user_who_does_not_belong_to_the_team(): void
    {
        /** @var Building $building */
        $building = Building::query()->first();

        $this->assertModelExists($building);

        /** @var Team $randomTeam */
        $randomTeam = Team::query()
            ->where('building_id', '!=', $building->id)
            ->first();

        $this->assertModelExists($randomTeam);

        $assignedUserId = $randomTeam->members()->first()->user_id;

        $this->assertNotNull($assignedUserId);

        $data = [
            'building_id' => $building->id,
            'created_by' => $building->owner->id,
            'assigned_to' => $assignedUserId,
            'title' => "Task title",
            'description' => "Task description",
        ];

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("/api/tasks", $data);

        $response->assertUnprocessable();
        $response->assertJson([
            "message" => "The assigned user does not belong to this team"
        ]);
    }
}
