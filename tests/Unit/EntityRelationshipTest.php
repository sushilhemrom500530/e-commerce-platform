<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntityRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_many_subcategories_and_products(): void
    {
        $category = Category::factory()->create(['name' => 'Electronics']);
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id, 'name' => 'Mobile Phone']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'iPhone 16',
            'old_price' => 120000,
            'new_price' => 110000,
        ]);

        $this->assertTrue($category->subcategories->contains($subcategory));
        $this->assertTrue($category->products->contains($product));
        $this->assertEquals('electronics', $category->slug);
    }

    public function test_subcategory_belongs_to_category_and_has_many_products(): void
    {
        $category = Category::factory()->create(['name' => 'Fashion']);
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id, 'name' => "Men's Clothing"]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'Oxford Shirt',
        ]);

        $this->assertEquals($category->id, $subcategory->category->id);
        $this->assertTrue($subcategory->products->contains($product));
    }

    public function test_product_calculates_discount_percentage_and_image_url(): void
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'Discounted Item',
            'old_price' => 100,
            'new_price' => 80,
        ]);

        $this->assertEquals(20, $product->discount_percentage);
        $this->assertNotEmpty($product->image_url);
    }

    public function test_category_deletion_cascades_to_subcategories_and_products(): void
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
        ]);

        $category->delete();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseMissing('subcategories', ['id' => $subcategory->id]);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
