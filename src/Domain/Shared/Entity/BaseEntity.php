<?php

namespace Domain\Shared\Entity;

use DateTimeImmutable;
use Domain\Shared\ValueObject\Uuid;

class BaseEntity
{
    private Uuid $uuid;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable|null $updatedAt;

    public function __construct(Uuid $uuid, DateTimeImmutable $createdAt, ?DateTimeImmutable $updatedAt = null)
    {
        $this->uuid = $uuid;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
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
