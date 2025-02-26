<?php

namespace Tests\Unit;

use DateTimeImmutable;
use Domain\Core\Entity\Team;
use Domain\Core\Enum\TeamRole;
use Domain\Core\Factory\BuildingFactory;
use Domain\Core\Factory\OwnerFactory;
use Domain\Core\Factory\UserFactory;
use Domain\Shared\ValueObject\Uuid;
use Tests\TestCase;

class TeamTest extends TestCase
{
    public function test_it_should_create_a_team_successfully(): void
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

        $member2 = UserFactory::createOne();
        $team->addMember($member2, TeamRole::WORKER);

        $this->assertInstanceOf(Team::class, $team);
        $this->assertCount(3, $team->getMembers());
    }
}
