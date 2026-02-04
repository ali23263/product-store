<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electronics = Category::where('name', 'Electronics')->first();
        $clothing = Category::where('name', 'Clothing')->first();
        $food = Category::where('name', 'Food')->first();
        $home = Category::where('name', 'Home')->first();
        $beauty = Category::where('name', 'Beauty')->first();

        $products = [
            // Electronics
            [
                'name' => 'Wireless Headphones',
                'description' => 'Premium noise-cancelling wireless headphones with 30-hour battery life',
                'price' => 199.99,
                'stock' => 50,
                'category_id' => $electronics->id,
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400',
            ],
            [
                'name' => 'Smart Watch',
                'description' => 'Feature-rich smartwatch with fitness tracking and heart rate monitor',
                'price' => 299.99,
                'stock' => 30,
                'category_id' => $electronics->id,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400',
            ],
            [
                'name' => 'Laptop Stand',
                'description' => 'Ergonomic aluminum laptop stand with adjustable height',
                'price' => 49.99,
                'stock' => 100,
                'category_id' => $electronics->id,
                'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400',
            ],

            // Clothing
            [
                'name' => 'Cotton T-Shirt',
                'description' => '100% organic cotton t-shirt, comfortable and breathable',
                'price' => 24.99,
                'stock' => 200,
                'category_id' => $clothing->id,
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400',
            ],
            [
                'name' => 'Denim Jeans',
                'description' => 'Classic fit denim jeans with premium quality fabric',
                'price' => 79.99,
                'stock' => 150,
                'category_id' => $clothing->id,
                'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400',
            ],
            [
                'name' => 'Running Shoes',
                'description' => 'Lightweight running shoes with excellent cushioning',
                'price' => 129.99,
                'stock' => 80,
                'category_id' => $clothing->id,
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400',
            ],

            // Food
            [
                'name' => 'Organic Coffee Beans',
                'description' => 'Premium organic coffee beans, medium roast - 1kg',
                'price' => 18.99,
                'stock' => 120,
                'category_id' => $food->id,
                'image' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=400',
            ],
            [
                'name' => 'Green Tea Set',
                'description' => 'Premium Japanese green tea collection - 100 bags',
                'price' => 15.99,
                'stock' => 90,
                'category_id' => $food->id,
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400',
            ],

            // Home
            [
                'name' => 'Ceramic Vase',
                'description' => 'Handcrafted ceramic vase, perfect for home decoration',
                'price' => 39.99,
                'stock' => 60,
                'category_id' => $home->id,
                'image' => 'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?w=400',
            ],
            [
                'name' => 'Throw Pillow Set',
                'description' => 'Soft and comfortable throw pillows - Set of 4',
                'price' => 59.99,
                'stock' => 75,
                'category_id' => $home->id,
                'image' => 'https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?w=400',
            ],
            [
                'name' => 'Wall Clock',
                'description' => 'Modern minimalist wall clock with silent movement',
                'price' => 44.99,
                'stock' => 45,
                'category_id' => $home->id,
                'image' => 'https://images.unsplash.com/photo-1563861826100-9cb868fdbe1c?w=400',
            ],

            // Beauty
            [
                'name' => 'Skincare Set',
                'description' => 'Complete skincare routine set with natural ingredients',
                'price' => 89.99,
                'stock' => 65,
                'category_id' => $beauty->id,
                'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400',
            ],
            [
                'name' => 'Makeup Brush Set',
                'description' => 'Professional makeup brush set - 12 pieces',
                'price' => 54.99,
                'stock' => 85,
                'category_id' => $beauty->id,
                'image' => 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=400',
            ],
            [
                'name' => 'Hair Dryer',
                'description' => 'Professional ionic hair dryer with multiple heat settings',
                'price' => 79.99,
                'stock' => 40,
                'category_id' => $beauty->id,
                'image' => 'https://images.unsplash.com/photo-1522338242992-e1a54906a8da?w=400',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Products created successfully!');
    }
}
