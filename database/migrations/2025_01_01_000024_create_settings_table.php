<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->index(); // company, payroll, leave, notifications, system
            $table->string('key')->index();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json, float
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['group', 'key']);
        });

        // Seed default settings
        $this->seedDefaultSettings();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }

    /**
     * Seed default settings
     */
    private function seedDefaultSettings(): void
    {
        $settings = [
            // Company Settings
            ['group' => 'company', 'key' => 'name', 'value' => 'My Company', 'type' => 'string', 'description' => 'Company name'],
            ['group' => 'company', 'key' => 'legal_name', 'value' => '', 'type' => 'string', 'description' => 'Legal company name'],
            ['group' => 'company', 'key' => 'registration_number', 'value' => '', 'type' => 'string', 'description' => 'Company registration number'],
            ['group' => 'company', 'key' => 'tax_registration_number', 'value' => '', 'type' => 'string', 'description' => 'Tax Registration Number (TRN)'],
            ['group' => 'company', 'key' => 'nis_number', 'value' => '', 'type' => 'string', 'description' => 'NIS Employer Number'],
            ['group' => 'company', 'key' => 'nht_number', 'value' => '', 'type' => 'string', 'description' => 'NHT Employer Number'],
            ['group' => 'company', 'key' => 'address_line_1', 'value' => '', 'type' => 'string', 'description' => 'Address line 1'],
            ['group' => 'company', 'key' => 'address_line_2', 'value' => '', 'type' => 'string', 'description' => 'Address line 2'],
            ['group' => 'company', 'key' => 'city', 'value' => '', 'type' => 'string', 'description' => 'City'],
            ['group' => 'company', 'key' => 'parish', 'value' => '', 'type' => 'string', 'description' => 'Parish'],
            ['group' => 'company', 'key' => 'country', 'value' => 'Jamaica', 'type' => 'string', 'description' => 'Country'],
            ['group' => 'company', 'key' => 'phone', 'value' => '', 'type' => 'string', 'description' => 'Phone number'],
            ['group' => 'company', 'key' => 'email', 'value' => '', 'type' => 'string', 'description' => 'Company email'],
            ['group' => 'company', 'key' => 'website', 'value' => '', 'type' => 'string', 'description' => 'Website URL'],
            ['group' => 'company', 'key' => 'logo', 'value' => '', 'type' => 'string', 'description' => 'Company logo path'],
            ['group' => 'company', 'key' => 'fiscal_year_start', 'value' => '01-01', 'type' => 'string', 'description' => 'Fiscal year start (MM-DD)'],

            // Payroll Settings
            ['group' => 'payroll', 'key' => 'pay_frequency', 'value' => 'monthly', 'type' => 'string', 'description' => 'Pay frequency (weekly, bi-weekly, semi-monthly, monthly)'],
            ['group' => 'payroll', 'key' => 'pay_day', 'value' => 'last', 'type' => 'string', 'description' => 'Pay day (1-31, last, or day name for weekly)'],
            ['group' => 'payroll', 'key' => 'currency', 'value' => 'JMD', 'type' => 'string', 'description' => 'Currency code'],
            ['group' => 'payroll', 'key' => 'currency_symbol', 'value' => '$', 'type' => 'string', 'description' => 'Currency symbol'],
            ['group' => 'payroll', 'key' => 'overtime_rate', 'value' => '1.5', 'type' => 'float', 'description' => 'Overtime rate multiplier'],
            ['group' => 'payroll', 'key' => 'double_time_rate', 'value' => '2.0', 'type' => 'float', 'description' => 'Double time rate multiplier'],
            ['group' => 'payroll', 'key' => 'standard_hours_per_week', 'value' => '40', 'type' => 'integer', 'description' => 'Standard hours per week'],
            ['group' => 'payroll', 'key' => 'paye_threshold_annual', 'value' => '1500096', 'type' => 'float', 'description' => 'PAYE annual tax threshold (JMD)'],
            ['group' => 'payroll', 'key' => 'paye_rate', 'value' => '0.25', 'type' => 'float', 'description' => 'PAYE tax rate (25%)'],
            ['group' => 'payroll', 'key' => 'paye_rate_higher', 'value' => '0.30', 'type' => 'float', 'description' => 'PAYE higher tax rate (30%)'],
            ['group' => 'payroll', 'key' => 'paye_higher_threshold', 'value' => '6000000', 'type' => 'float', 'description' => 'PAYE higher rate threshold'],
            ['group' => 'payroll', 'key' => 'nis_rate_employee', 'value' => '0.03', 'type' => 'float', 'description' => 'NIS employee rate (3%)'],
            ['group' => 'payroll', 'key' => 'nis_rate_employer', 'value' => '0.03', 'type' => 'float', 'description' => 'NIS employer rate (3%)'],
            ['group' => 'payroll', 'key' => 'nis_ceiling_weekly', 'value' => '5000', 'type' => 'float', 'description' => 'NIS weekly wage ceiling'],
            ['group' => 'payroll', 'key' => 'nht_rate_employee', 'value' => '0.02', 'type' => 'float', 'description' => 'NHT employee rate (2%)'],
            ['group' => 'payroll', 'key' => 'nht_rate_employer', 'value' => '0.03', 'type' => 'float', 'description' => 'NHT employer rate (3%)'],
            ['group' => 'payroll', 'key' => 'education_tax_rate', 'value' => '0.0225', 'type' => 'float', 'description' => 'Education tax rate (2.25%)'],
            ['group' => 'payroll', 'key' => 'heart_rate', 'value' => '0.03', 'type' => 'float', 'description' => 'HEART/NSTA contribution rate (3%)'],
            ['group' => 'payroll', 'key' => 'auto_process_payroll', 'value' => 'false', 'type' => 'boolean', 'description' => 'Automatically process payroll on pay day'],

            // Leave Settings
            ['group' => 'leave', 'key' => 'annual_leave_days', 'value' => '14', 'type' => 'integer', 'description' => 'Default annual vacation days'],
            ['group' => 'leave', 'key' => 'sick_leave_days', 'value' => '10', 'type' => 'integer', 'description' => 'Default sick leave days per year'],
            ['group' => 'leave', 'key' => 'maternity_leave_weeks', 'value' => '12', 'type' => 'integer', 'description' => 'Maternity leave weeks'],
            ['group' => 'leave', 'key' => 'paternity_leave_days', 'value' => '5', 'type' => 'integer', 'description' => 'Paternity leave days'],
            ['group' => 'leave', 'key' => 'bereavement_leave_days', 'value' => '5', 'type' => 'integer', 'description' => 'Bereavement leave days'],
            ['group' => 'leave', 'key' => 'accrual_method', 'value' => 'annual', 'type' => 'string', 'description' => 'Leave accrual method (annual, monthly, bi-weekly)'],
            ['group' => 'leave', 'key' => 'carry_over_enabled', 'value' => 'true', 'type' => 'boolean', 'description' => 'Allow leave carry-over'],
            ['group' => 'leave', 'key' => 'carry_over_max_days', 'value' => '5', 'type' => 'integer', 'description' => 'Maximum carry-over days'],
            ['group' => 'leave', 'key' => 'carry_over_expiry_months', 'value' => '3', 'type' => 'integer', 'description' => 'Carry-over expiry (months into new year)'],
            ['group' => 'leave', 'key' => 'require_approval', 'value' => 'true', 'type' => 'boolean', 'description' => 'Require manager approval for leave'],
            ['group' => 'leave', 'key' => 'min_notice_days', 'value' => '3', 'type' => 'integer', 'description' => 'Minimum notice days for leave request'],
            ['group' => 'leave', 'key' => 'probation_leave_eligible', 'value' => 'false', 'type' => 'boolean', 'description' => 'Allow leave during probation'],
            ['group' => 'leave', 'key' => 'probation_period_months', 'value' => '3', 'type' => 'integer', 'description' => 'Probation period in months'],

            // Notification Settings
            ['group' => 'notifications', 'key' => 'email_enabled', 'value' => 'true', 'type' => 'boolean', 'description' => 'Enable email notifications'],
            ['group' => 'notifications', 'key' => 'leave_request_notify', 'value' => 'true', 'type' => 'boolean', 'description' => 'Notify on leave requests'],
            ['group' => 'notifications', 'key' => 'leave_approval_notify', 'value' => 'true', 'type' => 'boolean', 'description' => 'Notify on leave approval/rejection'],
            ['group' => 'notifications', 'key' => 'payroll_notify', 'value' => 'true', 'type' => 'boolean', 'description' => 'Notify when payroll is processed'],
            ['group' => 'notifications', 'key' => 'loan_notify', 'value' => 'true', 'type' => 'boolean', 'description' => 'Notify on loan application updates'],
            ['group' => 'notifications', 'key' => 'appraisal_notify', 'value' => 'true', 'type' => 'boolean', 'description' => 'Notify on performance appraisals'],
            ['group' => 'notifications', 'key' => 'birthday_notify', 'value' => 'true', 'type' => 'boolean', 'description' => 'Send birthday notifications'],
            ['group' => 'notifications', 'key' => 'anniversary_notify', 'value' => 'true', 'type' => 'boolean', 'description' => 'Send work anniversary notifications'],
            ['group' => 'notifications', 'key' => 'compliance_remind_days', 'value' => '7', 'type' => 'integer', 'description' => 'Days before compliance deadline to remind'],
            ['group' => 'notifications', 'key' => 'digest_frequency', 'value' => 'daily', 'type' => 'string', 'description' => 'Admin digest frequency (none, daily, weekly)'],

            // System Settings
            ['group' => 'system', 'key' => 'timezone', 'value' => 'America/Jamaica', 'type' => 'string', 'description' => 'System timezone'],
            ['group' => 'system', 'key' => 'date_format', 'value' => 'd/m/Y', 'type' => 'string', 'description' => 'Date format'],
            ['group' => 'system', 'key' => 'time_format', 'value' => 'H:i', 'type' => 'string', 'description' => 'Time format'],
            ['group' => 'system', 'key' => 'week_starts_on', 'value' => 'monday', 'type' => 'string', 'description' => 'First day of week'],
            ['group' => 'system', 'key' => 'session_timeout_minutes', 'value' => '60', 'type' => 'integer', 'description' => 'Session timeout in minutes'],
            ['group' => 'system', 'key' => 'password_min_length', 'value' => '8', 'type' => 'integer', 'description' => 'Minimum password length'],
            ['group' => 'system', 'key' => 'password_require_special', 'value' => 'true', 'type' => 'boolean', 'description' => 'Require special characters in password'],
            ['group' => 'system', 'key' => 'two_factor_enabled', 'value' => 'false', 'type' => 'boolean', 'description' => 'Enable two-factor authentication'],
            ['group' => 'system', 'key' => 'audit_log_enabled', 'value' => 'true', 'type' => 'boolean', 'description' => 'Enable audit logging'],
            ['group' => 'system', 'key' => 'audit_log_retention_days', 'value' => '365', 'type' => 'integer', 'description' => 'Audit log retention days'],
            ['group' => 'system', 'key' => 'backup_enabled', 'value' => 'true', 'type' => 'boolean', 'description' => 'Enable automatic backups'],
            ['group' => 'system', 'key' => 'backup_frequency', 'value' => 'daily', 'type' => 'string', 'description' => 'Backup frequency (daily, weekly)'],
            ['group' => 'system', 'key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'description' => 'Enable maintenance mode'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
};
