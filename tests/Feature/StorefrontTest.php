<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_displays_products_grouped_by_subcategories(): void
    {
        $category = Category::factory()->create(['name' => 'Electronics']);
        $sub = Subcategory::factory()->create(['category_id' => $category->id, 'name' => 'Smartphones']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $sub->id,
            'name' => 'iPhone 16 Pro',
            'old_price' => 120000,
            'new_price' => 110000,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Smartphones');
        $response->assertSee('iPhone 16 Pro');
        $response->assertSee('110,000.00');
    }

    public function test_can_view_single_product_details_using_seo_slug(): void
    {
        $category = Category::factory()->create(['name' => 'Electronics']);
        $sub = Subcategory::factory()->create(['category_id' => $category->id, 'name' => 'Smartphones']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $sub->id,
            'name' => 'Samsung Galaxy S24 Ultra',
            'slug' => 'samsung-galaxy-s24-ultra',
            'description' => 'Flagship smartphone with Galaxy AI',
            'old_price' => 145000,
            'new_price' => 135000,
        ]);

        $response = $this->get('/product/samsung-galaxy-s24-ultra');

        $response->assertStatus(200);
        $response->assertSee('Samsung Galaxy S24 Ultra');
        $response->assertSee('Flagship smartphone with Galaxy AI');
        $response->assertSee('135,000.00');
        $response->assertSee('Smartphones');
    }

    public function test_viewing_nonexistent_product_slug_returns_404(): void
    {
        $response = $this->get('/product/nonexistent-item-slug');

        $response->assertStatus(404);
    }

    public function test_storefront_displays_shopease_header_hero_banner_and_top_categories(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('ShopEase');
        $response->assertSee('Smart Shopping');
        $response->assertSee('Better Living');
        $response->assertSee('Fast Delivery');
        $response->assertSee('Secure Payment');
        $response->assertSee('Top Categories');
        $response->assertSee('Laptops');
        $response->assertSee('Mobiles');
        $response->assertSee('Watches');
        $response->assertSee('Fashion');
    }
}
