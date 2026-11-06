<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WebAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_login_button_in_storefront_navbar(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Login');
        $response->assertSee(route('login'));
        $response->assertDontSee('Logout');
    }

    public function test_guest_can_access_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Welcome Back');
        $response->assertSee('Sign In');
        $response->assertSee('Quick Demo Credentials');
    }

    public function test_user_can_login_with_valid_credentials(): void
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

    public function test_admin_redirects_to_admin_dashboard_on_login(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('Admin@123456'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'Admin@123456',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_authenticated_user_sees_avatar_and_dropdown_actions_in_navbar(): void
    {
        $user = User::factory()->create([
            'name' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'role' => 'client',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        // User's avatar initial is displayed
        $response->assertSee('A');
        $response->assertSee('Alice Johnson');
        $response->assertSee('alice@example.com');
        // Dropdown actions specified by user: Admin Panel, Profile, and Logout
        $response->assertSee('Admin Panel');
        $response->assertSee('Profile');
        $response->assertSee('Logout');
        $response->assertSee(route('admin.dashboard'));
        $response->assertSee(route('profile'));
        $response->assertSee(route('logout'));
    }

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Bob Marley',
            'email' => 'bob@example.com',
            'role' => 'client',
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Bob Marley');
        $response->assertSee('bob@example.com');
        $response->assertSee('Personal Information');
    }

    public function test_authenticated_user_can_update_profile_info(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'phone_number' => '01711111111',
            'address' => 'Old Address',
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'Updated Name',
            'phone_number' => '01822222222',
            'address' => 'New Address, Dhaka',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'phone_number' => '01822222222',
            'address' => 'New Address, Dhaka',
        ]);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_invalid_login_credentials_return_error(): void
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
