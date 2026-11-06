<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\JwtService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityRoleAssignmentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_strictly_blocks_admin_role_escalation(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Attacker',
            'email' => 'attacker@example.com',
            'password' => 'password123',
            'confirmPassword' => 'password123',
            'role' => 'admin', // Malicious attempt to elevate privileges
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'statusCode' => 400,
                'message' => 'Validation Error',
            ]);

        $this->assertDatabaseMissing('temp_users', ['email' => 'attacker@example.com']);
        $this->assertDatabaseMissing('users', ['email' => 'attacker@example.com']);
    }

    public function test_select_role_strictly_blocks_admin_role_assignment(): void
    {
        $user = User::factory()->client()->create([
            'email' => 'regularuser@example.com',
        ]);

        $jwtService = app(JwtService::class);
        $token = $jwtService->generateAccessToken($user);

        // Attempt via POST /select-role
        $postResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/select-role', [
                'role' => 'admin',
            ]);

        $postResponse->assertStatus(400)
            ->assertJson([
                'success' => false,
                'statusCode' => 400,
            ]);

        // Attempt via PATCH /select-role
        $patchResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->patchJson('/api/v1/auth/select-role', [
                'role' => 'admin',
            ]);

        $patchResponse->assertStatus(400);

        // Ensure user remained client in DB
        $this->assertEquals('client', $user->fresh()->role);
    }

    public function test_select_role_permits_valid_roles_provider_and_client(): void
    {
        $user = User::factory()->client()->create([
            'email' => 'switchrole@example.com',
        ]);

        $jwtService = app(JwtService::class);
        $token = $jwtService->generateAccessToken($user);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/select-role', [
                'role' => 'provider',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('results.role', 'provider');

        $this->assertEquals('provider', $user->fresh()->role);
    }

    public function test_google_and_apple_login_endpoints_reject_admin_role(): void
    {
        $googleResponse = $this->postJson('/api/v1/auth/google', [
            'token' => 'dummy_token',
            'role' => 'admin',
        ]);
        $googleResponse->assertStatus(400);

        $appleResponse = $this->postJson('/api/v1/auth/apple', [
            'identityToken' => 'dummy_identity_token',
            'role' => 'admin',
        ]);
        $appleResponse->assertStatus(400);
    }
}
