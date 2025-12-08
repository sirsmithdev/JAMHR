<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'hr' role to users enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'hr', 'manager', 'employee', 'kiosk') DEFAULT 'employee'");

        // Create permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('module');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create role_permissions pivot table
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['role', 'permission_id']);
        });

        // Create audit_logs table for compliance
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // create, update, delete, view, export
            $table->string('model_type');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index(['model_type', 'model_id']);
            $table->index('user_id');
            $table->index('created_at');
        });

        // Seed default permissions
        $this->seedPermissions();
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'manager', 'employee', 'kiosk') DEFAULT 'employee'");
    }

    private function seedPermissions(): void
    {
        $permissions = [
            // Employees
            ['name' => 'employees.view', 'display_name' => 'View Employees', 'module' => 'employees'],
            ['name' => 'employees.create', 'display_name' => 'Create Employees', 'module' => 'employees'],
            ['name' => 'employees.edit', 'display_name' => 'Edit Employees', 'module' => 'employees'],
            ['name' => 'employees.delete', 'display_name' => 'Delete Employees', 'module' => 'employees'],
            ['name' => 'employees.export', 'display_name' => 'Export Employees', 'module' => 'employees'],

            // Payroll
            ['name' => 'payroll.view', 'display_name' => 'View Payroll', 'module' => 'payroll'],
            ['name' => 'payroll.create', 'display_name' => 'Create Payroll', 'module' => 'payroll'],
            ['name' => 'payroll.edit', 'display_name' => 'Edit Payroll', 'module' => 'payroll'],
            ['name' => 'payroll.approve', 'display_name' => 'Approve Payroll', 'module' => 'payroll'],
            ['name' => 'payroll.export', 'display_name' => 'Export Payroll', 'module' => 'payroll'],

            // Leave
            ['name' => 'leave.view', 'display_name' => 'View Leave Requests', 'module' => 'leave'],
            ['name' => 'leave.create', 'display_name' => 'Create Leave Requests', 'module' => 'leave'],
            ['name' => 'leave.approve', 'display_name' => 'Approve Leave Requests', 'module' => 'leave'],
            ['name' => 'leave.manage', 'display_name' => 'Manage Leave Settings', 'module' => 'leave'],

            // Time & Attendance
            ['name' => 'time.view', 'display_name' => 'View Time Entries', 'module' => 'time'],
            ['name' => 'time.create', 'display_name' => 'Create Time Entries', 'module' => 'time'],
            ['name' => 'time.edit', 'display_name' => 'Edit Time Entries', 'module' => 'time'],
            ['name' => 'time.approve', 'display_name' => 'Approve Time Entries', 'module' => 'time'],

            // Benefits
            ['name' => 'benefits.view', 'display_name' => 'View Benefits', 'module' => 'benefits'],
            ['name' => 'benefits.manage', 'display_name' => 'Manage Benefits', 'module' => 'benefits'],
            ['name' => 'benefits.enroll', 'display_name' => 'Enroll in Benefits', 'module' => 'benefits'],

            // Loans
            ['name' => 'loans.view', 'display_name' => 'View Loans', 'module' => 'loans'],
            ['name' => 'loans.create', 'display_name' => 'Apply for Loans', 'module' => 'loans'],
            ['name' => 'loans.approve', 'display_name' => 'Approve Loans', 'module' => 'loans'],

            // Performance
            ['name' => 'performance.view', 'display_name' => 'View Performance', 'module' => 'performance'],
            ['name' => 'performance.create', 'display_name' => 'Create Appraisals', 'module' => 'performance'],
            ['name' => 'performance.edit', 'display_name' => 'Edit Appraisals', 'module' => 'performance'],

            // Hiring
            ['name' => 'hiring.view', 'display_name' => 'View Job Postings', 'module' => 'hiring'],
            ['name' => 'hiring.manage', 'display_name' => 'Manage Hiring', 'module' => 'hiring'],

            // Disciplinary
            ['name' => 'disciplinary.view', 'display_name' => 'View Disciplinary', 'module' => 'disciplinary'],
            ['name' => 'disciplinary.create', 'display_name' => 'Create Disciplinary', 'module' => 'disciplinary'],
            ['name' => 'disciplinary.manage', 'display_name' => 'Manage Disciplinary', 'module' => 'disciplinary'],

            // Terminations
            ['name' => 'terminations.view', 'display_name' => 'View Terminations', 'module' => 'terminations'],
            ['name' => 'terminations.create', 'display_name' => 'Create Terminations', 'module' => 'terminations'],

            // Documents
            ['name' => 'documents.view', 'display_name' => 'View Documents', 'module' => 'documents'],
            ['name' => 'documents.upload', 'display_name' => 'Upload Documents', 'module' => 'documents'],
            ['name' => 'documents.delete', 'display_name' => 'Delete Documents', 'module' => 'documents'],

            // Reports
            ['name' => 'reports.view', 'display_name' => 'View Reports', 'module' => 'reports'],
            ['name' => 'reports.export', 'display_name' => 'Export Reports', 'module' => 'reports'],

            // Settings
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'module' => 'settings'],
            ['name' => 'settings.manage', 'display_name' => 'Manage Settings', 'module' => 'settings'],
            ['name' => 'users.manage', 'display_name' => 'Manage Users', 'module' => 'settings'],
        ];

        $now = now();
        foreach ($permissions as &$permission) {
            $permission['created_at'] = $now;
            $permission['updated_at'] = $now;
        }

        DB::table('permissions')->insert($permissions);

        // Assign permissions to roles
        $allPermissions = DB::table('permissions')->pluck('id', 'name');

        // Super Admin gets everything
        $superAdminPerms = $allPermissions->keys()->all();

        // Admin gets most things except some sensitive operations
        $adminPerms = $allPermissions->except(['settings.manage'])->keys()->all();

        // HR gets HR-related permissions
        $hrPerms = [
            'employees.view', 'employees.create', 'employees.edit', 'employees.export',
            'payroll.view', 'payroll.create', 'payroll.edit', 'payroll.export',
            'leave.view', 'leave.create', 'leave.approve', 'leave.manage',
            'time.view', 'time.create', 'time.edit', 'time.approve',
            'benefits.view', 'benefits.manage', 'benefits.enroll',
            'loans.view', 'loans.create', 'loans.approve',
            'performance.view', 'performance.create', 'performance.edit',
            'hiring.view', 'hiring.manage',
            'disciplinary.view', 'disciplinary.create', 'disciplinary.manage',
            'terminations.view', 'terminations.create',
            'documents.view', 'documents.upload', 'documents.delete',
            'reports.view', 'reports.export',
        ];

        // Manager gets team-related permissions
        $managerPerms = [
            'employees.view',
            'leave.view', 'leave.approve',
            'time.view', 'time.approve',
            'performance.view', 'performance.create',
            'disciplinary.view', 'disciplinary.create',
            'documents.view',
            'reports.view',
        ];

        // Employee gets self-service permissions
        $employeePerms = [
            'leave.view', 'leave.create',
            'time.view', 'time.create',
            'benefits.view', 'benefits.enroll',
            'loans.view', 'loans.create',
            'performance.view',
            'documents.view', 'documents.upload',
        ];

        $rolePermissions = [];
        $now = now();

        foreach ($superAdminPerms as $permName) {
            if (isset($allPermissions[$permName])) {
                $rolePermissions[] = ['role' => 'super_admin', 'permission_id' => $allPermissions[$permName], 'created_at' => $now, 'updated_at' => $now];
            }
        }

        foreach ($adminPerms as $permName) {
            if (isset($allPermissions[$permName])) {
                $rolePermissions[] = ['role' => 'admin', 'permission_id' => $allPermissions[$permName], 'created_at' => $now, 'updated_at' => $now];
            }
        }

        foreach ($hrPerms as $permName) {
            if (isset($allPermissions[$permName])) {
                $rolePermissions[] = ['role' => 'hr', 'permission_id' => $allPermissions[$permName], 'created_at' => $now, 'updated_at' => $now];
            }
        }

        foreach ($managerPerms as $permName) {
            if (isset($allPermissions[$permName])) {
                $rolePermissions[] = ['role' => 'manager', 'permission_id' => $allPermissions[$permName], 'created_at' => $now, 'updated_at' => $now];
            }
        }

        foreach ($employeePerms as $permName) {
            if (isset($allPermissions[$permName])) {
                $rolePermissions[] = ['role' => 'employee', 'permission_id' => $allPermissions[$permName], 'created_at' => $now, 'updated_at' => $now];
            }
        }

        DB::table('role_permissions')->insert($rolePermissions);
    }
};
