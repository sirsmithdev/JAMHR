<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'benefit_plan_id',
        'tier',
        'annual_deductible',
        'annual_max_coverage',
        'copay_doctor',
        'copay_specialist',
        'copay_emergency',
        'coinsurance_percentage',
        'includes_dental',
        'includes_vision',
        'includes_prescription',
        'network_providers',
        'exclusions',
    ];

    protected function casts(): array
    {
        return [
            'annual_deductible' => 'decimal:2',
            'annual_max_coverage' => 'decimal:2',
            'copay_doctor' => 'decimal:2',
            'copay_specialist' => 'decimal:2',
            'copay_emergency' => 'decimal:2',
            'includes_dental' => 'boolean',
            'includes_vision' => 'boolean',
            'includes_prescription' => 'boolean',
            'network_providers' => 'array',
        ];
    }

    public function benefitPlan(): BelongsTo
    {
        return $this->belongsTo(BenefitPlan::class);
    }

    public function getTierBadgeClassAttribute(): string
    {
        return match ($this->tier) {
            'basic' => 'bg-gray-100 text-gray-800',
            'standard' => 'bg-blue-100 text-blue-800',
            'premium' => 'bg-amber-100 text-amber-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getCoverageListAttribute(): array
    {
        $coverage = ['Medical'];
        if ($this->includes_dental) $coverage[] = 'Dental';
        if ($this->includes_vision) $coverage[] = 'Vision';
        if ($this->includes_prescription) $coverage[] = 'Prescription';
        return $coverage;
    }
}
