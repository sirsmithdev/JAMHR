<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeAllowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'allowance_type_id',
        'amount',
        'frequency',
        'effective_date',
        'end_date',
        'status',
        'notes',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'effective_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function allowanceType(): BelongsTo
    {
        return $this->belongsTo(AllowanceType::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(AllowancePayment::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-emerald-100 text-emerald-800',
            'suspended' => 'bg-yellow-100 text-yellow-800',
            'terminated' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getMonthlyAmountAttribute(): float
    {
        return match ($this->frequency) {
            'monthly' => $this->amount,
            'bi-weekly' => $this->amount * 26 / 12,
            'daily' => $this->amount * 22, // Assuming 22 working days
            'annual' => $this->amount / 12,
            default => $this->amount,
        };
    }

    public function getAnnualAmountAttribute(): float
    {
        return match ($this->frequency) {
            'monthly' => $this->amount * 12,
            'bi-weekly' => $this->amount * 26,
            'daily' => $this->amount * 260, // Assuming 260 working days
            'annual' => $this->amount,
            default => $this->amount * 12,
        };
    }

    public function getTaxableAmountAttribute(): float
    {
        return $this->allowanceType->calculateTaxableAmount($this->amount);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('effective_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }
}
