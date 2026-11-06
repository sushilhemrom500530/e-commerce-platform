<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_deleting_category_cascades_and_removes_related_subcategories_and_products(): void
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
        ]);

        $this->delete("/admin/categories/{$category->slug}")->assertRedirect('/admin/categories');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseMissing('subcategories', ['id' => $subcategory->id]);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_can_create_category_with_image_upload(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('category_badge.png', 400, 400);

        $response = $this->post('/admin/categories', [
            'name' => 'Sports & Outdoors',
            'description' => 'Fitness gear and sporting equipment',
            'image' => $image,
        ]);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', [
            'name' => 'Sports & Outdoors',
            'slug' => 'sports-outdoors',
        ]);

        $category = Category::where('slug', 'sports-outdoors')->first();
        $this->assertNotNull($category->image);
        Storage::disk('public')->assertExists($category->image);
    }
}
