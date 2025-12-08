<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BenefitDependent extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'benefit_enrollment_id',
        'first_name',
        'last_name',
        'relationship',
        'date_of_birth',
        'gender',
        'trn',
        'is_student',
        'is_disabled',
        'document_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'is_student' => 'boolean',
            'is_disabled' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(BenefitEnrollment::class, 'benefit_enrollment_id');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    public function getRelationshipLabelAttribute(): string
    {
        return match ($this->relationship) {
            'spouse' => 'Spouse',
            'child' => 'Child',
            'domestic_partner' => 'Domestic Partner',
            'parent' => 'Parent',
            default => ucfirst($this->relationship),
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
