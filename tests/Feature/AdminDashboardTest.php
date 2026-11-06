<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_admin_dashboard_with_exact_layout(): void
    {
        $category = Category::factory()->create(['name' => 'Tech']);
        $sub = Subcategory::factory()->create(['category_id' => $category->id]);
        Product::factory()->count(5)->create([
            'category_id' => $category->id,
            'subcategory_id' => $sub->id,
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertSee('Dashboard');
        $response->assertSee('Overview of your store');
        $response->assertSee('Total Products');
        $response->assertSee('Total Categories');
        $response->assertSee('Total Orders');
        $response->assertSee('Total Users');
        $response->assertSee('Sales Overview');
        $response->assertSee('Recent Orders');
        $response->assertSee('#ORD-1001');
        $response->assertSee('Pending');
        $response->assertSee('Delivered');
    }

    public function test_can_access_dashboard_via_dashboard_path(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }
}
