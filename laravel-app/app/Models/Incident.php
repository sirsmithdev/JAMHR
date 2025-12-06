<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'type',
        'severity',
        'description',
        'location',
        'witnesses',
        'occurred_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
        ];
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function getIncidentIdAttribute(): string
    {
        return 'INC-' . date('Y', strtotime($this->created_at)) . '-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    public function getSeverityBadgeClassAttribute(): string
    {
        return match ($this->severity) {
            'high' => 'border-red-200 text-red-700 bg-red-50',
            'medium' => 'border-amber-200 text-amber-700 bg-amber-50',
            'low' => 'border-slate-200 text-slate-700 bg-slate-50',
            default => 'border-gray-200 text-gray-700 bg-gray-50',
        };
    }

    public function getStatusIndicatorClassAttribute(): string
    {
        return match ($this->status) {
            'open' => 'bg-red-500 animate-pulse',
            'investigating' => 'bg-blue-500',
            'resolved' => 'bg-emerald-500',
            default => 'bg-gray-500',
        };
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
}
