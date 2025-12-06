<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'year',
        'entitled_days',
        'accrued_days',
        'used_days',
        'pending_days',
        'carried_over_days',
        'adjustment_days',
        'available_days',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'entitled_days' => 'decimal:2',
            'accrued_days' => 'decimal:2',
            'used_days' => 'decimal:2',
            'pending_days' => 'decimal:2',
            'carried_over_days' => 'decimal:2',
            'adjustment_days' => 'decimal:2',
            'available_days' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function recalculateAvailable(): void
    {
        $this->available_days = $this->entitled_days
            + $this->accrued_days
            + $this->carried_over_days
            + $this->adjustment_days
            - $this->used_days
            - $this->pending_days;

        $this->save();
    }

    public function getUsagePercentageAttribute(): float
    {
        $total = $this->entitled_days + $this->carried_over_days + $this->adjustment_days;
        if ($total <= 0) return 0;
        return ($this->used_days / $total) * 100;
    }

    public function getIsLowAttribute(): bool
    {
        return $this->available_days <= 2 && $this->entitled_days > 0;
    }

    public static function getOrCreate(int $employeeId, int $leaveTypeId, int $year): self
    {
        return self::firstOrCreate(
            [
                'employee_id' => $employeeId,
                'leave_type_id' => $leaveTypeId,
                'year' => $year,
            ],
            [
                'entitled_days' => 0,
                'accrued_days' => 0,
                'used_days' => 0,
                'pending_days' => 0,
                'carried_over_days' => 0,
                'adjustment_days' => 0,
                'available_days' => 0,
            ]
        );
    }

    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    public function scopeCurrentYear($query)
    {
        return $query->where('year', now()->year);
    }
}
