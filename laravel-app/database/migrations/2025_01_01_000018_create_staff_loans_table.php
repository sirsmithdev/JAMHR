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
        // Loan Types Configuration
        Schema::create('loan_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('interest_rate', 5, 2)->default(0); // Concessionary rate
            $table->decimal('market_rate', 5, 2)->default(9); // For tax benefit calculation
            $table->decimal('min_amount', 12, 2)->default(0);
            $table->decimal('max_amount', 12, 2)->nullable();
            $table->integer('min_term_months')->default(1);
            $table->integer('max_term_months')->default(60);
            $table->integer('min_employment_months')->default(12); // Eligibility requirement
            $table->boolean('requires_guarantor')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Staff Loans
        Schema::create('staff_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('loan_type_id')->constrained()->onDelete('restrict');
            $table->string('loan_number')->unique();
            $table->decimal('principal_amount', 12, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->decimal('market_rate', 5, 2)->default(9);
            $table->integer('term_months');
            $table->decimal('monthly_payment', 12, 2);
            $table->decimal('total_interest', 12, 2);
            $table->decimal('total_repayment', 12, 2);
            $table->decimal('outstanding_balance', 12, 2);
            $table->decimal('taxable_benefit', 12, 2)->default(0); // Monthly taxable benefit
            $table->date('application_date');
            $table->date('approval_date')->nullable();
            $table->date('disbursement_date')->nullable();
            $table->date('first_payment_date')->nullable();
            $table->date('maturity_date')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, active, paid_off, defaulted
            $table->text('purpose')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('guarantor_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->timestamps();
        });

        // Loan Repayments
        Schema::create('loan_repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_loan_id')->constrained()->onDelete('cascade');
            $table->foreignId('payroll_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('payment_number');
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->decimal('scheduled_amount', 12, 2);
            $table->decimal('principal_amount', 12, 2);
            $table->decimal('interest_amount', 12, 2);
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->decimal('balance_after', 12, 2);
            $table->string('status')->default('scheduled'); // scheduled, paid, partial, overdue, waived
            $table->string('payment_method')->nullable(); // payroll_deduction, cash, bank_transfer
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Loan Documents
        Schema::create('loan_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_loan_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // application, agreement, guarantor_form, pay_stub
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_documents');
        Schema::dropIfExists('loan_repayments');
        Schema::dropIfExists('staff_loans');
        Schema::dropIfExists('loan_types');
    }
};
