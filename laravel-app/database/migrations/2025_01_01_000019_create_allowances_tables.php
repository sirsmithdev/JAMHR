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
        // Allowance Types
        Schema::create('allowance_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('category'); // transport, meal, phone, housing, motor_vehicle, uniform, other
            $table->text('description')->nullable();
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_fixed')->default(true); // Fixed vs variable amount
            $table->decimal('default_amount', 12, 2)->default(0);
            $table->string('frequency')->default('monthly'); // monthly, bi-weekly, daily, per_diem
            $table->decimal('tax_threshold', 12, 2)->nullable(); // Amount before taxation applies
            $table->boolean('requires_receipts')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Employee Allowances
        Schema::create('employee_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('allowance_type_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('frequency')->default('monthly');
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('active'); // active, suspended, terminated
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Company Vehicles / Motor Vehicle Benefit
        Schema::create('company_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('set null');
            $table->string('registration_number')->unique();
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->decimal('original_cost', 12, 2);
            $table->decimal('current_value', 12, 2)->nullable();
            $table->date('acquisition_date');
            $table->date('assignment_date')->nullable();
            $table->integer('private_use_percentage')->default(50); // For taxable benefit
            $table->decimal('annual_taxable_benefit', 12, 2)->default(0);
            $table->decimal('monthly_taxable_benefit', 12, 2)->default(0);
            $table->string('fuel_card_number')->nullable();
            $table->decimal('monthly_fuel_limit', 10, 2)->nullable();
            $table->string('status')->default('available'); // available, assigned, maintenance, disposed
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Allowance Payments (historical tracking)
        Schema::create('allowance_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_allowance_id')->constrained()->onDelete('cascade');
            $table->foreignId('payroll_id')->nullable()->constrained()->onDelete('set null');
            $table->date('payment_date');
            $table->decimal('amount', 12, 2);
            $table->decimal('taxable_amount', 12, 2)->default(0);
            $table->string('receipt_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowance_payments');
        Schema::dropIfExists('company_vehicles');
        Schema::dropIfExists('employee_allowances');
        Schema::dropIfExists('allowance_types');
    }
};
