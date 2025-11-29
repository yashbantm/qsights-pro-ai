<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Activity extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'program_id',
        'questionnaire_id',
        'activity_type',
        'activity_name',
        'about',
        'survey_url',
        'sender_email',
        'start_date',
        'end_date',
        'manager_name',
        'manager_email',
        'project_code',
        'configuration_price',
        'subscription_price',
        'tax_percentage',
        'randomize_questions_count',
        'questions_per_page',
        'retake_count',
        'logo_main',
        'additional_logos',
        'header_html',
        'footer_html',
        'terms_and_conditions',
        'privacy_policy',
        'disclaimer',
        'additional_services',
        'status',
        'approval_token',
        'approved_at',
        'approved_by',
        'decline_reason',
        'general_link',
        'guest_link',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'configuration_price' => 'decimal:2',
        'subscription_price' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'additional_logos' => 'array',
        'additional_services' => 'array',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function generateApprovalToken(): string
    {
        $this->approval_token = Str::random(64);
        $this->save();
        return $this->approval_token;
    }

    public function generateParticipantLinks(): void
    {
        $this->general_link = url("/participate/general/{$this->id}");
        $this->guest_link = url("/participate/guest/{$this->id}");
        $this->save();
    }

    public function isExpired(): bool
    {
        return Carbon::parse($this->end_date)->isPast();
    }

    public function canGoLive(): bool
    {
        return $this->status === 'approved' && !$this->isExpired();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($activity) {
            if ($activity->isForceDeleting()) {
                $activity->responses()->each(fn($response) => $response->forceDelete());
            }
        });
    }
}
