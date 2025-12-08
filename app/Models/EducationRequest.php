<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'education_program_id',
        'request_number',
        'institution',
        'program_name',
        'program_type',
        'start_date',
        'end_date',
        'total_cost',
        'requested_amount',
        'approved_amount',
        'amount_paid',
        'status',
        'justification',
        'rejection_reason',
        'repayment_required',
        'service_commitment_months',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_cost' => 'decimal:2',
            'requested_amount' => 'decimal:2',
            'approved_amount' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'repayment_required' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            if (empty($request->request_number)) {
                $request->request_number = 'EDU-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function educationProgram(): BelongsTo
    {
        return $this->belongsTo(EducationProgram::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function reimbursements(): HasMany
    {
        return $this->hasMany(EducationReimbursement::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(EducationGrade::class);
    }

    public function qualifications(): HasMany
    {
        return $this->hasMany(EmployeeQualification::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'rejected' => 'bg-red-100 text-red-800',
            'in_progress' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-emerald-100 text-emerald-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getProgramTypeLabelAttribute(): string
    {
        return match ($this->program_type) {
            'degree' => 'Degree',
            'certificate' => 'Certificate',
            'diploma' => 'Diploma',
            'professional' => 'Professional',
            'workshop' => 'Workshop',
            default => ucfirst($this->program_type),
        };
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, ($this->approved_amount ?? 0) - $this->amount_paid);
    }

    public function getServiceCommitmentEndDateAttribute()
    {
        if (!$this->service_commitment_months || !$this->end_date) {
            return null;
        }
        return $this->end_date->addMonths($this->service_commitment_months);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['approved', 'in_progress']);
    }
}
