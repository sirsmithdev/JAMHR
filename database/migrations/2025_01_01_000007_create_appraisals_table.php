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
        Schema::create('appraisals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->string('cycle', 20); // Q1, Q2, Q3, Q4, Annual
            $table->json('score_competency')->nullable(); // Job knowledge, Quality, Communication
            $table->json('score_goals')->nullable();
            $table->decimal('rating_overall', 2, 1)->nullable(); // 1.0 - 5.0
            $table->integer('goals_met_percentage')->nullable();
            $table->text('manager_comments')->nullable();
            $table->enum('status', ['draft', 'needs_review', 'completed'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appraisals');
    }
};
