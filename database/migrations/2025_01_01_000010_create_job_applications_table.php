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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('resume_path')->nullable();
            $table->string('cover_letter_path')->nullable();
            $table->text('cover_letter_text')->nullable();
            $table->decimal('expected_salary', 12, 2)->nullable();
            $table->date('available_start_date')->nullable();
            $table->text('experience_summary')->nullable();
            $table->text('education')->nullable();
            $table->text('skills')->nullable();
            $table->text('references')->nullable();
            $table->enum('status', [
                'New',
                'Reviewing',
                'Phone Screen',
                'Interview Scheduled',
                'Interviewed',
                'Under Consideration',
                'Offer Extended',
                'Offer Accepted',
                'Offer Declined',
                'Hired',
                'Rejected',
                'Withdrawn'
            ])->default('New');
            $table->text('notes')->nullable();
            $table->integer('rating')->nullable(); // 1-5 rating
            $table->string('source')->nullable(); // Where they heard about the job
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
