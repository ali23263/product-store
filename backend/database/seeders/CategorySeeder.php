<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and gadgets',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=400',
            ],
            [
                'name' => 'Clothing',
                'description' => 'Fashion and apparel',
                'image' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=400',
            ],
            [
                'name' => 'Food',
                'description' => 'Food and beverages',
                'image' => 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=400',
            ],
            [
                'name' => 'Home',
                'description' => 'Home and garden products',
                'image' => 'https://images.unsplash.com/photo-1484101403633-562f891dc89a?w=400',
            ],
            [
                'name' => 'Beauty',
                'description' => 'Beauty and personal care',
                'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=400',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('Categories created successfully!');
    }
}
