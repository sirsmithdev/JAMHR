<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PensionAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'benefit_enrollment_id',
        'account_number',
        'employee_ytd_contributions',
        'employer_ytd_contributions',
        'total_balance',
        'vested_balance',
        'vesting_percentage',
        'vesting_date',
        'investment_allocation',
    ];

    protected function casts(): array
    {
        return [
            'employee_ytd_contributions' => 'decimal:2',
            'employer_ytd_contributions' => 'decimal:2',
            'total_balance' => 'decimal:2',
            'vested_balance' => 'decimal:2',
            'vesting_percentage' => 'decimal:2',
            'vesting_date' => 'date',
            'investment_allocation' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($account) {
            if (empty($account->account_number)) {
                $account->account_number = 'PEN-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(BenefitEnrollment::class, 'benefit_enrollment_id');
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(PensionContribution::class);
    }

    public function beneficiaries(): HasMany
    {
        return $this->hasMany(PensionBeneficiary::class);
    }

    public function getYtdTotalContributionsAttribute(): float
    {
        return $this->employee_ytd_contributions + $this->employer_ytd_contributions;
    }

    public function getIsFullyVestedAttribute(): bool
    {
        return $this->vesting_percentage >= 100;
    }

    public function updateVesting(): void
    {
        $enrollment = $this->enrollment()->with('benefitPlan.pensionPlan')->first();
        if ($enrollment && $enrollment->benefitPlan && $enrollment->benefitPlan->pensionPlan) {
            $yearsOfService = $this->employee->hire_date->diffInYears(now());
            $this->vesting_percentage = $enrollment->benefitPlan->pensionPlan->calculateVestingPercentage($yearsOfService);
            $this->vested_balance = $this->total_balance * ($this->vesting_percentage / 100);
            $this->save();
        }
    }
}
