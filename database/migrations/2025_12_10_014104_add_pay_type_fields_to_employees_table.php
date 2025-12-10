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
        Schema::table('employees', function (Blueprint $table) {
            // Pay frequency: fortnightly or monthly (can override company default)
            $table->enum('pay_frequency', ['fortnightly', 'monthly'])->default('monthly')->after('hourly_rate');

            // Pay type: how the employee is paid
            // - salaried: paid based on monthly salary
            // - hourly_from_salary: hourly rate calculated from monthly salary
            // - hourly_fixed: fixed hourly rate (flexi-hour)
            $table->enum('pay_type', ['salaried', 'hourly_from_salary', 'hourly_fixed'])->default('salaried')->after('pay_frequency');

            // Flexi-hour rate (used when pay_type is 'hourly_fixed')
            $table->decimal('flexi_hourly_rate', 10, 2)->nullable()->after('pay_type');

            // Standard hours per pay period (for calculating hourly rate from salary)
            // Monthly: typically 173.33 (40 hrs/week × 52 weeks / 12 months)
            // Fortnightly: typically 80 (40 hrs/week × 2 weeks)
            $table->decimal('standard_hours_per_period', 8, 2)->nullable()->after('flexi_hourly_rate');

            // Track when rates became effective
            $table->date('rate_effective_date')->nullable()->after('standard_hours_per_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'pay_frequency',
                'pay_type',
                'flexi_hourly_rate',
                'standard_hours_per_period',
                'rate_effective_date',
            ]);
        });
    }
};
