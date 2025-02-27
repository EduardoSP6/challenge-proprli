<?php

namespace Tests\Unit;

use DateTimeImmutable;
use Domain\Core\Entity\Comment;
use Domain\Core\Entity\Task;
use Domain\Core\Entity\Team;
use Domain\Core\Enum\TaskStatus;
use Domain\Core\Enum\TeamRole;
use Domain\Core\Factory\BuildingFactory;
use Domain\Core\Factory\OwnerFactory;
use Domain\Core\Factory\UserFactory;
use Domain\Shared\ValueObject\Uuid;
use DomainException;
use Faker\Factory as Faker;
use Faker\Generator;
use Tests\TestCase;

class TaskEntityTest extends TestCase
{
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->faker);
    }

    public function test_it_should_create_a_task_successfully(): void
    {
        $owner = OwnerFactory::createOne();
        $building = BuildingFactory::createOne($owner);
        $title = $this->faker->title;
        $description = $this->faker->realText;

        $task = new Task(
            id: new Uuid(),
            building: $building,
            createdBy: $owner,
            title: $title,
            description: $description,
            createdAt: new DateTimeImmutable()
        );

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals($title, $task->getTitle());
        $this->assertEquals($description, $task->getDescription());
        $this->assertEquals($owner, $task->getCreatedBy());
        $this->assertEquals($building, $task->getBuilding());
        $this->assertEquals(TaskStatus::OPENED, $task->getStatus());
        $this->assertCount(0, $task->getComments());
    }

    public function test_it_should_be_able_to_add_a_comment_to_task(): void
    {
        $owner = OwnerFactory::createOne();
        $building = BuildingFactory::createOne($owner);
        $title = $this->faker->title;
        $description = $this->faker->realText;

        $task = new Task(
            id: new Uuid(),
            building: $building,
            createdBy: $owner,
            title: $title,
            description: $description,
            createdAt: new DateTimeImmutable()
        );

        $comment = new Comment(
            id: new Uuid(),
            task: $task,
            user: $owner,
            content: $this->faker->realText,
            createdAt: new DateTimeImmutable()
        );

        $task->addComment($comment);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals($title, $task->getTitle());
        $this->assertEquals($description, $task->getDescription());
        $this->assertEquals($owner, $task->getCreatedBy());
        $this->assertEquals($building, $task->getBuilding());
        $this->assertEquals(TaskStatus::OPENED, $task->getStatus());
        $this->assertCount(1, $task->getComments());
    }

    public function test_it_should_be_able_to_assign_a_task_to_an_user(): void
    {
        $owner = OwnerFactory::createOne();
        $building = BuildingFactory::createOne($owner);

        $team = new Team(
            id: new Uuid(),
            building: $building,
            createdAt: new DateTimeImmutable()
        );

        $team->addMember($owner, TeamRole::MANAGER);

        $member1 = UserFactory::createOne();
        $team->addMember($member1, TeamRole::WORKER);

        $task = new Task(
            id: new Uuid(),
            building: $building,
            createdBy: $owner,
            title: $this->faker->title,
            description: $this->faker->realText,
            createdAt: new DateTimeImmutable()
        );

        $task->assignTo($member1, $team);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals($owner, $task->getCreatedBy());
        $this->assertEquals($building, $task->getBuilding());
        $this->assertEquals($member1, $task->getAssignedTo());
        $this->assertEquals(TaskStatus::OPENED, $task->getStatus());
        $this->assertCount(0, $task->getComments());
    }

    public function test_it_should_fail_to_assign_a_task_to_an_user_that_does_not_belong_to_the_same_building(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("The user does not belong to the team in this building.");

        $owner = OwnerFactory::createOne();
        $building = BuildingFactory::createOne($owner);

        $ownerTeam = new Team(
            id: new Uuid(),
            building: $building,
            createdAt: new DateTimeImmutable()
        );

        $ownerTeam->addMember($owner, TeamRole::MANAGER);

        $otherBuilding = BuildingFactory::createOne($owner);
        $otherBuildingTeam = new Team(
            id: new Uuid(),
            building: $otherBuilding,
            createdAt: new DateTimeImmutable()
        );

        $member1 = UserFactory::createOne();
        $otherBuildingTeam->addMember($member1, TeamRole::WORKER);

        $task = new Task(
            id: new Uuid(),
            building: $building,
            createdBy: $owner,
            title: $this->faker->title,
            description: $this->faker->realText,
            createdAt: new DateTimeImmutable()
        );

        $task->assignTo($member1, $otherBuildingTeam);
    }

    public function test_it_should_fail_to_assign_a_task_to_an_user_that_does_not_have_worker_team_role(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("The task can only be assigned to team members with the WORKER role.");

        $owner = OwnerFactory::createOne();
        $building = BuildingFactory::createOne($owner);

        $team = new Team(
            id: new Uuid(),
            building: $building,
            createdAt: new DateTimeImmutable()
        );

        $team->addMember($owner, TeamRole::MANAGER);

        $manager2 = UserFactory::createOne();
        $team->addMember($manager2, TeamRole::MANAGER);

        $task = new Task(
            id: new Uuid(),
            building: $building,
            createdBy: $owner,
            title: $this->faker->title,
            description: $this->faker->realText,
            createdAt: new DateTimeImmutable()
        );

        $task->assignTo($manager2, $team);
    }
}
