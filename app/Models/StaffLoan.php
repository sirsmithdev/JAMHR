<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaffLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'loan_type_id',
        'loan_number',
        'principal_amount',
        'interest_rate',
        'market_rate',
        'term_months',
        'monthly_payment',
        'total_interest',
        'total_repayment',
        'outstanding_balance',
        'taxable_benefit',
        'application_date',
        'approval_date',
        'disbursement_date',
        'first_payment_date',
        'maturity_date',
        'status',
        'purpose',
        'rejection_reason',
        'approved_by',
        'guarantor_id',
    ];

    protected function casts(): array
    {
        return [
            'principal_amount' => 'decimal:2',
            'interest_rate' => 'decimal:2',
            'market_rate' => 'decimal:2',
            'monthly_payment' => 'decimal:2',
            'total_interest' => 'decimal:2',
            'total_repayment' => 'decimal:2',
            'outstanding_balance' => 'decimal:2',
            'taxable_benefit' => 'decimal:2',
            'application_date' => 'date',
            'approval_date' => 'date',
            'disbursement_date' => 'date',
            'first_payment_date' => 'date',
            'maturity_date' => 'date',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($loan) {
            if (empty($loan->loan_number)) {
                $loan->loan_number = 'LN-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function loanType(): BelongsTo
    {
        return $this->belongsTo(LoanType::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function guarantor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'guarantor_id');
    }

    public function repayments(): HasMany
    {
        return $this->hasMany(LoanRepayment::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(LoanDocument::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'rejected' => 'bg-red-100 text-red-800',
            'active' => 'bg-emerald-100 text-emerald-800',
            'paid_off' => 'bg-green-100 text-green-800',
            'defaulted' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getAmountPaidAttribute(): float
    {
        return $this->repayments()->where('status', 'paid')->sum('amount_paid');
    }

    public function getPaymentProgressAttribute(): float
    {
        if ($this->total_repayment <= 0) return 0;
        return ($this->amount_paid / $this->total_repayment) * 100;
    }

    public function getRemainingPaymentsAttribute(): int
    {
        return $this->repayments()->whereIn('status', ['scheduled', 'overdue'])->count();
    }

    public function getNextPaymentAttribute()
    {
        return $this->repayments()
            ->whereIn('status', ['scheduled', 'overdue'])
            ->orderBy('due_date')
            ->first();
    }

    public function updateTaxableBenefit(): void
    {
        $rateSpread = max(0, $this->market_rate - $this->interest_rate);
        $this->taxable_benefit = ($this->outstanding_balance * $rateSpread / 100) / 12;
        $this->save();
    }

    public function generateAmortizationSchedule(): array
    {
        $schedule = [];
        $balance = $this->principal_amount;
        $monthlyRate = $this->interest_rate / 100 / 12;
        $paymentDate = $this->first_payment_date ?? now();

        for ($i = 1; $i <= $this->term_months; $i++) {
            $interestAmount = $balance * $monthlyRate;
            $principalAmount = $this->monthly_payment - $interestAmount;
            $balance -= $principalAmount;

            $schedule[] = [
                'payment_number' => $i,
                'due_date' => $paymentDate->copy()->addMonths($i - 1),
                'scheduled_amount' => $this->monthly_payment,
                'principal_amount' => max(0, $principalAmount),
                'interest_amount' => max(0, $interestAmount),
                'balance_after' => max(0, $balance),
            ];
        }

        return $schedule;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
