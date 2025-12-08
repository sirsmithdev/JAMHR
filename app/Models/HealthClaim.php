<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'benefit_enrollment_id',
        'claim_number',
        'service_date',
        'submission_date',
        'provider_name',
        'claim_type',
        'description',
        'amount_claimed',
        'amount_approved',
        'amount_paid',
        'employee_responsibility',
        'status',
        'denial_reason',
        'receipt_path',
        'processed_by',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'service_date' => 'date',
            'submission_date' => 'date',
            'amount_claimed' => 'decimal:2',
            'amount_approved' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'employee_responsibility' => 'decimal:2',
            'processed_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($claim) {
            if (empty($claim->claim_number)) {
                $claim->claim_number = 'CLM-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(BenefitEnrollment::class, 'benefit_enrollment_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'submitted' => 'bg-blue-100 text-blue-800',
            'under_review' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-emerald-100 text-emerald-800',
            'denied' => 'bg-red-100 text-red-800',
            'paid' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getClaimTypeLabelAttribute(): string
    {
        return match ($this->claim_type) {
            'medical' => 'Medical',
            'dental' => 'Dental',
            'vision' => 'Vision',
            'prescription' => 'Prescription',
            default => ucfirst($this->claim_type),
        };
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['submitted', 'under_review']);
    }

    public function scopeApproved($query)
    {
        return $query->whereIn('status', ['approved', 'paid']);
    }
}
