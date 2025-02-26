<?php

namespace Infrastructure\Persistence\Models;

use DateTimeImmutable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable|null $updated_at
 * @property Building[] $buildings
 * @property Task[] $tasks
 */
class Owner extends User
{
    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, "created_by");
    }
}
