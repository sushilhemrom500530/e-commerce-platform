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

    public function test_can_view_all_products_catalog_page(): void
    {
        $category = Category::factory()->create(['name' => 'Electronics']);
        $sub = Subcategory::factory()->create(['category_id' => $category->id, 'name' => 'Mobile']);
        Product::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $sub->id,
            'name' => 'iPhone 16',
            'old_price' => 120000,
            'new_price' => 110000,
        ]);

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee('All Products');
        $response->assertSee('Categories');
        $response->assertSee('iPhone 16');
        $response->assertSee('110,000');
        $response->assertSee('Add to Cart');
    }

    public function test_can_filter_products_by_category_on_catalog_page(): void
    {
        $electronics = Category::factory()->create(['name' => 'Electronics', 'slug' => 'electronics']);
        $fashion = Category::factory()->create(['name' => 'Fashion', 'slug' => 'fashion']);

        $subEl = Subcategory::factory()->create(['category_id' => $electronics->id]);
        $subFa = Subcategory::factory()->create(['category_id' => $fashion->id]);

        Product::factory()->create([
            'category_id' => $electronics->id,
            'subcategory_id' => $subEl->id,
            'name' => 'MacBook Air M4',
        ]);

        Product::factory()->create([
            'category_id' => $fashion->id,
            'subcategory_id' => $subFa->id,
            'name' => 'Nike Shoes',
        ]);

        $response = $this->get('/products?category=electronics');

        $response->assertStatus(200);
        $response->assertSee('MacBook Air M4');
        $response->assertDontSee('Nike Shoes');
    }

    public function test_can_sort_products_by_price_on_catalog_page(): void
    {
        $category = Category::factory()->create(['name' => 'Electronics']);
        $sub = Subcategory::factory()->create(['category_id' => $category->id]);

        Product::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $sub->id,
            'name' => 'Budget Watch',
            'new_price' => 2000,
        ]);

        Product::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $sub->id,
            'name' => 'Premium Laptop',
            'new_price' => 150000,
        ]);

        $response = $this->get('/products?sort=price_asc');

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Budget Watch', 'Premium Laptop']);
    }

    public function test_can_view_about_us_page(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('Our Story', false);
        $response->assertSee('Zero-Compromise Authenticity', false);
        $response->assertSee('Honest & Fair Pricing', false);
        $response->assertSee('Real Human Support', false);
        $response->assertSee('25,000+', false);
    }

    public function test_can_view_contact_page(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertSee("We're Here to Help", false);
        $response->assertSee('Visit Our Office', false);
        $response->assertSee('Call Support', false);
        $response->assertSee('support@shopease.com.bd', false);
        $response->assertSee('Frequently Asked Questions', false);
    }

    public function test_can_submit_contact_inquiry_form(): void
    {
        $payload = [
            'name' => 'Sushil Hemrom',
            'email' => 'sushil@example.com',
            'phone' => '+8801700123456',
            'subject' => 'Order Status & Delivery Tracking',
            'message' => 'Hello, I would like to check on the delivery timeline for my recent order.',
        ];

        $response = $this->post('/contact', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->post('/contact', []);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }
}
