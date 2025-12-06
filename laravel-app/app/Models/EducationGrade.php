<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'education_request_id',
        'course_name',
        'semester',
        'grade',
        'grade_points',
        'passed',
        'completion_date',
        'certificate_path',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'grade_points' => 'decimal:2',
            'passed' => 'boolean',
            'completion_date' => 'date',
        ];
    }

    public function educationRequest(): BelongsTo
    {
        return $this->belongsTo(EducationRequest::class);
    }

    public function getGradeBadgeClassAttribute(): string
    {
        if ($this->passed) {
            return 'bg-emerald-100 text-emerald-800';
        }
        return 'bg-red-100 text-red-800';
    }
}
