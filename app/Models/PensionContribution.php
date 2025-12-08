<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PensionContribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'pension_account_id',
        'payroll_id',
        'contribution_date',
        'contribution_type',
        'amount',
        'running_balance',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'contribution_date' => 'date',
            'amount' => 'decimal:2',
            'running_balance' => 'decimal:2',
        ];
    }

    public function pensionAccount(): BelongsTo
    {
        return $this->belongsTo(PensionAccount::class);
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    public function getContributionTypeLabelAttribute(): string
    {
        return match ($this->contribution_type) {
            'employee' => 'Employee',
            'employer' => 'Employer',
            'voluntary' => 'Voluntary',
            default => ucfirst($this->contribution_type),
        };
    }

    public function getContributionTypeBadgeClassAttribute(): string
    {
        return match ($this->contribution_type) {
            'employee' => 'bg-blue-100 text-blue-800',
            'employer' => 'bg-emerald-100 text-emerald-800',
            'voluntary' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
