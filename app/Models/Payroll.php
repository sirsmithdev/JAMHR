<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'period_start',
        'period_end',
        'pay_frequency',
        'pay_type',
        'hours_worked',
        'hourly_rate_used',
        'regular_hours',
        'regular_pay',
        'gross_pay',
        'basic_salary',
        'overtime_pay',
        'overtime_hours',
        'allowances',
        'bonus',
        'commission',
        'other_earnings',
        'nht_employee',
        'nht_employer',
        'nis_employee',
        'nis_employer',
        'ed_tax_employee',
        'ed_tax_employer',
        'heart_employer',
        'income_tax',
        'loan_deduction',
        'other_deductions',
        'calculation_breakdown',
        'net_pay',
        'status',
        'pay_date',
        'payslip_sent',
        'payslip_sent_at',
        'payslip_generated',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'pay_date' => 'date',
            'hours_worked' => 'decimal:2',
            'hourly_rate_used' => 'decimal:2',
            'regular_hours' => 'decimal:2',
            'regular_pay' => 'decimal:2',
            'gross_pay' => 'decimal:2',
            'basic_salary' => 'decimal:2',
            'overtime_pay' => 'decimal:2',
            'overtime_hours' => 'decimal:2',
            'allowances' => 'decimal:2',
            'bonus' => 'decimal:2',
            'commission' => 'decimal:2',
            'other_earnings' => 'decimal:2',
            'nht_employee' => 'decimal:2',
            'nht_employer' => 'decimal:2',
            'nis_employee' => 'decimal:2',
            'nis_employer' => 'decimal:2',
            'ed_tax_employee' => 'decimal:2',
            'ed_tax_employer' => 'decimal:2',
            'heart_employer' => 'decimal:2',
            'income_tax' => 'decimal:2',
            'loan_deduction' => 'decimal:2',
            'other_deductions' => 'decimal:2',
            'calculation_breakdown' => 'array',
            'net_pay' => 'decimal:2',
            'payslip_sent' => 'boolean',
            'payslip_sent_at' => 'datetime',
            'payslip_generated' => 'boolean',
        ];
    }

    // Accessor aliases for payslip templates
    public function getPayPeriodStartAttribute()
    {
        return $this->period_start;
    }

    public function getPayPeriodEndAttribute()
    {
        return $this->period_end;
    }

    public function getNhtAttribute()
    {
        return $this->nht_employee ?? 0;
    }

    public function getNisAttribute()
    {
        return $this->nis_employee ?? 0;
    }

    public function getEducationTaxAttribute()
    {
        return $this->ed_tax_employee ?? 0;
    }

    public function getPayeAttribute()
    {
        return $this->income_tax ?? 0;
    }

    public function getHeartAttribute()
    {
        return $this->heart_employer ?? 0;
    }

    public function getEmployerNhtAttribute()
    {
        return $this->nht_employer ?? 0;
    }

    public function getEmployerNisAttribute()
    {
        return $this->nis_employer ?? 0;
    }

    public function getBasicSalaryAttribute($value)
    {
        return $value ?? $this->gross_pay;
    }

    public function getTotalDeductionsAttribute()
    {
        return $this->total_employee_deductions + ($this->loan_deduction ?? 0) + ($this->other_deductions ?? 0);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getTotalEmployeeDeductionsAttribute(): float
    {
        return ($this->nht_employee ?? 0) + ($this->nis_employee ?? 0) + ($this->ed_tax_employee ?? 0) + ($this->income_tax ?? 0);
    }

    public function getTotalEmployerContributionsAttribute(): float
    {
        return ($this->nht_employer ?? 0) + ($this->nis_employer ?? 0) + ($this->ed_tax_employer ?? 0) + ($this->heart_employer ?? 0);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'finalized' => 'bg-blue-100 text-blue-800',
            'paid' => 'bg-emerald-100 text-emerald-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get pay frequency label
     */
    public function getPayFrequencyLabelAttribute(): string
    {
        return match ($this->pay_frequency) {
            'fortnightly' => 'Fortnightly',
            'monthly' => 'Monthly',
            default => 'Monthly',
        };
    }

    /**
     * Get pay type label
     */
    public function getPayTypeLabelAttribute(): string
    {
        return match ($this->pay_type) {
            'salaried' => 'Salaried',
            'hourly_from_salary' => 'Hourly (from Salary)',
            'hourly_fixed' => 'Flexi-Hour',
            default => 'Salaried',
        };
    }

    /**
     * Check if this payroll was calculated hourly
     */
    public function isHourlyPayroll(): bool
    {
        return in_array($this->pay_type, ['hourly_from_salary', 'hourly_fixed']);
    }

    /**
     * Get the total earnings (before deductions)
     */
    public function getTotalEarningsAttribute(): float
    {
        return ($this->basic_salary ?? $this->gross_pay ?? 0)
            + ($this->overtime_pay ?? 0)
            + ($this->allowances ?? 0)
            + ($this->bonus ?? 0)
            + ($this->commission ?? 0)
            + ($this->other_earnings ?? 0);
    }

    /**
     * Get period description
     */
    public function getPeriodDescriptionAttribute(): string
    {
        $start = $this->period_start->format('M j');
        $end = $this->period_end->format('M j, Y');
        return "{$start} - {$end}";
    }

    /**
     * Scope for a specific pay frequency
     */
    public function scopeForFrequency($query, string $frequency)
    {
        return $query->where('pay_frequency', $frequency);
    }

    /**
     * Scope for draft payrolls
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for finalized payrolls
     */
    public function scopeFinalized($query)
    {
        return $query->where('status', 'finalized');
    }

    /**
     * Scope for paid payrolls
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
