<?php

namespace Tests\Unit;

use DateTimeImmutable;
use Domain\Core\Entity\User;
use Domain\Core\Enum\UserRole;
use Domain\Shared\ValueObject\Uuid;
use DomainException;
use Faker\Factory as Faker;
use Faker\Generator;
use Tests\TestCase;

class UserEntityTest extends TestCase
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

    public function test_it_should_fail_to_create_a_user_with_empty_name(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("User name can not be empty");

        new User(
            id: new Uuid(),
            name: "   ",
            email: $this->faker->email,
            role: UserRole::OWNER,
            createdAt: new DateTimeImmutable()
        );
    }

    public function test_it_should_fail_to_create_a_user_with_invalid_email(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Invalid user email");

        new User(
            id: new Uuid(),
            name: $this->faker->name,
            email: "john.doe",
            role: UserRole::OWNER,
            createdAt: new DateTimeImmutable()
        );
    }

    public function test_it_should_create_a_user_successfully(): void
    {
        $user = new User(
            id: new Uuid(),
            name: $this->faker->name,
            email: $this->faker->email,
            role: UserRole::OWNER,
            createdAt: new DateTimeImmutable()
        );

        $this->assertNotNull($user);
        $this->assertInstanceOf(User::class, $user);
    }
}
