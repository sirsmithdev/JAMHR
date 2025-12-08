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
        Schema::create('disciplinary_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('incident_date');
            $table->date('action_date');
            $table->enum('type', [
                'Verbal Warning',
                'Written Warning',
                'Final Written Warning',
                'Suspension',
                'Demotion',
                'Termination',
                'Performance Improvement Plan'
            ]);
            $table->enum('category', [
                'Attendance',
                'Performance',
                'Conduct',
                'Policy Violation',
                'Insubordination',
                'Harassment',
                'Safety Violation',
                'Theft',
                'Substance Abuse',
                'Other'
            ]);
            $table->text('description');
            $table->text('evidence')->nullable();
            $table->text('employee_response')->nullable();
            $table->text('corrective_action')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->text('follow_up_notes')->nullable();

            // For suspensions
            $table->date('suspension_start')->nullable();
            $table->date('suspension_end')->nullable();
            $table->boolean('with_pay')->nullable();

            // For PIPs
            $table->date('pip_start_date')->nullable();
            $table->date('pip_end_date')->nullable();
            $table->text('pip_goals')->nullable();
            $table->enum('pip_outcome', ['Pending', 'Successful', 'Failed', 'Extended'])->nullable();

            // Witnesses and documentation
            $table->text('witnesses')->nullable();
            $table->string('document_path')->nullable();
            $table->boolean('employee_acknowledged')->default(false);
            $table->timestamp('acknowledged_at')->nullable();
            $table->boolean('union_representative_present')->default(false);
            $table->string('union_representative_name')->nullable();

            $table->enum('status', ['Open', 'Under Review', 'Resolved', 'Appealed', 'Overturned'])->default('Open');
            $table->text('appeal_notes')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disciplinary_actions');
    }
};
