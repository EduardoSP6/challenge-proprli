<?php

namespace Infrastructure\Persistence\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable|null $updated_at
 * @property TeamMember[] $teamMemberships
 * @property Task[] $tasks
 * @property Comment[] $comments
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "users";
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(TeamMember::class, "user_id");
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, "assigned_to");
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
