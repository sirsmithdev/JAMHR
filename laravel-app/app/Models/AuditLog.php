<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo('model');
    }

    /**
     * Log an action
     */
    public static function log(
        string $action,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get human-readable action description
     */
    public function getDescriptionAttribute(): string
    {
        $modelName = $this->model_type ? class_basename($this->model_type) : 'Record';

        return match($this->action) {
            'create' => "Created {$modelName} #{$this->model_id}",
            'update' => "Updated {$modelName} #{$this->model_id}",
            'delete' => "Deleted {$modelName} #{$this->model_id}",
            'view' => "Viewed {$modelName} #{$this->model_id}",
            'export' => "Exported {$modelName} data",
            'login' => "Logged in",
            'logout' => "Logged out",
            default => ucfirst($this->action),
        };
    }
}
