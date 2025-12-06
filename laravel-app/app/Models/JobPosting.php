<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'department',
        'location',
        'employment_type',
        'salary_min',
        'salary_max',
        'description',
        'requirements',
        'responsibilities',
        'benefits',
        'status',
        'posted_date',
        'closing_date',
        'positions_available',
        'positions_filled',
        'created_by',
    ];

    protected $casts = [
        'posted_date' => 'date',
        'closing_date' => 'date',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'Draft' => 'bg-gray-100 text-gray-800',
            'Open' => 'bg-emerald-100 text-emerald-800',
            'On Hold' => 'bg-amber-100 text-amber-800',
            'Closed' => 'bg-red-100 text-red-800',
            'Filled' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getSalaryRangeAttribute(): string
    {
        if ($this->salary_min && $this->salary_max) {
            return 'J$' . number_format($this->salary_min) . ' - J$' . number_format($this->salary_max);
        } elseif ($this->salary_min) {
            return 'From J$' . number_format($this->salary_min);
        } elseif ($this->salary_max) {
            return 'Up to J$' . number_format($this->salary_max);
        }
        return 'Negotiable';
    }

    public function getApplicationsCountAttribute(): int
    {
        return $this->applications()->count();
    }

    public function getNewApplicationsCountAttribute(): int
    {
        return $this->applications()->where('status', 'New')->count();
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'Open');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['Open', 'On Hold']);
    }
}
