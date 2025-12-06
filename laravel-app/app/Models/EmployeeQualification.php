<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeQualification extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'education_request_id',
        'qualification_type',
        'title',
        'institution',
        'field_of_study',
        'start_date',
        'completion_date',
        'expiry_date',
        'grade',
        'document_path',
        'company_sponsored',
        'verified',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'completion_date' => 'date',
            'expiry_date' => 'date',
            'company_sponsored' => 'boolean',
            'verified' => 'boolean',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function educationRequest(): BelongsTo
    {
        return $this->belongsTo(EducationRequest::class);
    }

    public function getQualificationTypeLabelAttribute(): string
    {
        return match ($this->qualification_type) {
            'degree' => 'Degree',
            'certificate' => 'Certificate',
            'diploma' => 'Diploma',
            'license' => 'License',
            'certification' => 'Professional Certification',
            default => ucfirst($this->qualification_type),
        };
    }

    public function getQualificationTypeBadgeClassAttribute(): string
    {
        return match ($this->qualification_type) {
            'degree' => 'bg-purple-100 text-purple-800',
            'certificate' => 'bg-blue-100 text-blue-800',
            'diploma' => 'bg-emerald-100 text-emerald-800',
            'license' => 'bg-amber-100 text-amber-800',
            'certification' => 'bg-cyan-100 text-cyan-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    public function getIsExpiringAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date->between(now(), now()->addMonths(3));
    }

    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    public function scopeExpiring($query)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '>', now())
            ->where('expiry_date', '<', now()->addMonths(3));
    }
}
