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
        Schema::table('payrolls', function (Blueprint $table) {
            // Pay frequency for this payroll period
            $table->enum('pay_frequency', ['fortnightly', 'monthly'])->default('monthly')->after('period_end');

            // Pay type used for calculation (copied from employee at time of payroll)
            $table->enum('pay_type', ['salaried', 'hourly_from_salary', 'hourly_fixed'])->default('salaried')->after('pay_frequency');

            // Hours tracking for hourly employees
            $table->decimal('hours_worked', 8, 2)->nullable()->after('pay_type');
            $table->decimal('hourly_rate_used', 10, 2)->nullable()->after('hours_worked');

            // Regular hours (standard) vs overtime split
            $table->decimal('regular_hours', 8, 2)->nullable()->after('hourly_rate_used');
            $table->decimal('regular_pay', 12, 2)->nullable()->after('regular_hours');

            // Calculation notes/breakdown (JSON for flexibility)
            $table->json('calculation_breakdown')->nullable()->after('other_deductions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'pay_frequency',
                'pay_type',
                'hours_worked',
                'hourly_rate_used',
                'regular_hours',
                'regular_pay',
                'calculation_breakdown',
            ]);
        });
    }
};
