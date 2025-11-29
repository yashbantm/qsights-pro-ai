<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Program extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'logo',
        'start_date',
        'end_date',
        'multilingual',
        'supported_languages',
        'default_language',
        'theme',
        'auto_expire',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'multilingual' => 'boolean',
        'supported_languages' => 'array',
        'theme' => 'array',
        'auto_expire' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function questionnaires(): HasMany
    {
        return $this->hasMany(Questionnaire::class);
    }

    public function programAccounts(): HasMany
    {
        return $this->hasMany(ProgramAccount::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Participant::class, 'participant_program')
            ->withTimestamps();
    }

    public function isExpired(): bool
    {
        return $this->auto_expire && Carbon::parse($this->end_date)->isPast();
    }

    protected static function boot()
    {
        parent::boot();

        // Cascade delete
        static::deleting(function ($program) {
            if ($program->isForceDeleting()) {
                $program->activities()->each(fn($activity) => $activity->forceDelete());
                $program->questionnaires()->each(fn($q) => $q->forceDelete());
                $program->programAccounts()->each(fn($pa) => $pa->delete());
            }
        });
    }
}
