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
        // Education Programs (approved courses/institutions)
        Schema::create('education_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('institution');
            $table->string('program_type'); // degree, certificate, diploma, professional, workshop
            $table->text('description')->nullable();
            $table->decimal('max_reimbursement', 12, 2)->nullable();
            $table->integer('duration_months')->nullable();
            $table->boolean('requires_grade_minimum')->default(false);
            $table->string('minimum_grade')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Education Assistance Requests
        Schema::create('education_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('education_program_id')->nullable()->constrained()->onDelete('set null');
            $table->string('request_number')->unique();
            $table->string('institution');
            $table->string('program_name');
            $table->string('program_type');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('total_cost', 12, 2);
            $table->decimal('requested_amount', 12, 2);
            $table->decimal('approved_amount', 12, 2)->nullable();
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->string('status')->default('pending'); // pending, approved, rejected, in_progress, completed, cancelled
            $table->text('justification')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->boolean('repayment_required')->default(false);
            $table->integer('service_commitment_months')->nullable(); // Required service after completion
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // Education Reimbursements
        Schema::create('education_reimbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_request_id')->constrained()->onDelete('cascade');
            $table->date('submission_date');
            $table->string('expense_type'); // tuition, books, exam_fees, materials
            $table->text('description');
            $table->decimal('amount', 12, 2);
            $table->decimal('approved_amount', 12, 2)->nullable();
            $table->string('receipt_path')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, paid
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Grade/Completion Records
        Schema::create('education_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_request_id')->constrained()->onDelete('cascade');
            $table->string('course_name');
            $table->string('semester')->nullable();
            $table->string('grade')->nullable();
            $table->decimal('grade_points', 4, 2)->nullable();
            $table->boolean('passed')->default(true);
            $table->date('completion_date')->nullable();
            $table->string('certificate_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Employee Education History (for tracking qualifications)
        Schema::create('employee_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('education_request_id')->nullable()->constrained()->onDelete('set null');
            $table->string('qualification_type'); // degree, certificate, diploma, license, certification
            $table->string('title');
            $table->string('institution');
            $table->string('field_of_study')->nullable();
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->date('expiry_date')->nullable(); // For licenses/certifications
            $table->string('grade')->nullable();
            $table->string('document_path')->nullable();
            $table->boolean('company_sponsored')->default(false);
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_qualifications');
        Schema::dropIfExists('education_grades');
        Schema::dropIfExists('education_reimbursements');
        Schema::dropIfExists('education_requests');
        Schema::dropIfExists('education_programs');
    }
};
