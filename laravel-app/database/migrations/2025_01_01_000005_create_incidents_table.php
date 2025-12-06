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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type', 50);
            $table->enum('severity', ['low', 'medium', 'high']);
            $table->text('description');
            $table->string('location', 255)->nullable();
            $table->text('witnesses')->nullable();
            $table->dateTime('occurred_at');
            $table->enum('status', ['open', 'investigating', 'resolved'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
