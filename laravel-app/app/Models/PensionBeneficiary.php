<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PensionBeneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'pension_account_id',
        'first_name',
        'last_name',
        'relationship',
        'date_of_birth',
        'trn',
        'percentage',
        'type',
        'address',
        'phone',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'percentage' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function pensionAccount(): BelongsTo
    {
        return $this->belongsTo(PensionAccount::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'primary' => 'bg-emerald-100 text-emerald-800',
            'contingent' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePrimary($query)
    {
        return $query->where('type', 'primary');
    }

    public function scopeContingent($query)
    {
        return $query->where('type', 'contingent');
    }
}
