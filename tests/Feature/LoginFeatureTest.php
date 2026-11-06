<?php

namespace Tests\Feature;

use App\Models\TempUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'loginuser@example.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'is_verified' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'loginuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'statusCode' => 201,
                'success' => true,
                'message' => 'Login successfully.',
            ])
            ->assertJsonStructure([
                'results' => [
                    'user' => ['_id', 'id', 'email', 'name', 'role'],
                    'role',
                    'token',
                    'refreshToken',
                ],
            ]);

        $response->assertCookie('token');
        $response->assertCookie('refreshToken');
    }

    public function test_login_fails_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'loginuser@example.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'is_verified' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'loginuser@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'statusCode' => 400,
                'message' => 'Invalid password',
            ]);
    }

    public function test_login_fails_if_account_is_suspended(): void
    {
        User::factory()->suspended()->create([
            'email' => 'suspended@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'suspended@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'statusCode' => 400,
                'message' => 'Your account is suspended. Please contact support.',
            ]);
    }

    public function test_login_fails_if_account_is_deleted(): void
    {
        User::factory()->deleted()->create([
            'email' => 'deleted@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'deleted@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'statusCode' => 400,
                'message' => 'Your account is deleted. Please contact support.',
            ]);
    }

    public function test_login_notifies_unverified_user_waiting_in_temp_table(): void
    {
        TempUser::create([
            'email' => 'unverified@example.com',
            'password' => Hash::make('password123'),
            'role' => 'client',
            'status' => 'pending',
            'is_verified' => false,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'unverified@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'statusCode' => 400,
                'message' => 'Your account is not verified yet. Please verify your OTP to activate your account.',
            ]);
    }
}
