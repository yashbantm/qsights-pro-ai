<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionnaireTranslation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'questionnaire_id',
        'language_code',
        'title',
        'description',
        'questions',
    ];

    protected $casts = [
        'questions' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }
}
