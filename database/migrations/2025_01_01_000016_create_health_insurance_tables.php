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
        // Health Insurance Plans (extends benefit_plans)
        Schema::create('health_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benefit_plan_id')->constrained()->onDelete('cascade');
            $table->string('tier'); // basic, standard, premium
            $table->decimal('annual_deductible', 12, 2)->default(0);
            $table->decimal('annual_max_coverage', 12, 2)->nullable();
            $table->decimal('copay_doctor', 10, 2)->default(0);
            $table->decimal('copay_specialist', 10, 2)->default(0);
            $table->decimal('copay_emergency', 10, 2)->default(0);
            $table->integer('coinsurance_percentage')->default(80);
            $table->boolean('includes_dental')->default(false);
            $table->boolean('includes_vision')->default(false);
            $table->boolean('includes_prescription')->default(true);
            $table->json('network_providers')->nullable();
            $table->text('exclusions')->nullable();
            $table->timestamps();
        });

        // Health Insurance Claims
        Schema::create('health_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('benefit_enrollment_id')->constrained()->onDelete('cascade');
            $table->string('claim_number')->unique();
            $table->date('service_date');
            $table->date('submission_date');
            $table->string('provider_name');
            $table->string('claim_type'); // medical, dental, vision, prescription
            $table->text('description');
            $table->decimal('amount_claimed', 12, 2);
            $table->decimal('amount_approved', 12, 2)->nullable();
            $table->decimal('amount_paid', 12, 2)->nullable();
            $table->decimal('employee_responsibility', 12, 2)->nullable();
            $table->string('status')->default('submitted'); // submitted, under_review, approved, denied, paid
            $table->text('denial_reason')->nullable();
            $table->string('receipt_path')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_claims');
        Schema::dropIfExists('health_plans');
    }
};
