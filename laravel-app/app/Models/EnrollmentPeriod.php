<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EnrollmentPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'start_date',
        'end_date',
        'effective_date',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'effective_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(BenefitEnrollment::class);
    }

    public function getIsOpenAttribute(): bool
    {
        $now = now()->toDateString();
        return $this->is_active && $now >= $this->start_date && $now <= $this->end_date;
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'open' => 'bg-emerald-100 text-emerald-800',
            'special' => 'bg-blue-100 text-blue-800',
            'new_hire' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function scopeOpen($query)
    {
        $now = now()->toDateString();
        return $query->where('is_active', true)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
