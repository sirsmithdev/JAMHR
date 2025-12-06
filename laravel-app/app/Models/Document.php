<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'file_path',
        'file_type',
        'file_size',
        'category',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getCategoryBadgeClassAttribute(): string
    {
        return 'bg-slate-100 text-slate-700';
    }

    public function getFileTypeUpperAttribute(): string
    {
        return strtoupper($this->file_type ?? 'FILE');
    }
}
