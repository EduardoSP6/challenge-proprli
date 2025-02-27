<?php

namespace Application\Repositories;

use Domain\Core\Entity\User;

interface UserRepository
{
    public function find(string $id): ?User;
}
