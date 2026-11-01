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
        Schema::create('temp_users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('client'); // 'client' or 'provider'
            $table->string('phone_number')->nullable();
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            $table->text('profile_url')->nullable()->default('https://iter-bene.s3.eu-north-1.amazonaws.com/uploads/users/ea97d957-1bf9-4e4a-b84c-f00525e0cca3.webp');
            $table->string('service_name')->nullable();
            $table->string('service_lisence')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_profile_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_users');
    }
};
