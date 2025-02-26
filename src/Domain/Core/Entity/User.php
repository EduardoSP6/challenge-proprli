<?php

namespace Domain\Core\Entity;

use DateTimeImmutable;
use Domain\Core\Enum\UserRole;
use Domain\Shared\Entity\BaseEntity;
use Domain\Shared\ValueObject\Uuid;
use DomainException;

class User extends BaseEntity
{
    private readonly Uuid $id;
    private string $name;
    private string $email;
    private readonly UserRole $role;
    private readonly DateTimeImmutable $createdAt;
    private readonly ?DateTimeImmutable $updatedAt;

    /**
     * @param Uuid $id
     * @param string $name
     * @param string $email
     * @param UserRole $role
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable|null $updatedAt
     */
    public function __construct(
        Uuid               $id,
        string             $name,
        string             $email,
        UserRole           $role,
        DateTimeImmutable  $createdAt,
        ?DateTimeImmutable $updatedAt = null
    )
    {
        parent::__construct($id, $createdAt, $updatedAt);

        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;

        $this->validate();
    }

    /**
     * @return void
     */
    protected function validate(): void
    {
        throw_if(
            empty(trim($this->name)),
            new DomainException("User name can not be empty")
        );

        throw_if(
            filter_var($this->email, FILTER_VALIDATE_EMAIL) === false,
            new DomainException("Invalid user email")
        );
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return UserRole
     */
    public function getRole(): UserRole
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
