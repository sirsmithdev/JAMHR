<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'default_days_per_year',
        'is_paid',
        'requires_approval',
        'requires_documentation',
        'accrual_method',
        'accrual_rate',
        'can_carry_over',
        'max_carry_over_days',
        'carry_over_expiry_months',
        'min_service_days',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_paid' => 'boolean',
            'requires_approval' => 'boolean',
            'requires_documentation' => 'boolean',
            'accrual_rate' => 'decimal:4',
            'can_carry_over' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function getAccrualMethodLabelAttribute(): string
    {
        return match ($this->accrual_method) {
            'annual' => 'Annual',
            'monthly' => 'Monthly',
            'per_period' => 'Per Pay Period',
            'tenure_based' => 'Tenure Based',
            default => ucfirst($this->accrual_method),
        };
    }

    /**
     * Calculate entitled days based on employee tenure (Jamaica rules)
     */
    public function calculateEntitledDays(Employee $employee): float
    {
        $yearsOfService = $employee->hire_date->diffInYears(now());
        $daysWorked = $employee->hire_date->diffInDays(now());

        // Jamaica vacation rules:
        // - Less than 1 year: 1 day per 22 days worked (max 10 days)
        // - 1+ years: 10 days
        // - 10+ years: 15 days
        if ($this->code === 'VACATION') {
            if ($yearsOfService < 1) {
                return min(10, floor($daysWorked / 22));
            } elseif ($yearsOfService >= 10) {
                return 15;
            }
            return 10;
        }

        // Jamaica sick leave rules:
        // - After 110 days: 1 day per 22 days worked
        // - After 1 year: 10 days
        if ($this->code === 'SICK') {
            if ($daysWorked < 110) {
                return 0;
            } elseif ($yearsOfService < 1) {
                return floor(($daysWorked - 110) / 22);
            }
            return 10;
        }

        // Maternity leave: 12 weeks = 60 working days (8 weeks paid)
        if ($this->code === 'MATERNITY') {
            return $yearsOfService >= 1 ? 60 : 0;
        }

        // Paternity leave: 20 working days
        if ($this->code === 'PATERNITY') {
            return 20;
        }

        return $this->default_days_per_year;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
