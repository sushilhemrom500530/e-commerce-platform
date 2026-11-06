<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->get('/admin/products');

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
    }

    public function test_can_create_product_with_image_upload(): void
    {
        Storage::fake('public');

        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $image = UploadedFile::fake()->image('iphone16.jpg', 600, 600);

        $response = $this->post('/admin/products', [
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'Apple iPhone 16',
            'description' => 'Latest Apple flagship',
            'old_price' => 120000,
            'new_price' => 110000,
            'stock' => 15,
            'image' => $image,
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', [
            'name' => 'Apple iPhone 16',
            'slug' => 'apple-iphone-16',
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'new_price' => 110000,
        ]);

        $product = Product::where('slug', 'apple-iphone-16')->first();
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_cross_validation_rejects_mismatched_subcategory(): void
    {
        $categoryA = Category::factory()->create(['name' => 'Electronics']);
        $categoryB = Category::factory()->create(['name' => 'Fashion']);
        $subOfB = Subcategory::factory()->create(['category_id' => $categoryB->id]);

        // Attempt to create product with Category A but Subcategory of Category B
        $response = $this->post('/admin/products', [
            'category_id' => $categoryA->id,
            'subcategory_id' => $subOfB->id,
            'name' => 'Invalid Product',
            'description' => 'Mismatch test',
            'new_price' => 500,
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertSessionHasErrors('subcategory_id');
    }

    public function test_can_update_product(): void
    {
        Storage::fake('public');

        $product = Product::factory()->create(['name' => 'Original Product']);

        $response = $this->put("/admin/products/{$product->slug}", [
            'category_id' => $product->category_id,
            'subcategory_id' => $product->subcategory_id,
            'name' => 'Renamed Product',
            'description' => 'Updated description',
            'new_price' => 999,
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Renamed Product',
            'slug' => 'renamed-product',
        ]);
    }

    public function test_can_delete_product(): void
    {
        Storage::fake('public');

        $product = Product::factory()->create([
            'image' => 'products/sample.jpg',
        ]);
        Storage::disk('public')->put('products/sample.jpg', 'fake content');

        $response = $this->delete("/admin/products/{$product->slug}");

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        Storage::disk('public')->assertMissing('products/sample.jpg');
    }
}
