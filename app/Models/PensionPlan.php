<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PensionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'benefit_plan_id',
        'plan_type',
        'employer_match_percentage',
        'employer_match_cap',
        'vesting_years',
        'vesting_schedule',
        'vesting_percentages',
        'minimum_retirement_age',
        'early_retirement_age',
        'investment_options',
    ];

    protected function casts(): array
    {
        return [
            'employer_match_percentage' => 'decimal:2',
            'employer_match_cap' => 'decimal:2',
            'vesting_years' => 'decimal:2',
            'vesting_percentages' => 'array',
        ];
    }

    public function benefitPlan(): BelongsTo
    {
        return $this->belongsTo(BenefitPlan::class);
    }

    public function getPlanTypeLabelAttribute(): string
    {
        return match ($this->plan_type) {
            'defined_benefit' => 'Defined Benefit',
            'defined_contribution' => 'Defined Contribution',
            'hybrid' => 'Hybrid',
            default => ucfirst($this->plan_type),
        };
    }

    public function getVestingScheduleLabelAttribute(): string
    {
        return match ($this->vesting_schedule) {
            'cliff' => 'Cliff Vesting',
            'graded' => 'Graded Vesting',
            default => ucfirst($this->vesting_schedule),
        };
    }

    public function calculateVestingPercentage(int $yearsOfService): float
    {
        if ($this->vesting_schedule === 'cliff') {
            return $yearsOfService >= $this->vesting_years ? 100 : 0;
        }

        if ($this->vesting_percentages) {
            foreach ($this->vesting_percentages as $years => $percentage) {
                if ($yearsOfService >= $years) {
                    return $percentage;
                }
            }
        }

        return min(100, ($yearsOfService / $this->vesting_years) * 100);
    }
}
