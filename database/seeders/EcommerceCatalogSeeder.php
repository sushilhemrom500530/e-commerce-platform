<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EcommerceCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            [
                'name' => 'Electronics',
                'description' => 'Latest tech gadgets, smart devices, smartphones, and laptops.',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=800&auto=format&fit=crop&q=80',
                'subcategories' => [
                    [
                        'name' => 'Mobile Phone',
                        'description' => 'Flagship, premium, and budget smartphones.',
                        'products' => [
                            [
                                'name' => 'Apple iPhone 16',
                                'description' => 'Apple iPhone 16 with A18 Bionic chip, advanced dual-camera system, Camera Control button, and stunning Super Retina XDR display.',
                                'image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 120000,
                                'new_price' => 110000,
                                'stock' => 25,
                            ],
                            [
                                'name' => 'Samsung Galaxy S24 Ultra',
                                'description' => 'Samsung flagship smartphone with Galaxy AI, titanium frame, 200MP camera, and integrated S-Pen.',
                                'image' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 145000,
                                'new_price' => 135000,
                                'stock' => 18,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Laptop',
                        'description' => 'High performance ultrabooks and workstation laptops.',
                        'products' => [
                            [
                                'name' => 'MacBook Pro 16 M3 Max',
                                'description' => 'Supercharged for pros with M3 Max 16-core CPU, 40-core GPU, 36GB unified memory, and Liquid Retina XDR display.',
                                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 320000,
                                'new_price' => 295000,
                                'stock' => 10,
                            ],
                            [
                                'name' => 'Dell XPS 15 OLED',
                                'description' => 'Premium Windows laptop with 3.5K OLED touchscreen, Intel Core i9 processor, and NVIDIA GeForce RTX 4060 graphics.',
                                'image' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 240000,
                                'new_price' => 215000,
                                'stock' => 12,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Fashion',
                'description' => 'Trendy modern apparel, men and women clothing, and footwear.',
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&auto=format&fit=crop&q=80',
                'subcategories' => [
                    [
                        'name' => "Men's Clothing",
                        'description' => 'Shirts, jackets, trousers, and streetwear.',
                        'products' => [
                            [
                                'name' => 'Classic Slim-Fit Oxford Shirt',
                                'description' => '100% breathable Egyptian cotton tailored button-down shirt designed for all-day comfort and timeless appeal.',
                                'image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 3200,
                                'new_price' => 2400,
                                'stock' => 50,
                            ],
                            [
                                'name' => 'Vintage Washed Denim Jacket',
                                'description' => 'Heavyweight durable denim jacket with copper hardware, relaxed fit, and heritage wash styling.',
                                'image' => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 5500,
                                'new_price' => 4500,
                                'stock' => 30,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Shoes',
                        'description' => 'Handcrafted formal shoes, casual loafers, and athletic sneakers.',
                        'products' => [
                            [
                                'name' => 'Handcrafted Italian Leather Loafers',
                                'description' => 'Supple full-grain Italian calfskin leather loafers with cushioned insole and hand-stitched apron toe.',
                                'image' => 'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 7500,
                                'new_price' => 5900,
                                'stock' => 20,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Grocery',
                'description' => 'Organic fresh groceries, premium beverages, and pantry essentials.',
                'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=800&auto=format&fit=crop&q=80',
                'subcategories' => [
                    [
                        'name' => 'Beverages',
                        'description' => 'Specialty coffee roasts, artisan teas, and organic juices.',
                        'products' => [
                            [
                                'name' => 'Single-Origin Ethiopian Yirgacheffe Coffee',
                                'description' => 'Freshly roasted whole bean arabica coffee offering delicate notes of jasmine floral, citrus bergamot, and honey sweetness.',
                                'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 1800,
                                'new_price' => 1500,
                                'stock' => 40,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($catalog as $catData) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($catData['name'])],
                [
                    'name' => $catData['name'],
                    'description' => $catData['description'],
                    'image' => $catData['image'],
                    'is_active' => true,
                ]
            );

            foreach ($catData['subcategories'] as $subData) {
                $subcategory = Subcategory::firstOrCreate(
                    ['slug' => Str::slug($subData['name'])],
                    [
                        'category_id' => $category->id,
                        'name' => $subData['name'],
                        'description' => $subData['description'],
                        'is_active' => true,
                    ]
                );

                foreach ($subData['products'] as $prodData) {
                    Product::firstOrCreate(
                        ['slug' => Str::slug($prodData['name'])],
                        [
                            'category_id' => $category->id,
                            'subcategory_id' => $subcategory->id,
                            'name' => $prodData['name'],
                            'description' => $prodData['description'],
                            'image' => $prodData['image'],
                            'old_price' => $prodData['old_price'],
                            'new_price' => $prodData['new_price'],
                            'stock' => $prodData['stock'],
                            'is_active' => true,
                        ]
                    );
                }
            }
        }
    }
}
