<?php

namespace Tests\Unit;

use Application\Exceptions\UserDoesNotBelongTeamException;
use Application\Exceptions\UserIsNotBuildingOwnerException;
use Application\UseCases\Tasks\Create\CreateTaskInputDto;
use Application\UseCases\Tasks\Create\CreateTaskUseCase;
use Domain\Core\Enum\TeamRole;
use Exception;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Support\Str;
use Infrastructure\Persistence\Models\Building;
use Infrastructure\Persistence\Models\Owner;
use Infrastructure\Persistence\Models\Team;
use Infrastructure\Persistence\Models\User;
use Infrastructure\Persistence\Repositories\BuildingEloquentRepository;
use Infrastructure\Persistence\Repositories\TaskEloquentRepository;
use Tests\TestCase;

class CreateTaskUseCaseTest extends TestCase
{
    protected Generator $faker;
    protected CreateTaskUseCase $createTaskUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();

        $this->createTaskUseCase = new CreateTaskUseCase(
            new TaskEloquentRepository(),
            new BuildingEloquentRepository()
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->faker, $this->createTaskUseCase);
    }

    /**
     * @throws Exception
     */
    public function test_it_should_create_a_task_successfully(): void
    {
        /** @var Building $building */
        $building = Building::query()->whereHas('team')->first();

        $this->assertModelExists($building);

        /** @var User $assignedUser */
        $assignedUser = $building->team->members()
            ->firstWhere(['role' => TeamRole::WORKER->value])
            ->user;

        $this->assertModelExists($assignedUser);

        $inputDto = new CreateTaskInputDto(
            title: $this->faker->sentence,
            description: $this->faker->paragraph,
            buildingId: $building->id,
            ownerId: $building->owner_id,
            assignedUserId: $assignedUser->id
        );

        $this->createTaskUseCase->execute($inputDto);
    }

    /**
     * @throws Exception
     */
    public function test_it_should_fail_to_create_a_task_with_user_who_is_not_the_owner_of_the_building(): void
    {
        $this->expectException(UserIsNotBuildingOwnerException::class);
        $this->expectExceptionMessage("You are not the owner of this building");

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

        $inputDto = new CreateTaskInputDto(
            title: $this->faker->sentence,
            description: $this->faker->paragraph,
            buildingId: $building->id,
            ownerId: $owner->id,
            assignedUserId: $assignedUser->id
        );

        $this->createTaskUseCase->execute($inputDto);
    }

    /**
     * @throws Exception
     */
    public function test_it_should_fail_to_create_a_task_with_assigned_user_who_does_not_belong_to_the_team(): void
    {
        $this->expectException(UserDoesNotBelongTeamException::class);
        $this->expectExceptionMessage("The assigned user does not belong to this team");

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

        $inputDto = new CreateTaskInputDto(
            title: $this->faker->sentence,
            description: $this->faker->paragraph,
            buildingId: $building->id,
            ownerId: $building->owner_id,
            assignedUserId: $assignedUserId
        );

        $this->createTaskUseCase->execute($inputDto);
    }

    public function test_it_should_fail_to_create_a_task_with_mocked_data(): void
    {
        $this->expectException(Exception::class);

        $buildingId = Str::orderedUuid()->toString();
        $ownerId = Str::orderedUuid()->toString();
        $assignedUserId = Str::orderedUuid()->toString();

        $inputDto = new CreateTaskInputDto(
            title: $this->faker->sentence,
            description: $this->faker->paragraph,
            buildingId: $buildingId,
            ownerId: $ownerId,
            assignedUserId: $assignedUserId
        );

        $this->createTaskUseCase->execute($inputDto);
    }
}
