<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanRepayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_loan_id',
        'payroll_id',
        'payment_number',
        'due_date',
        'payment_date',
        'scheduled_amount',
        'principal_amount',
        'interest_amount',
        'amount_paid',
        'balance_after',
        'status',
        'payment_method',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'payment_date' => 'date',
            'scheduled_amount' => 'decimal:2',
            'principal_amount' => 'decimal:2',
            'interest_amount' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'balance_after' => 'decimal:2',
        ];
    }

    public function staffLoan(): BelongsTo
    {
        return $this->belongsTo(StaffLoan::class);
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'scheduled' => 'bg-blue-100 text-blue-800',
            'paid' => 'bg-emerald-100 text-emerald-800',
            'partial' => 'bg-yellow-100 text-yellow-800',
            'overdue' => 'bg-red-100 text-red-800',
            'waived' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'paid' && $this->due_date < now();
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'payroll_deduction' => 'Payroll Deduction',
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'cheque' => 'Cheque',
            default => $this->payment_method ?? 'N/A',
        };
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
            ->orWhere(function ($q) {
                $q->where('status', 'scheduled')
                    ->where('due_date', '<', now());
            });
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
