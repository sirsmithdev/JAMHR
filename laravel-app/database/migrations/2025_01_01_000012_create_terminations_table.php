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
        Schema::create('terminations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->enum('type', [
                'Resignation',
                'Termination',
                'Redundancy',
                'End of Contract',
                'Retirement',
                'Mutual Agreement',
                'Dismissal',
                'Death'
            ]);
            $table->date('notice_date');
            $table->date('last_working_day');
            $table->text('reason')->nullable();
            $table->text('exit_interview_notes')->nullable();
            $table->boolean('exit_interview_completed')->default(false);
            $table->date('exit_interview_date')->nullable();

            // Clearance checklist
            $table->boolean('company_property_returned')->default(false);
            $table->boolean('access_revoked')->default(false);
            $table->boolean('final_pay_processed')->default(false);
            $table->boolean('benefits_terminated')->default(false);
            $table->boolean('knowledge_transfer_complete')->default(false);

            // Final pay details
            $table->decimal('final_salary', 12, 2)->nullable();
            $table->decimal('unused_leave_payout', 12, 2)->nullable();
            $table->decimal('severance_pay', 12, 2)->nullable();
            $table->decimal('other_payments', 12, 2)->nullable();
            $table->decimal('deductions', 12, 2)->nullable();
            $table->decimal('total_final_pay', 12, 2)->nullable();

            // Jamaica-specific
            $table->boolean('nht_clearance')->default(false);
            $table->boolean('nis_updated')->default(false);
            $table->boolean('tax_forms_issued')->default(false);

            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Cancelled'])->default('Pending');
            $table->boolean('eligible_for_rehire')->default(true);
            $table->text('rehire_notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminations');
    }
};
