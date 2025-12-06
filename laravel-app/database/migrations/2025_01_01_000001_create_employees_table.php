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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('job_title', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('trn_number', 20)->nullable(); // Tax Registration Number
            $table->string('nis_number', 20)->nullable(); // National Insurance Scheme
            $table->string('pin', 4)->nullable(); // For kiosk clock in/out
            $table->date('start_date')->nullable();
            $table->decimal('salary_annual', 15, 2)->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->integer('vacation_days_total')->default(15);
            $table->integer('vacation_days_used')->default(0);
            $table->integer('sick_days_total')->default(10);
            $table->integer('sick_days_used')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
