<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application with an admin account.
     */
    public function run(): void
    {
        // Check if admin already exists
        if (User::where('email', 'admin@jamhr.com')->exists()) {
            $this->command->info('Admin account already exists.');
            return;
        }

        // Create admin user
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@jamhr.com',
            'password' => Hash::make('Admin@123!'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create associated employee record
        Employee::create([
            'user_id' => $admin->id,
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'job_title' => 'System Administrator',
            'department' => 'IT',
            'start_date' => now(),
            'pay_frequency' => 'monthly',
            'pay_type' => 'salaried',
            'pin' => '0000',
        ]);

        $this->command->info('Admin account created successfully.');
        $this->command->info('Email: admin@jamhr.com');
        $this->command->info('Password: Admin@123!');
        $this->command->warn('Please change the password after first login!');
    }
}
