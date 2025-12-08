<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'break_start',
        'break_end',
        'total_hours',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'clock_in' => 'datetime',
            'clock_out' => 'datetime',
            'break_start' => 'datetime',
            'break_end' => 'datetime',
            'total_hours' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function calculateTotalHours(): float
    {
        if (!$this->clock_in || !$this->clock_out) {
            return 0;
        }

        $totalMinutes = $this->clock_out->diffInMinutes($this->clock_in);

        // Subtract break time if applicable
        if ($this->break_start && $this->break_end) {
            $breakMinutes = $this->break_end->diffInMinutes($this->break_start);
            $totalMinutes -= $breakMinutes;
        }

        return round($totalMinutes / 60, 2);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'on_time' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'late' => 'bg-amber-50 text-amber-700 border-amber-200',
            'overtime' => 'bg-blue-50 text-blue-700 border-blue-200',
            'absent' => 'bg-red-50 text-red-700 border-red-200',
            default => 'bg-gray-50 text-gray-700 border-gray-200',
        };
    }
}
