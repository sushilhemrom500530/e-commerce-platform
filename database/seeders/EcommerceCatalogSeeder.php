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
                                'name' => 'iPhone 16',
                                'description' => 'Apple iPhone 16 with A18 chip, Camera Control, and 48MP Fusion camera.',
                                'image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 120000,
                                'new_price' => 110000,
                                'stock' => 25,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Laptop',
                        'description' => 'High performance ultrabooks and workstation laptops.',
                        'products' => [
                            [
                                'name' => 'MacBook Air M4',
                                'description' => 'Supercharged by Apple M4 silicon with Liquid Retina display and all-day battery life.',
                                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 150000,
                                'new_price' => 135000,
                                'stock' => 15,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Audio',
                        'description' => 'Wireless noise-canceling headphones and earbuds.',
                        'products' => [
                            [
                                'name' => 'Sony Headphones',
                                'description' => 'Industry-leading wireless noise canceling stereo headphones with premium sound.',
                                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 22000,
                                'new_price' => 18000,
                                'stock' => 30,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Wearables',
                        'description' => 'Smart watches, fitness trackers, and health monitors.',
                        'products' => [
                            [
                                'name' => 'Smart Watch',
                                'description' => 'Advanced smartwatch with fitness tracking, heart-rate sensor, and OLED display.',
                                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 15000,
                                'new_price' => 12000,
                                'stock' => 35,
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
                        'name' => 'Shoes',
                        'description' => 'Handcrafted formal shoes, casual loafers, and athletic sneakers.',
                        'products' => [
                            [
                                'name' => 'Nike Shoes',
                                'description' => 'Lightweight running and lifestyle sneakers designed for comfort and performance.',
                                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 9000,
                                'new_price' => 7500,
                                'stock' => 45,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Bags & Accessories',
                        'description' => 'Everyday backpacks, laptop bags, and travel gear.',
                        'products' => [
                            [
                                'name' => 'Backpack',
                                'description' => 'Durable water-resistant urban backpack with padded laptop sleeve and multi-compartments.',
                                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 2500,
                                'new_price' => 2000,
                                'stock' => 50,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Home & Living',
                'description' => 'Modern furniture, ambient lighting, and home accessories.',
                'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&auto=format&fit=crop&q=80',
                'subcategories' => [
                    [
                        'name' => 'Home Decor',
                        'description' => 'Ambient lighting, rugs, and decorative pieces.',
                        'products' => [
                            [
                                'name' => 'Minimalist Table Lamp',
                                'description' => 'Warm LED designer bedside lamp with dimmable touch control.',
                                'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 3500,
                                'new_price' => 2800,
                                'stock' => 25,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Sports',
                'description' => 'Sports equipment, workout gear, and outdoor accessories.',
                'image' => 'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?w=800&auto=format&fit=crop&q=80',
                'subcategories' => [
                    [
                        'name' => 'Fitness Gear',
                        'description' => 'Dumbbells, resistance bands, and gym gear.',
                        'products' => [
                            [
                                'name' => 'Adjustable Dumbbell Set',
                                'description' => 'Solid cast iron quick-adjust dumbbells for full-body home workouts.',
                                'image' => 'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 6000,
                                'new_price' => 4800,
                                'stock' => 20,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Books',
                'description' => 'Bestselling novels, self-improvement books, and educational literature.',
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800&auto=format&fit=crop&q=80',
                'subcategories' => [
                    [
                        'name' => 'Self-Development',
                        'description' => 'Personal growth, productivity, and leadership books.',
                        'products' => [
                            [
                                'name' => 'Atomic Habits',
                                'description' => 'An easy and proven way to build good habits and break bad ones by James Clear.',
                                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800&auto=format&fit=crop&q=80',
                                'old_price' => 950,
                                'new_price' => 750,
                                'stock' => 60,
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
