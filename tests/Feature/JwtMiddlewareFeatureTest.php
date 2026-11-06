<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\JwtService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class JwtMiddlewareFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/api/test-admin-only', function () {
            return response()->json(['success' => true, 'message' => 'Admin secret accessed']);
        })->middleware('jwt.auth:admin');
    }

    public function test_access_denied_when_no_token_provided(): void
    {
        $response = $this->getJson('/api/v1/auth/me');

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Access denied. No token provided.',
            ]);
    }

    public function test_access_granted_with_valid_bearer_token(): void
    {
        $user = User::factory()->create([
            'email' => 'jwtuser@example.com',
            'role' => 'client',
        ]);

        $jwtService = app(JwtService::class);
        $token = $jwtService->generateAccessToken($user);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'statusCode' => 200,
                'success' => true,
            ])
            ->assertJsonPath('results.email', 'jwtuser@example.com')
            ->assertJsonPath('results.role', 'client');
    }

    public function test_access_granted_with_cookie_token(): void
    {
        $user = User::factory()->create([
            'email' => 'cookieuser@example.com',
            'role' => 'client',
        ]);

        $jwtService = app(JwtService::class);
        $token = $jwtService->generateAccessToken($user);

        $response = $this->withHeader('Cookie', "token={$token}")
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(200)
            ->assertJsonPath('results.email', 'cookieuser@example.com');
    }

    public function test_role_enforcement_blocks_unauthorized_roles(): void
    {
        $clientUser = User::factory()->client()->create();

        $jwtService = app(JwtService::class);
        $clientToken = $jwtService->generateAccessToken($clientUser);

        // Client attempting to access admin-only route
        $response = $this->withHeader('Authorization', "Bearer {$clientToken}")
            ->getJson('/api/test-admin-only');

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'statusCode' => 403,
                'message' => 'You are not authorized',
            ]);

        // Admin accessing admin-only route
        $adminUser = User::factory()->admin()->create();
        $adminToken = $jwtService->generateAccessToken($adminUser);

        $adminResponse = $this->withHeader('Authorization', "Bearer {$adminToken}")
            ->getJson('/api/test-admin-only');

        $adminResponse->assertStatus(200)
            ->assertJsonPath('success', true);
    }
}
