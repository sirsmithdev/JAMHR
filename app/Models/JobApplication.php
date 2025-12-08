<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_posting_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'resume_path',
        'cover_letter_path',
        'cover_letter_text',
        'expected_salary',
        'available_start_date',
        'experience_summary',
        'education',
        'skills',
        'references',
        'status',
        'notes',
        'rating',
        'source',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'available_start_date' => 'date',
        'reviewed_at' => 'datetime',
        'expected_salary' => 'decimal:2',
    ];

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'New' => 'bg-blue-100 text-blue-800',
            'Reviewing' => 'bg-purple-100 text-purple-800',
            'Phone Screen' => 'bg-indigo-100 text-indigo-800',
            'Interview Scheduled' => 'bg-amber-100 text-amber-800',
            'Interviewed' => 'bg-cyan-100 text-cyan-800',
            'Under Consideration' => 'bg-yellow-100 text-yellow-800',
            'Offer Extended' => 'bg-emerald-100 text-emerald-800',
            'Offer Accepted' => 'bg-green-100 text-green-800',
            'Offer Declined' => 'bg-orange-100 text-orange-800',
            'Hired' => 'bg-green-200 text-green-900',
            'Rejected' => 'bg-red-100 text-red-800',
            'Withdrawn' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getNextInterviewAttribute()
    {
        return $this->interviews()
            ->where('status', 'Scheduled')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at')
            ->first();
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['Hired', 'Rejected', 'Withdrawn']);
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'New');
    }
}
