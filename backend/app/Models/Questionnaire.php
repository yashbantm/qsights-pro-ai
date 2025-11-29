<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questionnaire extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'program_id',
        'title',
        'description',
        'sections',
        'questions',
        'conditional_logic',
        'is_template',
        'status',
    ];

    protected $casts = [
        'sections' => 'array',
        'questions' => 'array',
        'conditional_logic' => 'array',
        'is_template' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(QuestionnaireTranslation::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
