<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participant extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'type',
        'preferred_language',
        'custom_fields',
        'status',
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'participant_program')
            ->withTimestamps();
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
