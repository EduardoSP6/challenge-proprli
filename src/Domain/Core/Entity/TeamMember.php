<?php

namespace Domain\Core\Entity;

use DateTimeImmutable;
use Domain\Core\Enum\TeamRole;
use Domain\Shared\Entity\BaseEntity;
use Domain\Shared\ValueObject\Uuid;

final class TeamMember extends BaseEntity
{
    private readonly Uuid $id;
    private readonly Team $team;
    private readonly User $user;
    private readonly TeamRole $role;
    private readonly DateTimeImmutable $createdAt;
    private readonly ?DateTimeImmutable $updatedAt;

    /**
     * @param Uuid $id
     * @param Team $team
     * @param User $user
     * @param TeamRole $role
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable|null $updatedAt
     */
    public function __construct(
        Uuid               $id,
        Team               $team,
        User               $user,
        TeamRole           $role,
        DateTimeImmutable  $createdAt,
        ?DateTimeImmutable $updatedAt = null
    )
    {
        parent::__construct($id, $createdAt, $updatedAt);

        $this->id = $id;
        $this->team = $team;
        $this->user = $user;
        $this->role = $role;
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
     * @return Team
     */
    public function getTeam(): Team
    {
        return $this->team;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return TeamRole
     */
    public function getRole(): TeamRole
    {
        return $this->role;
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
