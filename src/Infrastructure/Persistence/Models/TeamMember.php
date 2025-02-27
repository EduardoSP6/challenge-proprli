<?php

namespace Infrastructure\Persistence\Models;

use Database\Factories\TeamMemberFactory;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $team_id
 * @property string $user_id
 * @property string $role
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable|null $updated_at
 * @property Team $team
 * @property User $user
 */
class TeamMember extends Model
{
    use HasFactory;

    protected $table = "team_members";
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        "id",
        "team_id",
        "user_id",
        "role",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    protected static function newFactory(): Factory
    {
        return TeamMemberFactory::new();
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, "team_id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
