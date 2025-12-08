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
        // Benefit Plans (catalog of available benefits)
        Schema::create('benefit_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // health, pension, life_insurance, other
            $table->string('provider')->nullable();
            $table->text('description')->nullable();
            $table->decimal('employee_contribution', 12, 2)->default(0);
            $table->decimal('employer_contribution', 12, 2)->default(0);
            $table->string('contribution_frequency')->default('monthly'); // monthly, bi-weekly, annual
            $table->decimal('coverage_amount', 12, 2)->nullable();
            $table->json('coverage_details')->nullable();
            $table->date('effective_date');
            $table->date('termination_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_enrollment')->default(true);
            $table->integer('waiting_period_days')->default(0);
            $table->timestamps();
        });

        // Enrollment Periods
        Schema::create('enrollment_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('open'); // open, special, new_hire
            $table->date('start_date');
            $table->date('end_date');
            $table->date('effective_date');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Employee Benefit Enrollments
        Schema::create('benefit_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('benefit_plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('enrollment_period_id')->nullable()->constrained()->onDelete('set null');
            $table->string('status')->default('pending'); // pending, active, cancelled, terminated
            $table->date('enrollment_date');
            $table->date('effective_date');
            $table->date('termination_date')->nullable();
            $table->decimal('employee_contribution', 12, 2)->default(0);
            $table->decimal('employer_contribution', 12, 2)->default(0);
            $table->string('coverage_level')->nullable(); // employee_only, employee_spouse, family
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // Dependents for benefits
        Schema::create('benefit_dependents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('benefit_enrollment_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('relationship'); // spouse, child, domestic_partner
            $table->date('date_of_birth');
            $table->string('gender')->nullable();
            $table->string('trn')->nullable(); // Tax Registration Number
            $table->boolean('is_student')->default(false);
            $table->boolean('is_disabled')->default(false);
            $table->string('document_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('benefit_dependents');
        Schema::dropIfExists('benefit_enrollments');
        Schema::dropIfExists('enrollment_periods');
        Schema::dropIfExists('benefit_plans');
    }
};
