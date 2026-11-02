<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class RateLimitingFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('auth.login:ratelimit@example.com|127.0.0.1');
        RateLimiter::clear('auth.register:127.0.0.1');
    }

    public function test_login_endpoint_is_throttled_after_maximum_attempts(): void
    {
        // 5 allowed attempts
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/auth/login', [
                'email' => 'ratelimit@example.com',
                'password' => 'wrongpass',
            ]);
        }

        // 6th attempt should be blocked with 429
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'ratelimit@example.com',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(429)
            ->assertJson([
                'success' => false,
                'statusCode' => 429,
                'message' => 'Too many requests from this IP, please try again later',
            ]);
    }

    public function test_register_endpoint_is_throttled_after_10_attempts(): void
    {
        // 10 attempts
        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/api/v1/auth/register', [
                'email' => "user{$i}@example.com",
                'password' => 'secret123',
                'confirmPassword' => 'secret123',
            ]);
        }

        // 11th attempt
        $response = $this->postJson('/api/v1/auth/register', [
            'email' => 'overflow@example.com',
            'password' => 'secret123',
            'confirmPassword' => 'secret123',
        ]);

        $response->assertStatus(429);
    }

    public function test_resend_otp_is_throttled_after_3_attempts(): void
    {
        RateLimiter::clear('auth.resend:otpuser@example.com|127.0.0.1');

        for ($i = 0; $i < 3; $i++) {
            $this->postJson('/api/v1/auth/resend-otp', [
                'email' => 'otpuser@example.com',
            ]);
        }

        $response = $this->postJson('/api/v1/auth/resend-otp', [
            'email' => 'otpuser@example.com',
        ]);

        $response->assertStatus(429);
        $this->assertNotNull($response->headers->get('Retry-After'));
    }

    public function test_verify_otp_is_throttled_after_5_attempts(): void
    {
        RateLimiter::clear('auth.otp:verifyuser@example.com|127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/auth/verify-otp', [
                'email' => 'verifyuser@example.com',
                'otp' => '123456',
            ]);
        }

        $response = $this->postJson('/api/v1/auth/verify-otp', [
            'email' => 'verifyuser@example.com',
            'otp' => '123456',
        ]);

        $response->assertStatus(429);
    }

    public function test_reset_password_is_throttled_after_5_attempts(): void
    {
        RateLimiter::clear('auth.password.reset:resetuser@example.com|127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/auth/reset-password', [
                'email' => 'resetuser@example.com',
                'otp' => '123456',
                'password' => 'newsecret123',
                'confirmPassword' => 'newsecret123',
            ]);
        }

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'email' => 'resetuser@example.com',
            'otp' => '123456',
            'password' => 'newsecret123',
            'confirmPassword' => 'newsecret123',
        ]);

        $response->assertStatus(429);
    }

    public function test_social_endpoint_is_throttled_after_10_attempts(): void
    {
        RateLimiter::clear('auth.social:127.0.0.1');

        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/api/v1/auth/google', [
                'idToken' => 'invalid-google-token',
            ]);
        }

        $response = $this->postJson('/api/v1/auth/google', [
            'idToken' => 'invalid-google-token',
        ]);

        $response->assertStatus(429);
    }

    public function test_refresh_token_is_throttled(): void
    {
        RateLimiter::clear('auth.refresh:'.substr(hash('sha256', 'fake-refresh-token'), 0, 16).'|127.0.0.1');

        for ($i = 0; $i < 15; $i++) {
            $this->postJson('/api/v1/auth/refresh-token', [
                'refreshToken' => 'fake-refresh-token',
            ]);
        }

        $response = $this->postJson('/api/v1/auth/refresh-token', [
            'refreshToken' => 'fake-refresh-token',
        ]);

        $response->assertStatus(429);
    }
}
