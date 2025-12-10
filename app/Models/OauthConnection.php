<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class OauthConnection extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'provider_user_id',
        'email',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'scopes',
        'metadata',
        'is_active',
        'connected_by',
    ];

    protected function casts(): array
    {
        return [
            'token_expires_at' => 'datetime',
            'scopes' => 'array',
            'metadata' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Encrypt access token when setting
     */
    public function setAccessTokenAttribute($value): void
    {
        $this->attributes['access_token'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt access token when getting
     */
    public function getAccessTokenAttribute($value): ?string
    {
        if (!$value) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Encrypt refresh token when setting
     */
    public function setRefreshTokenAttribute($value): void
    {
        if ($value) {
            $this->attributes['refresh_token'] = Crypt::encryptString($value);
        } else {
            $this->attributes['refresh_token'] = null;
        }
    }

    /**
     * Decrypt refresh token when getting
     */
    public function getRefreshTokenAttribute($value): ?string
    {
        if (!$value) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * User who connected this integration
     */
    public function connectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'connected_by');
    }

    /**
     * Check if token is expired
     */
    public function isTokenExpired(): bool
    {
        if (!$this->token_expires_at) {
            return false;
        }
        return $this->token_expires_at->isPast();
    }

    /**
     * Check if token will expire soon (within 5 minutes)
     */
    public function isTokenExpiringSoon(): bool
    {
        if (!$this->token_expires_at) {
            return false;
        }
        return $this->token_expires_at->subMinutes(5)->isPast();
    }

    /**
     * Get connection by provider
     */
    public static function forProvider(string $provider): ?self
    {
        return static::where('provider', $provider)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Scope for active connections
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
