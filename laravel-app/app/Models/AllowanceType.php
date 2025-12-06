<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AllowanceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'category',
        'description',
        'is_taxable',
        'is_fixed',
        'default_amount',
        'frequency',
        'tax_threshold',
        'requires_receipts',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_taxable' => 'boolean',
            'is_fixed' => 'boolean',
            'default_amount' => 'decimal:2',
            'tax_threshold' => 'decimal:2',
            'requires_receipts' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function employeeAllowances(): HasMany
    {
        return $this->hasMany(EmployeeAllowance::class);
    }

    public function getCategoryBadgeClassAttribute(): string
    {
        return match ($this->category) {
            'transport' => 'bg-blue-100 text-blue-800',
            'meal' => 'bg-amber-100 text-amber-800',
            'phone' => 'bg-purple-100 text-purple-800',
            'housing' => 'bg-emerald-100 text-emerald-800',
            'motor_vehicle' => 'bg-red-100 text-red-800',
            'uniform' => 'bg-cyan-100 text-cyan-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getFrequencyLabelAttribute(): string
    {
        return match ($this->frequency) {
            'monthly' => 'Monthly',
            'bi-weekly' => 'Bi-Weekly',
            'daily' => 'Daily',
            'per_diem' => 'Per Diem',
            'annual' => 'Annual',
            default => ucfirst($this->frequency),
        };
    }

    public function calculateTaxableAmount(float $amount): float
    {
        if (!$this->is_taxable) {
            return 0;
        }

        if ($this->tax_threshold && $amount <= $this->tax_threshold) {
            return 0;
        }

        if ($this->tax_threshold) {
            return $amount - $this->tax_threshold;
        }

        return $amount;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
