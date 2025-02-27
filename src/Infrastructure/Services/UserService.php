<?php

namespace Infrastructure\Services;

use Domain\Core\Entity\User;
use Infrastructure\Persistence\Repositories\UserEloquentRepository;

class UserService
{
    public static function findUserById(string $id): ?User
    {
        $userRepository = new UserEloquentRepository();

        return $userRepository->find($id);
    }
}
