<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'institution',
        'program_type',
        'description',
        'max_reimbursement',
        'duration_months',
        'requires_grade_minimum',
        'minimum_grade',
        'is_approved',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'max_reimbursement' => 'decimal:2',
            'requires_grade_minimum' => 'boolean',
            'is_approved' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function educationRequests(): HasMany
    {
        return $this->hasMany(EducationRequest::class);
    }

    public function getProgramTypeBadgeClassAttribute(): string
    {
        return match ($this->program_type) {
            'degree' => 'bg-purple-100 text-purple-800',
            'certificate' => 'bg-blue-100 text-blue-800',
            'diploma' => 'bg-emerald-100 text-emerald-800',
            'professional' => 'bg-amber-100 text-amber-800',
            'workshop' => 'bg-cyan-100 text-cyan-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
