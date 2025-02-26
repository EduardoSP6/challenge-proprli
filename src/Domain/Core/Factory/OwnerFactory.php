<?php

namespace Domain\Core\Factory;

use Domain\Core\Entity\Owner;
use Faker\Factory as Faker;

class OwnerFactory
{
    public static function createOne(): Owner
    {
        $faker = Faker::create();

        return new Owner(
            name: $faker->name,
            email: $faker->email
        );
    }
}
