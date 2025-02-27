<?php

namespace Tests\Unit;

use Domain\Core\Entity\Owner;
use Domain\Shared\ValueObject\Uuid;
use DomainException;
use Faker\Factory as Faker;
use Faker\Generator;
use Tests\TestCase;

class OwnerEntityTest extends TestCase
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

    public function test_it_should_fail_to_create_a_owner_with_empty_name(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("User name can not be empty");

        new Owner(
            id: new Uuid(),
            name: "   ",
            email: $this->faker->email,
        );
    }

    public function test_it_should_fail_to_create_a_owner_with_invalid_email(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Invalid user email");

        new Owner(
            id: new Uuid(),
            name: $this->faker->name,
            email: "john.doe",
        );
    }

    public function test_it_should_create_a_owner_successfully(): void
    {
        $owner = new Owner(
            id: new Uuid(),
            name: $this->faker->name,
            email: $this->faker->email,
        );

        $this->assertNotNull($owner);
        $this->assertInstanceOf(Owner::class, $owner);
    }
}
