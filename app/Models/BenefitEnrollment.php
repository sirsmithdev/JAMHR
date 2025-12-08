<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BenefitEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'benefit_plan_id',
        'enrollment_period_id',
        'status',
        'enrollment_date',
        'effective_date',
        'termination_date',
        'employee_contribution',
        'employer_contribution',
        'coverage_level',
        'notes',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'enrollment_date' => 'date',
            'effective_date' => 'date',
            'termination_date' => 'date',
            'employee_contribution' => 'decimal:2',
            'employer_contribution' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function benefitPlan(): BelongsTo
    {
        return $this->belongsTo(BenefitPlan::class);
    }

    public function enrollmentPeriod(): BelongsTo
    {
        return $this->belongsTo(EnrollmentPeriod::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function dependents(): HasMany
    {
        return $this->hasMany(BenefitDependent::class);
    }

    public function healthClaims(): HasMany
    {
        return $this->hasMany(HealthClaim::class);
    }

    public function pensionAccount(): HasOne
    {
        return $this->hasOne(PensionAccount::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'active' => 'bg-emerald-100 text-emerald-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'terminated' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getCoverageLevelLabelAttribute(): string
    {
        return match ($this->coverage_level) {
            'employee_only' => 'Employee Only',
            'employee_spouse' => 'Employee + Spouse',
            'employee_children' => 'Employee + Children',
            'family' => 'Family',
            default => $this->coverage_level ?? 'N/A',
        };
    }

    public function getTotalContributionAttribute(): float
    {
        return $this->employee_contribution + $this->employer_contribution;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
