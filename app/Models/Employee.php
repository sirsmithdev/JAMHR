<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    // Pay frequency constants
    const PAY_FREQUENCY_FORTNIGHTLY = 'fortnightly';
    const PAY_FREQUENCY_MONTHLY = 'monthly';

    // Pay type constants
    const PAY_TYPE_SALARIED = 'salaried';
    const PAY_TYPE_HOURLY_FROM_SALARY = 'hourly_from_salary';
    const PAY_TYPE_HOURLY_FIXED = 'hourly_fixed';

    // Standard hours constants
    const STANDARD_HOURS_MONTHLY = 173.33; // 40 hrs/week × 52 weeks / 12 months
    const STANDARD_HOURS_FORTNIGHTLY = 80; // 40 hrs/week × 2 weeks

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'job_title',
        'department',
        'trn_number',
        'nis_number',
        'pin',
        'start_date',
        'salary_annual',
        'hourly_rate',
        'pay_frequency',
        'pay_type',
        'flexi_hourly_rate',
        'standard_hours_per_period',
        'rate_effective_date',
        'vacation_days_total',
        'vacation_days_used',
        'sick_days_total',
        'sick_days_used',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'rate_effective_date' => 'date',
            'salary_annual' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
            'flexi_hourly_rate' => 'decimal:2',
            'standard_hours_per_period' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function appraisals(): HasMany
    {
        return $this->hasMany(Appraisal::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }

    public function getVacationDaysRemainingAttribute(): int
    {
        return $this->vacation_days_total - $this->vacation_days_used;
    }

    public function getSickDaysRemainingAttribute(): int
    {
        return $this->sick_days_total - $this->sick_days_used;
    }

    /**
     * Get monthly salary
     */
    public function getMonthlySalaryAttribute(): float
    {
        return $this->salary_annual ? round($this->salary_annual / 12, 2) : 0;
    }

    /**
     * Get fortnightly salary (for salaried employees)
     */
    public function getFortnightlySalaryAttribute(): float
    {
        // 26 fortnights per year
        return $this->salary_annual ? round($this->salary_annual / 26, 2) : 0;
    }

    /**
     * Get the effective hourly rate based on pay type
     */
    public function getEffectiveHourlyRateAttribute(): float
    {
        return $this->calculateHourlyRate();
    }

    /**
     * Calculate hourly rate based on pay type and frequency
     */
    public function calculateHourlyRate(): float
    {
        switch ($this->pay_type) {
            case self::PAY_TYPE_HOURLY_FIXED:
                // Use the fixed flexi hourly rate
                return (float) ($this->flexi_hourly_rate ?? 0);

            case self::PAY_TYPE_HOURLY_FROM_SALARY:
                // Calculate from monthly salary
                return $this->calculateHourlyRateFromSalary();

            case self::PAY_TYPE_SALARIED:
            default:
                // For salaried, calculate implied hourly rate for reference
                return $this->calculateHourlyRateFromSalary();
        }
    }

    /**
     * Calculate hourly rate from annual salary
     */
    public function calculateHourlyRateFromSalary(): float
    {
        if (!$this->salary_annual) {
            return 0;
        }

        $standardHours = $this->standard_hours_per_period ?? $this->getDefaultStandardHours();

        if ($this->pay_frequency === self::PAY_FREQUENCY_FORTNIGHTLY) {
            // 26 fortnights per year
            $periodicSalary = $this->salary_annual / 26;
        } else {
            // Monthly (12 periods)
            $periodicSalary = $this->salary_annual / 12;
        }

        return $standardHours > 0 ? round($periodicSalary / $standardHours, 2) : 0;
    }

    /**
     * Get default standard hours based on pay frequency
     */
    public function getDefaultStandardHours(): float
    {
        return $this->pay_frequency === self::PAY_FREQUENCY_FORTNIGHTLY
            ? self::STANDARD_HOURS_FORTNIGHTLY
            : self::STANDARD_HOURS_MONTHLY;
    }

    /**
     * Calculate gross pay for a period
     */
    public function calculateGrossPay(?float $hoursWorked = null): float
    {
        switch ($this->pay_type) {
            case self::PAY_TYPE_HOURLY_FIXED:
            case self::PAY_TYPE_HOURLY_FROM_SALARY:
                // Hourly calculation
                $hours = $hoursWorked ?? $this->getDefaultStandardHours();
                return round($this->calculateHourlyRate() * $hours, 2);

            case self::PAY_TYPE_SALARIED:
            default:
                // Fixed salary per period
                return $this->pay_frequency === self::PAY_FREQUENCY_FORTNIGHTLY
                    ? $this->fortnightly_salary
                    : $this->monthly_salary;
        }
    }

    /**
     * Calculate overtime pay
     */
    public function calculateOvertimePay(float $overtimeHours, float $overtimeMultiplier = 1.5): float
    {
        return round($this->calculateHourlyRate() * $overtimeHours * $overtimeMultiplier, 2);
    }

    /**
     * Check if employee is paid hourly
     */
    public function isHourlyPaid(): bool
    {
        return in_array($this->pay_type, [
            self::PAY_TYPE_HOURLY_FIXED,
            self::PAY_TYPE_HOURLY_FROM_SALARY,
        ]);
    }

    /**
     * Check if employee is salaried
     */
    public function isSalaried(): bool
    {
        return $this->pay_type === self::PAY_TYPE_SALARIED;
    }

    /**
     * Get hours worked in a date range from time entries
     */
    public function getHoursWorkedInPeriod($startDate, $endDate): float
    {
        return $this->timeEntries()
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNotNull('total_hours')
            ->sum('total_hours');
    }

    /**
     * Get pay type label for display
     */
    public function getPayTypeLabelAttribute(): string
    {
        return match ($this->pay_type) {
            self::PAY_TYPE_SALARIED => 'Salaried',
            self::PAY_TYPE_HOURLY_FROM_SALARY => 'Hourly (from Salary)',
            self::PAY_TYPE_HOURLY_FIXED => 'Flexi-Hour',
            default => 'Unknown',
        };
    }

    /**
     * Get pay frequency label for display
     */
    public function getPayFrequencyLabelAttribute(): string
    {
        return match ($this->pay_frequency) {
            self::PAY_FREQUENCY_FORTNIGHTLY => 'Fortnightly',
            self::PAY_FREQUENCY_MONTHLY => 'Monthly',
            default => 'Monthly',
        };
    }

    /**
     * Available pay frequencies
     */
    public static function getPayFrequencies(): array
    {
        return [
            self::PAY_FREQUENCY_MONTHLY => 'Monthly',
            self::PAY_FREQUENCY_FORTNIGHTLY => 'Fortnightly',
        ];
    }

    /**
     * Available pay types
     */
    public static function getPayTypes(): array
    {
        return [
            self::PAY_TYPE_SALARIED => 'Salaried (Fixed Monthly/Fortnightly)',
            self::PAY_TYPE_HOURLY_FROM_SALARY => 'Hourly Rate (Calculated from Salary)',
            self::PAY_TYPE_HOURLY_FIXED => 'Flexi-Hour (Fixed Hourly Rate)',
        ];
    }
}
