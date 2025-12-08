<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Termination extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'notice_date',
        'last_working_day',
        'reason',
        'exit_interview_notes',
        'exit_interview_completed',
        'exit_interview_date',
        'company_property_returned',
        'access_revoked',
        'final_pay_processed',
        'benefits_terminated',
        'knowledge_transfer_complete',
        'final_salary',
        'unused_leave_payout',
        'severance_pay',
        'other_payments',
        'deductions',
        'total_final_pay',
        'nht_clearance',
        'nis_updated',
        'tax_forms_issued',
        'status',
        'eligible_for_rehire',
        'rehire_notes',
        'processed_by',
    ];

    protected $casts = [
        'notice_date' => 'date',
        'last_working_day' => 'date',
        'exit_interview_date' => 'date',
        'exit_interview_completed' => 'boolean',
        'company_property_returned' => 'boolean',
        'access_revoked' => 'boolean',
        'final_pay_processed' => 'boolean',
        'benefits_terminated' => 'boolean',
        'knowledge_transfer_complete' => 'boolean',
        'nht_clearance' => 'boolean',
        'nis_updated' => 'boolean',
        'tax_forms_issued' => 'boolean',
        'eligible_for_rehire' => 'boolean',
        'final_salary' => 'decimal:2',
        'unused_leave_payout' => 'decimal:2',
        'severance_pay' => 'decimal:2',
        'other_payments' => 'decimal:2',
        'deductions' => 'decimal:2',
        'total_final_pay' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match($this->type) {
            'Resignation' => 'bg-blue-100 text-blue-800',
            'Termination' => 'bg-red-100 text-red-800',
            'Redundancy' => 'bg-orange-100 text-orange-800',
            'End of Contract' => 'bg-gray-100 text-gray-800',
            'Retirement' => 'bg-purple-100 text-purple-800',
            'Mutual Agreement' => 'bg-cyan-100 text-cyan-800',
            'Dismissal' => 'bg-red-200 text-red-900',
            'Death' => 'bg-gray-200 text-gray-900',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'Pending' => 'bg-amber-100 text-amber-800',
            'In Progress' => 'bg-blue-100 text-blue-800',
            'Completed' => 'bg-emerald-100 text-emerald-800',
            'Cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getNoticePeriodDaysAttribute(): int
    {
        return $this->notice_date->diffInDays($this->last_working_day);
    }

    public function getClearanceProgressAttribute(): int
    {
        $items = [
            $this->company_property_returned,
            $this->access_revoked,
            $this->final_pay_processed,
            $this->benefits_terminated,
            $this->knowledge_transfer_complete,
            $this->exit_interview_completed,
        ];

        $completed = count(array_filter($items));
        return (int) round(($completed / count($items)) * 100);
    }

    public function getJamaicaClearanceProgressAttribute(): int
    {
        $items = [
            $this->nht_clearance,
            $this->nis_updated,
            $this->tax_forms_issued,
        ];

        $completed = count(array_filter($items));
        return (int) round(($completed / count($items)) * 100);
    }

    public function calculateFinalPay(): void
    {
        $this->total_final_pay =
            ($this->final_salary ?? 0) +
            ($this->unused_leave_payout ?? 0) +
            ($this->severance_pay ?? 0) +
            ($this->other_payments ?? 0) -
            ($this->deductions ?? 0);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['Pending', 'In Progress']);
    }
}
