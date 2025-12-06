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
            // Additional earnings breakdown
            $table->decimal('basic_salary', 12, 2)->nullable()->after('gross_pay');
            $table->decimal('overtime_pay', 12, 2)->nullable()->after('basic_salary');
            $table->decimal('overtime_hours', 8, 2)->nullable()->after('overtime_pay');
            $table->decimal('allowances', 12, 2)->nullable()->after('overtime_hours');
            $table->decimal('bonus', 12, 2)->nullable()->after('allowances');
            $table->decimal('commission', 12, 2)->nullable()->after('bonus');
            $table->decimal('other_earnings', 12, 2)->nullable()->after('commission');

            // Additional deductions
            $table->decimal('loan_deduction', 12, 2)->nullable()->after('income_tax');
            $table->decimal('other_deductions', 12, 2)->nullable()->after('loan_deduction');

            // Pay date
            $table->date('pay_date')->nullable()->after('net_pay');

            // Payslip tracking
            $table->boolean('payslip_generated')->default(false)->after('status');
            $table->boolean('payslip_sent')->default(false)->after('payslip_generated');
            $table->timestamp('payslip_sent_at')->nullable()->after('payslip_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'basic_salary',
                'overtime_pay',
                'overtime_hours',
                'allowances',
                'bonus',
                'commission',
                'other_earnings',
                'loan_deduction',
                'other_deductions',
                'pay_date',
                'payslip_generated',
                'payslip_sent',
                'payslip_sent_at',
            ]);
        });
    }
};
