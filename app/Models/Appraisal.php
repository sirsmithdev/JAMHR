<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appraisal extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'reviewer_id',
        'cycle',
        'score_competency',
        'score_goals',
        'rating_overall',
        'goals_met_percentage',
        'manager_comments',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'score_competency' => 'array',
            'score_goals' => 'array',
            'rating_overall' => 'decimal:1',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'bg-emerald-100 text-emerald-800',
            'needs_review' => 'bg-amber-100 text-amber-800',
            'draft' => 'bg-slate-100 text-slate-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'Completed',
            'needs_review' => 'Needs Review',
            'draft' => 'Draft',
            default => ucfirst($this->status),
        };
    }
}
