<?php

namespace Domain\Core\Entity;

use DateTimeImmutable;
use Domain\Core\Enum\UserRole;
use Domain\Shared\ValueObject\Uuid;

final class Owner extends User
{
    public function __construct(Uuid $id, string $name, string $email)
    {
        parent::__construct($id, $name, $email, UserRole::OWNER, new DateTimeImmutable());
    }
}
