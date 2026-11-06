<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->get('/admin/categories');

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
    }

    public function test_can_create_category(): void
    {
        $response = $this->post('/admin/categories', [
            'name' => 'Electronics',
            'description' => 'Electronic devices',
        ]);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', [
            'name' => 'Electronics',
            'slug' => 'electronics',
        ]);
    }

    public function test_category_validation_requires_unique_name(): void
    {
        Category::factory()->create(['name' => 'Fashion']);

        $response = $this->post('/admin/categories', [
            'name' => 'Fashion',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_can_update_category(): void
    {
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->put("/admin/categories/{$category->slug}", [
            'name' => 'Updated Name',
            'description' => 'Updated desc',
        ]);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Name',
            'slug' => 'updated-name',
        ]);
    }

    public function test_can_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->delete("/admin/categories/{$category->slug}");

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
