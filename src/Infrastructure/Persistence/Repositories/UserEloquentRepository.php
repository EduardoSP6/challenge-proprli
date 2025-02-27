<?php

namespace Infrastructure\Persistence\Repositories;

use Application\Repositories\UserRepository;
use Domain\Core\Entity\User;
use Domain\Core\Enum\UserRole;
use Domain\Shared\ValueObject\Uuid;
use Infrastructure\Persistence\Models\User as UserModel;

class UserEloquentRepository implements UserRepository
{

    public function find(string $id): ?User
    {
        /** @var UserModel $userModel */
        $userModel = UserModel::query()->firstWhere(['id', '=', $id]);

        if (!$userModel) return null;

        return new User(
            id: new Uuid($userModel->id),
            name: $userModel->name,
            email: $userModel->email,
            role: UserRole::from($userModel->role),
            createdAt: $userModel->created_at,
            updatedAt: $userModel->updated_at
        );
    }
}
