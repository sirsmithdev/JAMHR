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
        // Leave Types (Jamaica-specific)
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->integer('default_days_per_year')->default(0);
            $table->boolean('is_paid')->default(true);
            $table->boolean('requires_approval')->default(true);
            $table->boolean('requires_documentation')->default(false);
            $table->string('accrual_method')->default('annual'); // annual, monthly, per_period, tenure_based
            $table->decimal('accrual_rate', 8, 4)->nullable(); // Days per period
            $table->boolean('can_carry_over')->default(false);
            $table->integer('max_carry_over_days')->nullable();
            $table->integer('carry_over_expiry_months')->nullable();
            $table->integer('min_service_days')->default(0); // Eligibility requirement
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Employee Leave Balances
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('leave_type_id')->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->decimal('entitled_days', 8, 2)->default(0);
            $table->decimal('accrued_days', 8, 2)->default(0);
            $table->decimal('used_days', 8, 2)->default(0);
            $table->decimal('pending_days', 8, 2)->default(0);
            $table->decimal('carried_over_days', 8, 2)->default(0);
            $table->decimal('adjustment_days', 8, 2)->default(0);
            $table->decimal('available_days', 8, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'leave_type_id', 'year']);
        });

        // Jamaica Public Holidays
        Schema::create('public_holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->integer('year');
            $table->boolean('is_observed')->default(true); // In lieu day
            $table->date('observed_date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['name', 'year']);
        });

        // Maternity/Paternity Leave Tracking
        Schema::create('parental_leave_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('leave_request_id')->nullable()->constrained()->onDelete('set null');
            $table->string('leave_type'); // maternity, paternity, adoption
            $table->date('expected_date'); // Due date or adoption date
            $table->date('actual_date')->nullable();
            $table->date('leave_start_date');
            $table->date('leave_end_date');
            $table->integer('total_days');
            $table->integer('paid_days');
            $table->integer('unpaid_days')->default(0);
            $table->decimal('pay_rate', 5, 2)->default(100); // Percentage of salary
            $table->string('status')->default('pending'); // pending, active, completed
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Update leave_requests table if it exists
        if (Schema::hasTable('leave_requests')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('leave_requests', 'leave_type_id')) {
                    $table->foreignId('leave_type_id')->nullable()->after('employee_id')->constrained()->onDelete('set null');
                }
                if (!Schema::hasColumn('leave_requests', 'half_day')) {
                    $table->boolean('half_day')->default(false)->after('days_requested');
                }
                if (!Schema::hasColumn('leave_requests', 'half_day_period')) {
                    $table->string('half_day_period')->nullable()->after('half_day'); // morning, afternoon
                }
                if (!Schema::hasColumn('leave_requests', 'document_path')) {
                    $table->string('document_path')->nullable()->after('notes');
                }
                if (!Schema::hasColumn('leave_requests', 'covers_holiday')) {
                    $table->boolean('covers_holiday')->default(false)->after('document_path');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('leave_requests')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                $columns = ['leave_type_id', 'half_day', 'half_day_period', 'document_path', 'covers_holiday'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('leave_requests', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        Schema::dropIfExists('parental_leave_records');
        Schema::dropIfExists('public_holidays');
        Schema::dropIfExists('leave_balances');
        Schema::dropIfExists('leave_types');
    }
};
