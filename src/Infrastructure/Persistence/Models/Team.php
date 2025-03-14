<?php

namespace Infrastructure\Persistence\Models;

use Database\Factories\TeamFactory;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $building_id
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable|null $updated_at
 * @property Building $building
 * @property TeamMember[] $members
 */
class Team extends Model
{
    use HasFactory;

    protected $table = "teams";
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        "id",
        "building_id",
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
        return TeamFactory::new();
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, "building_id");
    }

    public function members(): HasMany
    {
        return $this->hasMany(TeamMember::class, "team_id");
    }
}
