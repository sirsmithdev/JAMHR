<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'interest_rate',
        'market_rate',
        'min_amount',
        'max_amount',
        'min_term_months',
        'max_term_months',
        'min_employment_months',
        'requires_guarantor',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'interest_rate' => 'decimal:2',
            'market_rate' => 'decimal:2',
            'min_amount' => 'decimal:2',
            'max_amount' => 'decimal:2',
            'requires_guarantor' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function loans(): HasMany
    {
        return $this->hasMany(StaffLoan::class);
    }

    public function activeLoans(): HasMany
    {
        return $this->hasMany(StaffLoan::class)->where('status', 'active');
    }

    public function getTaxableBenefitRateAttribute(): float
    {
        return max(0, $this->market_rate - $this->interest_rate);
    }

    public function calculateMonthlyPayment(float $principal, int $termMonths): float
    {
        if ($this->interest_rate == 0) {
            return $principal / $termMonths;
        }

        $monthlyRate = $this->interest_rate / 100 / 12;
        return $principal * ($monthlyRate * pow(1 + $monthlyRate, $termMonths)) / (pow(1 + $monthlyRate, $termMonths) - 1);
    }

    public function calculateTotalInterest(float $principal, int $termMonths): float
    {
        $monthlyPayment = $this->calculateMonthlyPayment($principal, $termMonths);
        return ($monthlyPayment * $termMonths) - $principal;
    }

    public function calculateMonthlyTaxableBenefit(float $outstandingBalance): float
    {
        $annualBenefit = $outstandingBalance * ($this->taxable_benefit_rate / 100);
        return $annualBenefit / 12;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
