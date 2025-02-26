<?php

namespace Domain\Core\Factory;

use DateTimeImmutable;
use Domain\Core\Entity\Building;
use Domain\Core\Entity\Owner;
use Domain\Shared\ValueObject\Uuid;
use Faker\Factory as Faker;

class BuildingFactory
{
    public static function createOne(Owner $owner): Building
    {
        $faker = Faker::create();

        return new Building(
            id: new Uuid(),
            owner: $owner,
            name: $faker->company,
            address: $faker->address,
            createdAt: new DateTimeImmutable()
        );
    }
}
