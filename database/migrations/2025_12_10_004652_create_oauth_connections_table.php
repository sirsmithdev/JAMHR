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
        Schema::create('oauth_connections', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); // google, microsoft, slack
            $table->string('provider_user_id')->nullable(); // ID from provider
            $table->string('email')->nullable(); // Connected account email
            $table->text('access_token');
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->json('scopes')->nullable(); // Granted scopes
            $table->json('metadata')->nullable(); // Additional provider data
            $table->boolean('is_active')->default(true);
            $table->foreignId('connected_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['provider']); // One connection per provider (single tenant)
            // For multi-tenant: $table->unique(['provider', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_connections');
    }
};
