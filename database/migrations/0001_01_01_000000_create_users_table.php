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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('client')->index(); // 'admin', 'provider', 'client'
            $table->string('phone_number')->nullable();
            $table->string('gender')->nullable(); // 'male', 'female', 'other'
            $table->text('address')->nullable();
            $table->text('profile_url')->nullable()->default('https://iter-bene.s3.eu-north-1.amazonaws.com/uploads/users/ea97d957-1bf9-4e4a-b84c-f00525e0cca3.webp');
            $table->string('service_name')->nullable();
            $table->string('service_lisence')->nullable();
            $table->string('status')->default('active')->index(); // 'pending', 'active', 'inactive', 'suspended', 'deleted'
            $table->boolean('is_verified')->default(false)->index();
            $table->boolean('is_deleted')->default(false)->index();
            $table->boolean('is_profile_completed')->default(false);
            $table->string('provider')->nullable()->index(); // 'google', 'apple'
            $table->string('provider_id')->nullable()->index();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
