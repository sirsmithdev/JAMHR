<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationReimbursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'education_request_id',
        'submission_date',
        'expense_type',
        'description',
        'amount',
        'approved_amount',
        'receipt_path',
        'status',
        'payment_date',
        'payment_method',
        'notes',
        'processed_by',
    ];

    protected function casts(): array
    {
        return [
            'submission_date' => 'date',
            'amount' => 'decimal:2',
            'approved_amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function educationRequest(): BelongsTo
    {
        return $this->belongsTo(EducationRequest::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'rejected' => 'bg-red-100 text-red-800',
            'paid' => 'bg-emerald-100 text-emerald-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getExpenseTypeLabelAttribute(): string
    {
        return match ($this->expense_type) {
            'tuition' => 'Tuition',
            'books' => 'Books',
            'exam_fees' => 'Exam Fees',
            'materials' => 'Materials',
            'registration' => 'Registration',
            default => ucfirst(str_replace('_', ' ', $this->expense_type)),
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
