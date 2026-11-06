<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WebAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_login_page(): void
    {
        $this->get('/login')
            ->assertStatus(200)
            ->assertSee('Sign In to Your Account');
    }

    public function test_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
            'password' => Hash::make('Password@123'),
            'role' => 'client',
        ]);

        $response = $this->post('/login', [
            'email' => 'customer@example.com',
            'password' => 'Password@123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_redirects_to_admin_portal_upon_login(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('Password@123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'Password@123',
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $this->from('/login')->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpass',
        ])->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_can_register_new_customer(): void
    {
        $response = $this->post('/register', [
            'name' => 'Jane Customer',
            'email' => 'jane@example.com',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'role' => 'client',
        ]);
        $this->assertAuthenticated();
    }

    public function test_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }
}
