<?php

namespace Domain\Shared\Entity;

use DateTimeImmutable;
use Domain\Shared\ValueObject\Uuid;

class BaseEntity
{
    private readonly Uuid $id;
    private readonly DateTimeImmutable $createdAt;
    private readonly DateTimeImmutable|null $updatedAt;

    public function __construct(
        Uuid               $id,
        DateTimeImmutable  $createdAt = new DateTimeImmutable(),
        ?DateTimeImmutable $updatedAt = null
    )
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
