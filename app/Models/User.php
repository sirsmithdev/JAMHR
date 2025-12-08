<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'reporter_id');
    }

    public function approvedLeaves(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'approved_by');
    }

    public function appraisalsGiven(): HasMany
    {
        return $this->hasMany(Appraisal::class, 'reviewer_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // Role checks
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isHR(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'hr']);
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function isKiosk(): bool
    {
        return $this->role === 'kiosk';
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'super_admin') {
            return true;
        }

        return Permission::roleHas($this->role, $permission);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get all permissions for this user's role
     */
    public function getPermissions(): array
    {
        if ($this->role === 'super_admin') {
            return Permission::pluck('name')->toArray();
        }

        return Permission::forRole($this->role);
    }

    /**
     * Check if user can manage another user based on role hierarchy
     */
    public function canManage(User $user): bool
    {
        $hierarchy = [
            'super_admin' => 6,
            'admin' => 5,
            'hr' => 4,
            'manager' => 3,
            'employee' => 2,
            'kiosk' => 1,
        ];

        return ($hierarchy[$this->role] ?? 0) > ($hierarchy[$user->role] ?? 0);
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayNameAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'Super Administrator',
            'admin' => 'Administrator',
            'hr' => 'HR Manager',
            'manager' => 'Manager',
            'employee' => 'Employee',
            'kiosk' => 'Kiosk',
            default => ucfirst($this->role),
        };
    }
}
