<?php

namespace Domain\Core\Factory;

use Domain\Core\Entity\Owner;
use Domain\Shared\ValueObject\Uuid;
use Faker\Factory as Faker;

class OwnerFactory
{
    public static function createOne(): Owner
    {
        $faker = Faker::create();

        return new Owner(
            id: new Uuid(),
            name: $faker->name,
            email: $faker->email
        );
    }
}
