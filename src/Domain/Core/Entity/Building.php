<?php

namespace Domain\Core\Entity;

use DateTimeImmutable;
use Domain\Shared\Entity\BaseEntity;
use Domain\Shared\ValueObject\Uuid;
use DomainException;

class Building extends BaseEntity
{
    private readonly Uuid $id;
    private Owner $owner;
    private string $name;
    private string $address;
    private readonly DateTimeImmutable $createdAt;
    private readonly ?DateTimeImmutable $updatedAt;

    /**
     * @param Uuid $id
     * @param Owner $owner
     * @param string $name
     * @param string $address
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable|null $updatedAt
     */
    public function __construct(
        Uuid               $id,
        Owner              $owner,
        string             $name,
        string             $address,
        DateTimeImmutable  $createdAt,
        ?DateTimeImmutable $updatedAt = null
    )
    {
        parent::__construct($id, $createdAt, $updatedAt);

        $this->id = $id;
        $this->owner = $owner;
        $this->name = $name;
        $this->address = $address;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;

        $this->validate();
    }

    protected function validate(): void
    {
        throw_if(
            empty(trim($this->name)),
            new DomainException("Building name can not be empty")
        );

        throw_if(
            empty(trim($this->address)),
            new DomainException("Building address can not be empty")
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
     * @return Owner
     */
    public function getOwner(): Owner
    {
        return $this->owner;
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
    public function getAddress(): string
    {
        return $this->address;
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
