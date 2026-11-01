<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\User;
use App\Services\JwtService;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordManagementFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_sends_otp_and_returns_reset_token(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'forgot@example.com',
        ]);

        $response = $this->postJson('/api/v1/auth/forgot-password', [
            'email' => 'forgot@example.com',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'statusCode' => 201,
                'success' => true,
                'message' => 'Otp send successfully. Please check your email to reset password.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'token',
                ],
            ]);

        Mail::assertSent(OtpMail::class);
    }

    public function test_forgot_password_otp_verification_flow(): void
    {
        $user = User::factory()->create([
            'email' => 'verifyreset@example.com',
        ]);

        $otpService = app(OtpService::class);
        $otp = $otpService->generateOtp('verifyreset@example.com');

        $jwtService = app(JwtService::class);
        $token = $jwtService->generatePasswordResetToken($user);

        // Verification succeeds
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/forgot-otp-verify', [
                'otp' => $otp,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'statusCode' => 201,
                'success' => true,
                'message' => 'Opt verified successfully. Please reset your password.',
            ]);

        // Reset password with verified token
        $resetResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/reset-password', [
                'newPassword' => 'brandnewpass123',
                'confirmPassword' => 'brandnewpass123',
            ]);

        $resetResponse->assertStatus(201)
            ->assertJson([
                'statusCode' => 201,
                'success' => true,
                'message' => 'Password reset successfully.',
            ]);

        $this->assertTrue(Hash::check('brandnewpass123', $user->fresh()->password));
    }

    public function test_authenticated_user_can_change_password(): void
    {
        $user = User::factory()->create([
            'email' => 'changepass@example.com',
            'password' => Hash::make('oldpassword123'),
        ]);

        $jwtService = app(JwtService::class);
        $token = $jwtService->generateAccessToken($user);

        // Fails with wrong old password
        $failResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/change-password', [
                'oldPassword' => 'wrongpass',
                'newPassword' => 'newpassword123',
                'confirmPassword' => 'newpassword123',
            ]);

        $failResponse->assertStatus(401)
            ->assertJson([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Old password is incorrect',
            ]);

        // Fails if new password same as old password
        $sameResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/change-password', [
                'oldPassword' => 'oldpassword123',
                'newPassword' => 'oldpassword123',
                'confirmPassword' => 'oldpassword123',
            ]);

        $sameResponse->assertStatus(400);

        // Succeeds with correct old password
        $successResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/change-password', [
                'oldPassword' => 'oldpassword123',
                'newPassword' => 'newpassword123',
                'confirmPassword' => 'newpassword123',
            ]);

        $successResponse->assertStatus(201)
            ->assertJson([
                'statusCode' => 201,
                'success' => true,
                'message' => 'Password changed successfully.',
            ]);

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }
}
