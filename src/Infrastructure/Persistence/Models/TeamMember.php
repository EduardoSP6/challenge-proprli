<?php

namespace Infrastructure\Persistence\Models;

use DateTimeImmutable;
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

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, "team_id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "team_id");
    }
}
