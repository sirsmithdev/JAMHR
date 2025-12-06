<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentalLeaveRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_request_id',
        'leave_type',
        'expected_date',
        'actual_date',
        'leave_start_date',
        'leave_end_date',
        'total_days',
        'paid_days',
        'unpaid_days',
        'pay_rate',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'expected_date' => 'date',
            'actual_date' => 'date',
            'leave_start_date' => 'date',
            'leave_end_date' => 'date',
            'pay_rate' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveRequest(): BelongsTo
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    public function getLeaveTypeLabelAttribute(): string
    {
        return match ($this->leave_type) {
            'maternity' => 'Maternity Leave',
            'paternity' => 'Paternity Leave',
            'adoption' => 'Adoption Leave',
            default => ucfirst($this->leave_type) . ' Leave',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'active' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-emerald-100 text-emerald-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPaidAmountAttribute(): float
    {
        // Calculate based on pay rate percentage
        return $this->paid_days * ($this->pay_rate / 100);
    }

    /**
     * Jamaica maternity leave rules:
     * - 12 weeks total (60 working days)
     * - 8 weeks paid at full salary
     * - Must start at least 2 weeks before due date
     */
    public static function createMaternityLeave(Employee $employee, $expectedDate, $leaveStartDate = null): self
    {
        $expectedDate = is_string($expectedDate) ? \Carbon\Carbon::parse($expectedDate) : $expectedDate;
        $leaveStartDate = $leaveStartDate
            ? (is_string($leaveStartDate) ? \Carbon\Carbon::parse($leaveStartDate) : $leaveStartDate)
            : $expectedDate->copy()->subWeeks(2);

        $leaveEndDate = $leaveStartDate->copy()->addWeeks(12);

        return self::create([
            'employee_id' => $employee->id,
            'leave_type' => 'maternity',
            'expected_date' => $expectedDate,
            'leave_start_date' => $leaveStartDate,
            'leave_end_date' => $leaveEndDate,
            'total_days' => 60, // 12 weeks
            'paid_days' => 40, // 8 weeks paid
            'unpaid_days' => 20, // 4 weeks unpaid
            'pay_rate' => 100,
            'status' => 'pending',
        ]);
    }

    /**
     * Jamaica paternity leave: 20 working days paid
     */
    public static function createPaternityLeave(Employee $employee, $expectedDate, $leaveStartDate): self
    {
        $leaveStartDate = is_string($leaveStartDate) ? \Carbon\Carbon::parse($leaveStartDate) : $leaveStartDate;
        $leaveEndDate = $leaveStartDate->copy()->addWeekdays(20);

        return self::create([
            'employee_id' => $employee->id,
            'leave_type' => 'paternity',
            'expected_date' => $expectedDate,
            'leave_start_date' => $leaveStartDate,
            'leave_end_date' => $leaveEndDate,
            'total_days' => 20,
            'paid_days' => 20,
            'unpaid_days' => 0,
            'pay_rate' => 100,
            'status' => 'pending',
        ]);
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
