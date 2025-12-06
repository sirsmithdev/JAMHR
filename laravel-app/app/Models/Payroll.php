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
        'nht_employee',
        'nht_employer',
        'nis_employee',
        'nis_employer',
        'ed_tax_employee',
        'ed_tax_employer',
        'heart_employer',
        'income_tax',
        'net_pay',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'gross_pay' => 'decimal:2',
            'nht_employee' => 'decimal:2',
            'nht_employer' => 'decimal:2',
            'nis_employee' => 'decimal:2',
            'nis_employer' => 'decimal:2',
            'ed_tax_employee' => 'decimal:2',
            'ed_tax_employer' => 'decimal:2',
            'heart_employer' => 'decimal:2',
            'income_tax' => 'decimal:2',
            'net_pay' => 'decimal:2',
        ];
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
