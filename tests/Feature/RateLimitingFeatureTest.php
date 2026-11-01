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
}
