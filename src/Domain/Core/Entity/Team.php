<?php

namespace Domain\Core\Entity;

use DateTimeImmutable;
use Domain\Core\Enum\TeamRole;
use Domain\Shared\Entity\BaseEntity;
use Domain\Shared\ValueObject\Uuid;

final class Team extends BaseEntity
{
    private readonly Uuid $id;
    private readonly Building $building;
    private array $members;
    private readonly DateTimeImmutable $createdAt;
    private readonly ?DateTimeImmutable $updatedAt;

    /**
     * @param Uuid $id
     * @param Building $building
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable|null $updatedAt
     */
    public function __construct(
        Uuid               $id,
        Building           $building,
        DateTimeImmutable  $createdAt,
        ?DateTimeImmutable $updatedAt = null
    )
    {
        parent::__construct($id, $createdAt, $updatedAt);

        $this->id = $id;
        $this->building = $building;
        $this->members = [];
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return Building
     */
    public function getBuilding(): Building
    {
        return $this->building;
    }

    /**
     * @param User $user
     * @param TeamRole $role
     * @return void
     */
    public function addMember(User $user, TeamRole $role): void
    {
        $this->members[$user->getId()->value()] = new TeamMember(
            new Uuid(),
            $this,
            $user,
            $role,
            new DateTimeImmutable()
        );
    }

    /**
     * @return TeamMember[]
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param User $user
     * @return TeamMember|null
     */
    public function getMemberByUser(User $user): ?TeamMember
    {
        return $this->members[$user->getId()->value()] ?? null;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
