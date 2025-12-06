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
            'gross_pay' => 'decimal:2',
            'basic_salary' => 'decimal:2',
            'overtime_pay' => 'decimal:2',
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
        return $this->nht_employee + $this->nis_employee + $this->ed_tax_employee + $this->income_tax;
    }

    public function getTotalEmployerContributionsAttribute(): float
    {
        return $this->nht_employer + $this->nis_employer + $this->ed_tax_employer + $this->heart_employer;
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
}
