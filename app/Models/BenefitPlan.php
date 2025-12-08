<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BenefitPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'provider',
        'description',
        'employee_contribution',
        'employer_contribution',
        'contribution_frequency',
        'coverage_amount',
        'coverage_details',
        'effective_date',
        'termination_date',
        'is_active',
        'requires_enrollment',
        'waiting_period_days',
    ];

    protected function casts(): array
    {
        return [
            'employee_contribution' => 'decimal:2',
            'employer_contribution' => 'decimal:2',
            'coverage_amount' => 'decimal:2',
            'coverage_details' => 'array',
            'effective_date' => 'date',
            'termination_date' => 'date',
            'is_active' => 'boolean',
            'requires_enrollment' => 'boolean',
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(BenefitEnrollment::class);
    }

    public function healthPlan(): HasOne
    {
        return $this->hasOne(HealthPlan::class);
    }

    public function pensionPlan(): HasOne
    {
        return $this->hasOne(PensionPlan::class);
    }

    public function activeEnrollments(): HasMany
    {
        return $this->hasMany(BenefitEnrollment::class)->where('status', 'active');
    }

    public function getTotalCostAttribute(): float
    {
        return $this->employee_contribution + $this->employer_contribution;
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'health' => 'bg-red-100 text-red-800',
            'pension' => 'bg-blue-100 text-blue-800',
            'life_insurance' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
