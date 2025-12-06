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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('gross_pay', 15, 2);

            // Jamaican Statutory Deductions
            $table->decimal('nht_employee', 10, 2);  // 2%
            $table->decimal('nht_employer', 10, 2);  // 3%
            $table->decimal('nis_employee', 10, 2);  // 3%
            $table->decimal('nis_employer', 10, 2);  // 3%
            $table->decimal('ed_tax_employee', 10, 2);  // 2.25%
            $table->decimal('ed_tax_employer', 10, 2);  // 3.5%
            $table->decimal('heart_employer', 10, 2);   // 3% (Employer only)
            $table->decimal('income_tax', 10, 2);       // PAYE

            $table->decimal('net_pay', 15, 2);
            $table->enum('status', ['draft', 'finalized', 'paid'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
