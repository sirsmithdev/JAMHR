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
        // Pension Plans (extends benefit_plans)
        Schema::create('pension_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benefit_plan_id')->constrained()->onDelete('cascade');
            $table->string('plan_type'); // defined_benefit, defined_contribution, hybrid
            $table->decimal('employer_match_percentage', 5, 2)->default(0);
            $table->decimal('employer_match_cap', 12, 2)->nullable();
            $table->decimal('vesting_years', 4, 2)->default(0);
            $table->string('vesting_schedule')->default('cliff'); // cliff, graded
            $table->json('vesting_percentages')->nullable();
            $table->integer('minimum_retirement_age')->default(65);
            $table->integer('early_retirement_age')->nullable();
            $table->text('investment_options')->nullable();
            $table->timestamps();
        });

        // Employee Pension Accounts
        Schema::create('pension_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('benefit_enrollment_id')->constrained()->onDelete('cascade');
            $table->string('account_number')->unique();
            $table->decimal('employee_ytd_contributions', 14, 2)->default(0);
            $table->decimal('employer_ytd_contributions', 14, 2)->default(0);
            $table->decimal('total_balance', 14, 2)->default(0);
            $table->decimal('vested_balance', 14, 2)->default(0);
            $table->decimal('vesting_percentage', 5, 2)->default(0);
            $table->date('vesting_date')->nullable();
            $table->json('investment_allocation')->nullable();
            $table->timestamps();
        });

        // Pension Contributions History
        Schema::create('pension_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pension_account_id')->constrained()->onDelete('cascade');
            $table->foreignId('payroll_id')->nullable()->constrained()->onDelete('set null');
            $table->date('contribution_date');
            $table->string('contribution_type'); // employee, employer, voluntary
            $table->decimal('amount', 12, 2);
            $table->decimal('running_balance', 14, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // NIS Contribution Tracking (Jamaica-specific)
        Schema::create('nis_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('payroll_id')->nullable()->constrained()->onDelete('set null');
            $table->date('contribution_date');
            $table->decimal('employee_contribution', 12, 2);
            $table->decimal('employer_contribution', 12, 2);
            $table->decimal('insurable_earnings', 12, 2);
            $table->integer('weeks_credited')->default(1);
            $table->integer('ytd_weeks')->default(0);
            $table->decimal('ytd_employee_contributions', 14, 2)->default(0);
            $table->decimal('ytd_employer_contributions', 14, 2)->default(0);
            $table->timestamps();
        });

        // Beneficiary Designations
        Schema::create('pension_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pension_account_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('relationship');
            $table->date('date_of_birth')->nullable();
            $table->string('trn')->nullable();
            $table->decimal('percentage', 5, 2);
            $table->string('type')->default('primary'); // primary, contingent
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pension_beneficiaries');
        Schema::dropIfExists('nis_contributions');
        Schema::dropIfExists('pension_contributions');
        Schema::dropIfExists('pension_accounts');
        Schema::dropIfExists('pension_plans');
    }
};
