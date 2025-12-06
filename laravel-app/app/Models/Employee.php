<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'job_title',
        'department',
        'trn_number',
        'nis_number',
        'pin',
        'start_date',
        'salary_annual',
        'hourly_rate',
        'vacation_days_total',
        'vacation_days_used',
        'sick_days_total',
        'sick_days_used',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'salary_annual' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function appraisals(): HasMany
    {
        return $this->hasMany(Appraisal::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }

    public function getVacationDaysRemainingAttribute(): int
    {
        return $this->vacation_days_total - $this->vacation_days_used;
    }

    public function getSickDaysRemainingAttribute(): int
    {
        return $this->sick_days_total - $this->sick_days_used;
    }
}
