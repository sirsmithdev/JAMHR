<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllowancePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_allowance_id',
        'payroll_id',
        'payment_date',
        'amount',
        'taxable_amount',
        'receipt_path',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'amount' => 'decimal:2',
            'taxable_amount' => 'decimal:2',
        ];
    }

    public function employeeAllowance(): BelongsTo
    {
        return $this->belongsTo(EmployeeAllowance::class);
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }
}
