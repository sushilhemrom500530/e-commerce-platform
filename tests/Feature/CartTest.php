<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_cart_page(): void
    {
        $response = $this->get('/cart');

        $response->assertStatus(200);
        $response->assertSee('Shopping Cart');
        $response->assertSee('Your shopping cart is empty');
    }

    public function test_can_add_product_to_cart_via_post(): void
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'Sony Wireless Headphones',
            'new_price' => 18000,
        ]);

        $response = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'total_count' => 2,
        ]);

        $this->assertEquals(2, session('cart')[$product->id]['quantity']);
    }

    public function test_can_update_product_quantity_in_cart(): void
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'MacBook Air M4',
            'new_price' => 135000,
        ]);

        $this->postJson('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

        $response = $this->postJson('/cart/update', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'total_count' => 3,
        ]);

        $this->assertEquals(3, session('cart')[$product->id]['quantity']);
    }

    public function test_can_remove_product_from_cart(): void
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'Nike Sports Shoes',
            'new_price' => 7500,
        ]);

        $this->postJson('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

        $response = $this->postJson('/cart/remove', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'total_count' => 0,
        ]);

        $this->assertArrayNotHasKey($product->id, session('cart', []));
    }

    public function test_can_checkout_and_place_order(): void
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'new_price' => 5000,
        ]);

        $this->postJson('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

        $response = $this->post('/cart/checkout');

        $response->assertRedirect('/');
        $response->assertSessionHas('success');
        $this->assertEmpty(session('cart', []));
    }

    public function test_cannot_checkout_empty_cart(): void
    {
        $response = $this->post('/cart/checkout');

        $response->assertRedirect('/products');
        $response->assertSessionHas('error');
    }
}
