<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Cache duration in seconds (1 hour)
     */
    protected const CACHE_TTL = 3600;

    /**
     * Get a setting value
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = "setting.{$key}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($key, $default) {
            $parts = explode('.', $key);

            if (count($parts) === 2) {
                $setting = self::where('group', $parts[0])
                    ->where('key', $parts[1])
                    ->first();
            } else {
                $setting = self::where('key', $key)->first();
            }

            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, mixed $value): bool
    {
        $parts = explode('.', $key);

        if (count($parts) === 2) {
            $group = $parts[0];
            $settingKey = $parts[1];
        } else {
            $group = 'general';
            $settingKey = $key;
        }

        $setting = self::updateOrCreate(
            ['group' => $group, 'key' => $settingKey],
            ['value' => is_array($value) || is_object($value) ? json_encode($value) : (string) $value]
        );

        // Clear cache
        Cache::forget("setting.{$key}");
        Cache::forget("settings.{$group}");
        Cache::forget('settings.all');

        return (bool) $setting;
    }

    /**
     * Get all settings for a group
     */
    public static function getGroup(string $group): array
    {
        return Cache::remember("settings.{$group}", self::CACHE_TTL, function () use ($group) {
            $settings = self::where('group', $group)->get();

            $result = [];
            foreach ($settings as $setting) {
                $result[$setting->key] = self::castValue($setting->value, $setting->type);
            }

            return $result;
        });
    }

    /**
     * Get all settings
     */
    public static function getAll(): array
    {
        return Cache::remember('settings.all', self::CACHE_TTL, function () {
            $settings = self::all();

            $result = [];
            foreach ($settings as $setting) {
                if (!isset($result[$setting->group])) {
                    $result[$setting->group] = [];
                }
                $result[$setting->group][$setting->key] = self::castValue($setting->value, $setting->type);
            }

            return $result;
        });
    }

    /**
     * Update multiple settings at once
     */
    public static function setMany(string $group, array $values): bool
    {
        foreach ($values as $key => $value) {
            self::set("{$group}.{$key}", $value);
        }

        return true;
    }

    /**
     * Cast value to correct type
     */
    protected static function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            'array' => json_decode($value, true) ?? [],
            default => $value,
        };
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        $groups = self::select('group')->distinct()->pluck('group');

        foreach ($groups as $group) {
            Cache::forget("settings.{$group}");

            $keys = self::where('group', $group)->pluck('key');
            foreach ($keys as $key) {
                Cache::forget("setting.{$group}.{$key}");
            }
        }

        Cache::forget('settings.all');
    }

    /**
     * Get company settings
     */
    public static function company(): array
    {
        return self::getGroup('company');
    }

    /**
     * Get payroll settings
     */
    public static function payroll(): array
    {
        return self::getGroup('payroll');
    }

    /**
     * Get leave settings
     */
    public static function leave(): array
    {
        return self::getGroup('leave');
    }

    /**
     * Get notification settings
     */
    public static function notifications(): array
    {
        return self::getGroup('notifications');
    }

    /**
     * Get system settings
     */
    public static function system(): array
    {
        return self::getGroup('system');
    }
}
