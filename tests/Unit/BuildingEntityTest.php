<?php

namespace Tests\Unit;

use DateTimeImmutable;
use Domain\Core\Entity\Building;
use Domain\Core\Entity\Owner;
use Domain\Core\Factory\OwnerFactory;
use Domain\Shared\ValueObject\Uuid;
use DomainException;
use Faker\Factory as Faker;
use Faker\Generator;
use Tests\TestCase;

class BuildingEntityTest extends TestCase
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

    public function test_it_should_fail_to_create_a_building_with_empty_name(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Building name can not be empty");

        $owner = OwnerFactory::createOne();

        new Building(
            id: new Uuid(),
            owner: $owner,
            name: "   ",
            address: $this->faker->address,
            createdAt: new DateTimeImmutable()
        );
    }

    public function test_it_should_fail_to_create_a_building_with_empty_address(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Building address can not be empty");

        $owner = OwnerFactory::createOne();

        new Building(
            id: new Uuid(),
            owner: $owner,
            name: $this->faker->company,
            address: "   ",
            createdAt: new DateTimeImmutable()
        );
    }

    public function test_it_should_create_a_building_successfully(): void
    {
        $owner = OwnerFactory::createOne();

        $building = new Building(
            id: new Uuid(),
            owner: $owner,
            name: $this->faker->company,
            address: $this->faker->address,
            createdAt: new DateTimeImmutable()
        );

        $this->assertInstanceOf(Owner::class, $owner);
        $this->assertInstanceOf(Building::class, $building);
    }
}
