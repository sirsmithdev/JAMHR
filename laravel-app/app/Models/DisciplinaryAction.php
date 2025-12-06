<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisciplinaryAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'incident_date',
        'action_date',
        'type',
        'category',
        'description',
        'evidence',
        'employee_response',
        'corrective_action',
        'follow_up_date',
        'follow_up_notes',
        'suspension_start',
        'suspension_end',
        'with_pay',
        'pip_start_date',
        'pip_end_date',
        'pip_goals',
        'pip_outcome',
        'witnesses',
        'document_path',
        'employee_acknowledged',
        'acknowledged_at',
        'union_representative_present',
        'union_representative_name',
        'status',
        'appeal_notes',
        'issued_by',
        'approved_by',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'action_date' => 'date',
        'follow_up_date' => 'date',
        'suspension_start' => 'date',
        'suspension_end' => 'date',
        'pip_start_date' => 'date',
        'pip_end_date' => 'date',
        'with_pay' => 'boolean',
        'employee_acknowledged' => 'boolean',
        'acknowledged_at' => 'datetime',
        'union_representative_present' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match($this->type) {
            'Verbal Warning' => 'bg-yellow-100 text-yellow-800',
            'Written Warning' => 'bg-amber-100 text-amber-800',
            'Final Written Warning' => 'bg-orange-100 text-orange-800',
            'Suspension' => 'bg-red-100 text-red-800',
            'Demotion' => 'bg-purple-100 text-purple-800',
            'Termination' => 'bg-red-200 text-red-900',
            'Performance Improvement Plan' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getCategoryBadgeClassAttribute(): string
    {
        return match($this->category) {
            'Attendance' => 'bg-blue-100 text-blue-800',
            'Performance' => 'bg-purple-100 text-purple-800',
            'Conduct' => 'bg-amber-100 text-amber-800',
            'Policy Violation' => 'bg-orange-100 text-orange-800',
            'Insubordination' => 'bg-red-100 text-red-800',
            'Harassment' => 'bg-red-200 text-red-900',
            'Safety Violation' => 'bg-yellow-100 text-yellow-800',
            'Theft' => 'bg-red-300 text-red-900',
            'Substance Abuse' => 'bg-pink-100 text-pink-800',
            'Other' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'Open' => 'bg-blue-100 text-blue-800',
            'Under Review' => 'bg-amber-100 text-amber-800',
            'Resolved' => 'bg-emerald-100 text-emerald-800',
            'Appealed' => 'bg-purple-100 text-purple-800',
            'Overturned' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getSeverityLevelAttribute(): int
    {
        return match($this->type) {
            'Verbal Warning' => 1,
            'Written Warning' => 2,
            'Final Written Warning' => 3,
            'Performance Improvement Plan' => 3,
            'Suspension' => 4,
            'Demotion' => 4,
            'Termination' => 5,
            default => 1,
        };
    }

    public function getSuspensionDaysAttribute(): ?int
    {
        if ($this->suspension_start && $this->suspension_end) {
            return $this->suspension_start->diffInDays($this->suspension_end);
        }
        return null;
    }

    public function getPipDaysRemainingAttribute(): ?int
    {
        if ($this->pip_end_date && $this->pip_outcome === 'Pending') {
            return max(0, now()->diffInDays($this->pip_end_date, false));
        }
        return null;
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['Open', 'Under Review']);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeRecent($query, $days = 365)
    {
        return $query->where('action_date', '>=', now()->subDays($days));
    }
}
