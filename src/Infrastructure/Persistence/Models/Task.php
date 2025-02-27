<?php

namespace Infrastructure\Persistence\Models;

use Database\Factories\TaskFactory;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $building_id
 * @property string $created_by
 * @property string|null $assigned_to
 * @property string $title
 * @property string|null $description
 * @property string $status
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable|null $updated_at
 * @property Building $building
 * @property Owner $createdBy
 * @property User|null $assignedTo
 * @property Comment[] $comments
 */
class Task extends Model
{
    use HasFactory;

    protected $table = "tasks";
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        "id",
        "building_id",
        "created_by",
        "assigned_to",
        "title",
        "description",
        "status",
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
        return TaskFactory::new();
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, "building_id");
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Owner::class, "created_by");
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, "assigned_to");
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
