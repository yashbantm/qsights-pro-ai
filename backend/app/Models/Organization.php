<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Organization extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'logo',
        'website',
        'email',
        'phone',
        'address',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function groupHeads(): HasMany
    {
        return $this->hasMany(GroupHead::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Cascade delete: Organization -> Programs -> Activities -> Participants -> Accounts
        static::deleting(function ($organization) {
            if ($organization->isForceDeleting()) {
                $organization->programs()->each(function ($program) {
                    $program->forceDelete();
                });
                $organization->groupHeads()->each(function ($groupHead) {
                    $groupHead->forceDelete();
                });
            }
        });
    }
}
