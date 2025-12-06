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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['Phone Screen', 'Video Call', 'In-Person', 'Panel', 'Technical', 'Final'])->default('In-Person');
            $table->dateTime('scheduled_at');
            $table->integer('duration_minutes')->default(60);
            $table->string('location')->nullable(); // Physical location or video link
            $table->text('notes')->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['Scheduled', 'Completed', 'Cancelled', 'No Show', 'Rescheduled'])->default('Scheduled');
            $table->enum('outcome', ['Pending', 'Pass', 'Fail', 'On Hold'])->default('Pending');
            $table->integer('rating')->nullable(); // 1-5 rating
            $table->json('interviewers')->nullable(); // Array of interviewer user IDs
            $table->text('questions_asked')->nullable();
            $table->text('candidate_questions')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
