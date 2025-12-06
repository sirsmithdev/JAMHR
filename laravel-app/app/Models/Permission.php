<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'module',
        'description',
    ];

    /**
     * Get permissions for a specific role
     */
    public static function forRole(string $role): array
    {
        return Cache::remember("permissions.{$role}", 3600, function () use ($role) {
            return self::query()
                ->join('role_permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                ->where('role_permissions.role', $role)
                ->pluck('permissions.name')
                ->toArray();
        });
    }

    /**
     * Check if a role has a specific permission
     */
    public static function roleHas(string $role, string $permission): bool
    {
        $permissions = self::forRole($role);
        return in_array($permission, $permissions);
    }

    /**
     * Get all permissions grouped by module
     */
    public static function groupedByModule(): array
    {
        return self::query()
            ->orderBy('module')
            ->orderBy('display_name')
            ->get()
            ->groupBy('module')
            ->toArray();
    }

    /**
     * Clear permission cache for a role
     */
    public static function clearCache(?string $role = null): void
    {
        if ($role) {
            Cache::forget("permissions.{$role}");
        } else {
            foreach (['super_admin', 'admin', 'hr', 'manager', 'employee', 'kiosk'] as $r) {
                Cache::forget("permissions.{$r}");
            }
        }
    }
}
