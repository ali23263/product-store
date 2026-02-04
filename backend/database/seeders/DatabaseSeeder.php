<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\PromoCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Picker User',
            'email' => 'picker@example.com',
            'password' => Hash::make('password'),
            'role' => 'picker',
        ]);

        User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        // Create categories
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic devices and accessories',
                'image' => 'https://via.placeholder.com/300x200?text=Electronics',
            ],
            [
                'name' => 'Clothing',
                'slug' => 'clothing',
                'description' => 'Fashion and apparel',
                'image' => 'https://via.placeholder.com/300x200?text=Clothing',
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'description' => 'Books and publications',
                'image' => 'https://via.placeholder.com/300x200?text=Books',
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Home decor and garden supplies',
                'image' => 'https://via.placeholder.com/300x200?text=Home+Garden',
            ],
            [
                'name' => 'Sports',
                'slug' => 'sports',
                'description' => 'Sports equipment and gear',
                'image' => 'https://via.placeholder.com/300x200?text=Sports',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create products
        $products = [
            // Electronics
            ['category_id' => 1, 'name' => 'Wireless Headphones', 'price' => 79.99, 'stock' => 50],
            ['category_id' => 1, 'name' => 'Smartphone Case', 'price' => 19.99, 'stock' => 100],
            ['category_id' => 1, 'name' => 'USB-C Cable', 'price' => 12.99, 'stock' => 200],
            ['category_id' => 1, 'name' => 'Portable Charger', 'price' => 34.99, 'stock' => 75],
            
            // Clothing
            ['category_id' => 2, 'name' => 'Cotton T-Shirt', 'price' => 24.99, 'stock' => 120],
            ['category_id' => 2, 'name' => 'Denim Jeans', 'price' => 59.99, 'stock' => 80],
            ['category_id' => 2, 'name' => 'Running Shoes', 'price' => 89.99, 'stock' => 60],
            ['category_id' => 2, 'name' => 'Winter Jacket', 'price' => 129.99, 'stock' => 40],
            
            // Books
            ['category_id' => 3, 'name' => 'The Great Novel', 'price' => 14.99, 'stock' => 150],
            ['category_id' => 3, 'name' => 'Cookbook Deluxe', 'price' => 29.99, 'stock' => 90],
            ['category_id' => 3, 'name' => 'Programming Guide', 'price' => 44.99, 'stock' => 70],
            ['category_id' => 3, 'name' => 'History Encyclopedia', 'price' => 39.99, 'stock' => 55],
            
            // Home & Garden
            ['category_id' => 4, 'name' => 'Table Lamp', 'price' => 49.99, 'stock' => 65],
            ['category_id' => 4, 'name' => 'Throw Pillow Set', 'price' => 34.99, 'stock' => 85],
            ['category_id' => 4, 'name' => 'Garden Tools Set', 'price' => 69.99, 'stock' => 45],
            ['category_id' => 4, 'name' => 'Indoor Plant Pot', 'price' => 19.99, 'stock' => 110],
            
            // Sports
            ['category_id' => 5, 'name' => 'Yoga Mat', 'price' => 29.99, 'stock' => 95],
            ['category_id' => 5, 'name' => 'Dumbbells Set', 'price' => 79.99, 'stock' => 50],
            ['category_id' => 5, 'name' => 'Tennis Racket', 'price' => 119.99, 'stock' => 35],
            ['category_id' => 5, 'name' => 'Water Bottle', 'price' => 14.99, 'stock' => 150],
        ];

        foreach ($products as $product) {
            Product::create([
                'category_id' => $product['category_id'],
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => 'High quality ' . strtolower($product['name']) . ' at an affordable price.',
                'price' => $product['price'],
                'stock' => $product['stock'],
                'image' => 'https://via.placeholder.com/400x400?text=' . urlencode($product['name']),
                'is_active' => true,
            ]);
        }

        // Create promo codes
        PromoCode::create([
            'code' => 'SAVE10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'min_purchase' => 50,
            'max_uses' => 100,
            'used_count' => 0,
            'expires_at' => now()->addMonths(3),
            'is_active' => true,
        ]);

        PromoCode::create([
            'code' => 'FLAT5',
            'discount_type' => 'fixed',
            'discount_value' => 5,
            'min_purchase' => 25,
            'max_uses' => 50,
            'used_count' => 0,
            'expires_at' => now()->addMonths(1),
            'is_active' => true,
        ]);
    }
}
