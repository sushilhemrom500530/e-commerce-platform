<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubcategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_subcategories(): void
    {
        Subcategory::factory()->count(3)->create();

        $response = $this->get('/admin/subcategories');

        $response->assertStatus(200);
        $response->assertViewIs('admin.subcategories.index');
    }

    public function test_can_create_subcategory_under_category(): void
    {
        $category = Category::factory()->create(['name' => 'Electronics']);

        $response = $this->post('/admin/subcategories', [
            'category_id' => $category->id,
            'name' => 'Mobile Phone',
            'description' => 'Smartphones',
        ]);

        $response->assertRedirect('/admin/subcategories');
        $this->assertDatabaseHas('subcategories', [
            'category_id' => $category->id,
            'name' => 'Mobile Phone',
            'slug' => 'mobile-phone',
        ]);
    }

    public function test_by_category_ajax_endpoint_returns_json(): void
    {
        $category = Category::factory()->create();
        $sub = Subcategory::factory()->create(['category_id' => $category->id, 'name' => 'Laptops']);

        $this->getJson("/admin/categories/{$category->id}/subcategories")
            ->assertStatus(200)
            ->assertJson([
                ['id' => $sub->id, 'name' => 'Laptops'],
            ]);

        $this->getJson("/admin/categories/{$category->slug}/subcategories")
            ->assertStatus(200)
            ->assertJson([
                ['id' => $sub->id, 'name' => 'Laptops'],
            ]);
    }

    public function test_can_update_subcategory(): void
    {
        $category = Category::factory()->create();
        $sub = Subcategory::factory()->create(['category_id' => $category->id, 'name' => 'Original']);

        $response = $this->put("/admin/subcategories/{$sub->slug}", [
            'category_id' => $category->id,
            'name' => 'Renamed',
        ]);

        $response->assertRedirect('/admin/subcategories');
        $this->assertDatabaseHas('subcategories', [
            'id' => $sub->id,
            'name' => 'Renamed',
            'slug' => 'renamed',
        ]);
    }

    public function test_can_delete_subcategory(): void
    {
        $sub = Subcategory::factory()->create();

        $response = $this->delete("/admin/subcategories/{$sub->slug}");

        $response->assertRedirect('/admin/subcategories');
        $this->assertDatabaseMissing('subcategories', ['id' => $sub->id]);
    }

    public function test_can_create_subcategory_with_image_upload(): void
    {
        Storage::fake('public');

        $category = Category::factory()->create();
        $image = UploadedFile::fake()->image('subcat_icon.png', 200, 200);

        $response = $this->post('/admin/subcategories', [
            'category_id' => $category->id,
            'name' => 'Running Shoes',
            'description' => 'Athletic running footwear',
            'image' => $image,
        ]);

        $response->assertRedirect('/admin/subcategories');
        $this->assertDatabaseHas('subcategories', [
            'category_id' => $category->id,
            'name' => 'Running Shoes',
            'slug' => 'running-shoes',
        ]);

        $sub = Subcategory::where('slug', 'running-shoes')->first();
        $this->assertNotNull($sub->image);
        Storage::disk('public')->assertExists($sub->image);
    }
}
