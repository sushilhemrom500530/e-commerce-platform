<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully_and_receive_verification_token(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret123',
            'confirmPassword' => 'secret123',
            'role' => 'client',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'statusCode' => 201,
                'success' => true,
                'message' => 'OTP has been sent to your email address. Please verify it to activate your account.',
            ])
            ->assertJsonStructure([
                'results' => [
                    'user' => ['_id', 'id', 'email', 'name', 'role'],
                    'role',
                    'token',
                ],
            ]);

        $this->assertDatabaseHas('temp_users', [
            'email' => 'john@example.com',
            'role' => 'client',
        ]);

        $this->assertDatabaseHas('email_otps', [
            'email' => 'john@example.com',
            'verified' => false,
        ]);

        Mail::assertSent(OtpMail::class, function ($mail) {
            return $mail->hasTo('john@example.com');
        });
    }

    public function test_registration_fails_if_passwords_do_not_match(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'secret123',
            'confirmPassword' => 'different123',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'statusCode' => 400,
                'message' => 'Validation Error',
            ]);
    }

    public function test_registration_fails_if_email_is_already_registered(): void
    {
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => 'secret123',
            'confirmPassword' => 'secret123',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'statusCode' => 400,
                'message' => 'This email is already registered. Please login to continue.',
            ]);
    }

    public function test_otp_is_never_returned_in_production_environment(): void
    {
        Mail::fake();

        config(['app.env' => 'production']);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Prod User',
            'email' => 'prod@example.com',
            'password' => 'secret123',
            'confirmPassword' => 'secret123',
        ]);

        $response->assertStatus(201);
        $this->assertNull($response->json('results.otp'));
    }
}
