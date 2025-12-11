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
        Schema::table('time_entries', function (Blueprint $table) {
            $table->string('clock_in_photo')->nullable()->after('clock_in');
            $table->string('clock_out_photo')->nullable()->after('clock_out');
            $table->string('ip_address')->nullable()->after('status');
            $table->string('device_info')->nullable()->after('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_entries', function (Blueprint $table) {
            $table->dropColumn(['clock_in_photo', 'clock_out_photo', 'ip_address', 'device_info']);
        });
    }
};
