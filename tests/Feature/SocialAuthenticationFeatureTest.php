<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AppleAuthService;
use App\Services\GoogleAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class SocialAuthenticationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_sign_in_creates_new_user_and_returns_tokens(): void
    {
        $this->mock(GoogleAuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('verifyGoogleToken')
                ->once()
                ->with('valid_google_token')
                ->andReturn([
                    'sub' => 'google_uid_12345',
                    'email' => 'googleuser@example.com',
                    'name' => 'Google Person',
                    'picture' => 'https://example.com/avatar.png',
                ]);
        });

        $response = $this->postJson('/api/v1/auth/google', [
            'idToken' => 'valid_google_token',
            'role' => 'client',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'statusCode' => 200,
                'success' => true,
                'message' => 'Google sign-in successful.',
            ])
            ->assertJsonPath('data.user.email', 'googleuser@example.com')
            ->assertJsonPath('data.user.provider', 'google')
            ->assertJsonPath('data.user.providerId', 'google_uid_12345')
            ->assertJsonStructure([
                'data' => [
                    'user',
                    'accessToken',
                    'token',
                    'refreshToken',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'googleuser@example.com',
            'provider' => 'google',
            'provider_id' => 'google_uid_12345',
            'is_verified' => true,
        ]);
    }

    public function test_google_sign_in_links_to_existing_account_with_matching_email(): void
    {
        $existing = User::factory()->create([
            'email' => 'linkuser@example.com',
            'provider' => null,
            'provider_id' => null,
        ]);

        $this->mock(GoogleAuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('verifyGoogleToken')
                ->once()
                ->andReturn([
                    'sub' => 'google_link_999',
                    'email' => 'linkuser@example.com',
                    'name' => 'Link User',
                    'picture' => null,
                ]);
        });

        $response = $this->postJson('/api/v1/auth/google', [
            'token' => 'dummy_token',
        ]);

        $response->assertStatus(200);

        $this->assertEquals('google', $existing->fresh()->provider);
        $this->assertEquals('google_link_999', $existing->fresh()->provider_id);
    }

    public function test_apple_sign_in_creates_new_user_and_returns_tokens(): void
    {
        $this->mock(AppleAuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('verifyIdentityToken')
                ->once()
                ->with('valid_apple_identity_token')
                ->andReturn([
                    'sub' => 'apple_sub_77777',
                    'email' => 'appleuser@privaterelay.appleid.com',
                ]);
        });

        $response = $this->postJson('/api/v1/auth/apple', [
            'identityToken' => 'valid_apple_identity_token',
            'fullName' => 'Apple Dev',
            'role' => 'provider',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'statusCode' => 200,
                'success' => true,
                'message' => 'Apple sign-in successful.',
            ])
            ->assertJsonPath('data.user.email', 'appleuser@privaterelay.appleid.com')
            ->assertJsonPath('data.user.provider', 'apple')
            ->assertJsonPath('data.user.providerId', 'apple_sub_77777')
            ->assertJsonPath('data.user.role', 'provider');

        $this->assertDatabaseHas('users', [
            'email' => 'appleuser@privaterelay.appleid.com',
            'provider' => 'apple',
            'provider_id' => 'apple_sub_77777',
            'role' => 'provider',
        ]);
    }
}
