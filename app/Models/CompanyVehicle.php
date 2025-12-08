<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'registration_number',
        'make',
        'model',
        'year',
        'original_cost',
        'current_value',
        'acquisition_date',
        'assignment_date',
        'private_use_percentage',
        'annual_taxable_benefit',
        'monthly_taxable_benefit',
        'fuel_card_number',
        'monthly_fuel_limit',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'original_cost' => 'decimal:2',
            'current_value' => 'decimal:2',
            'acquisition_date' => 'date',
            'assignment_date' => 'date',
            'annual_taxable_benefit' => 'decimal:2',
            'monthly_taxable_benefit' => 'decimal:2',
            'monthly_fuel_limit' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->year} {$this->make} {$this->model}";
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'available' => 'bg-emerald-100 text-emerald-800',
            'assigned' => 'bg-blue-100 text-blue-800',
            'maintenance' => 'bg-yellow-100 text-yellow-800',
            'disposed' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getAgeAttribute(): int
    {
        return now()->year - $this->year;
    }

    /**
     * Calculate taxable benefit based on Jamaica tax rules
     * The taxable benefit ranges from JMD 30,000 to JMD 140,000 per annum
     * based on cost, age, and private use percentage
     */
    public function calculateTaxableBenefit(): void
    {
        // Jamaica taxable benefit calculation for company cars
        // Based on original cost and age of vehicle
        $baseBenefit = $this->original_cost * 0.1; // 10% of cost as base

        // Reduce by age (depreciation factor)
        $ageFactor = max(0.3, 1 - ($this->age * 0.1)); // Minimum 30%
        $adjustedBenefit = $baseBenefit * $ageFactor;

        // Apply private use percentage
        $privateBenefit = $adjustedBenefit * ($this->private_use_percentage / 100);

        // Cap between JMD 30,000 and JMD 140,000
        $this->annual_taxable_benefit = max(30000, min(140000, $privateBenefit));
        $this->monthly_taxable_benefit = $this->annual_taxable_benefit / 12;
        $this->save();
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }
}
