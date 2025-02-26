<?php

namespace Domain\Core\Factory;

use DateTimeImmutable;
use Domain\Core\Entity\User;
use Domain\Core\Enum\UserRole;
use Domain\Shared\ValueObject\Uuid;
use Faker\Factory as Faker;

class UserFactory
{
    public static function createOne(): User
    {
        $faker = Faker::create();

        return new User(
            id: new Uuid(),
            name: $faker->name,
            email: $faker->email,
            role: UserRole::TEAM_MEMBER,
            createdAt: new DateTimeImmutable()
        );
    }
}
