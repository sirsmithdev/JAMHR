<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'type',
        'scheduled_at',
        'duration_minutes',
        'location',
        'notes',
        'feedback',
        'status',
        'outcome',
        'rating',
        'interviewers',
        'questions_asked',
        'candidate_questions',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'interviewers' => 'array',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'Scheduled' => 'bg-blue-100 text-blue-800',
            'Completed' => 'bg-emerald-100 text-emerald-800',
            'Cancelled' => 'bg-gray-100 text-gray-800',
            'No Show' => 'bg-red-100 text-red-800',
            'Rescheduled' => 'bg-amber-100 text-amber-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getOutcomeBadgeClassAttribute(): string
    {
        return match($this->outcome) {
            'Pending' => 'bg-gray-100 text-gray-800',
            'Pass' => 'bg-emerald-100 text-emerald-800',
            'Fail' => 'bg-red-100 text-red-800',
            'On Hold' => 'bg-amber-100 text-amber-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getEndTimeAttribute()
    {
        return $this->scheduled_at->addMinutes($this->duration_minutes);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', now())
            ->where('status', 'Scheduled')
            ->orderBy('scheduled_at');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today())
            ->where('status', 'Scheduled');
    }
}
