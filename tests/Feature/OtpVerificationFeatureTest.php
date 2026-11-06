<?php

namespace Tests\Feature;

use App\Models\EmailOtp;
use App\Models\TempUser;
use App\Models\User;
use App\Services\JwtService;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OtpVerificationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_verify_otp_and_activate_account(): void
    {
        $tempUser = TempUser::create([
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
            'password' => Hash::make('password123'),
            'role' => 'client',
            'status' => 'pending',
            'is_verified' => false,
        ]);

        $otpService = app(OtpService::class);
        $otp = $otpService->generateOtp('alice@example.com');

        $jwtService = app(JwtService::class);
        $token = $jwtService->generateVerificationToken($tempUser);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/verify-otp', [
                'otp' => $otp,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'statusCode' => 200,
                'success' => true,
                'message' => 'Account activated successfully.',
            ])
            ->assertJsonPath('results.user.email', 'alice@example.com')
            ->assertJsonPath('results.user.isVerified', true);

        // TempUser is deleted, permanent User is created
        $this->assertDatabaseMissing('temp_users', ['email' => 'alice@example.com']);
        $this->assertDatabaseHas('users', [
            'email' => 'alice@example.com',
            'is_verified' => true,
            'status' => 'active',
        ]);
    }

    public function test_verify_otp_fails_with_invalid_otp(): void
    {
        $tempUser = TempUser::create([
            'email' => 'bob@example.com',
            'password' => Hash::make('password123'),
            'role' => 'client',
        ]);

        $otpService = app(OtpService::class);
        $otpService->generateOtp('bob@example.com');

        $jwtService = app(JwtService::class);
        $token = $jwtService->generateVerificationToken($tempUser);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/verify-otp', [
                'otp' => '000000',
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'statusCode' => 400,
                'message' => 'Invalid OTP. Please try again.',
            ]);
    }

    public function test_verify_otp_fails_with_expired_otp(): void
    {
        $tempUser = TempUser::create([
            'email' => 'charlie@example.com',
            'password' => Hash::make('password123'),
            'role' => 'client',
        ]);

        EmailOtp::create([
            'email' => 'charlie@example.com',
            'otp' => '123456',
            'expires_at' => now()->subMinute(),
            'verified' => false,
        ]);

        $jwtService = app(JwtService::class);
        $token = $jwtService->generateVerificationToken($tempUser);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/verify-otp', [
                'otp' => '123456',
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'statusCode' => 400,
                'message' => 'OTP expired. Please request a new one.',
            ]);
    }
}
