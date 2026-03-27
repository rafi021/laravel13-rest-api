<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategroySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Books', 'slug' => 'books'],
            ['name' => 'Clothing', 'slug' => 'clothing'],
            ['name' => 'Home & Kitchen', 'slug' => 'home-kitchen'],
            ['name' => 'Sports & Outdoors', 'slug' => 'sports-outdoors'],
            ['name' => 'Health & Personal Care', 'slug' => 'health-personal-care'],
            ['name' => 'Toys & Games', 'slug' => 'toys-games'],
            ['name' => 'Automotive', 'slug' => 'automotive'],
            ['name' => 'Beauty', 'slug' => 'beauty'],
            ['name' => 'Grocery', 'slug' => 'grocery'],
            ['name' => 'Music', 'slug' => 'music'],
            ['name' => 'Movies & TV', 'slug' => 'movies-tv'],
            ['name' => 'Garden & Outdoor', 'slug' => 'garden-outdoor'],
            ['name' => 'Office Supplies', 'slug' => 'office-supplies'],
            ['name' => 'Pet Supplies', 'slug' => 'pet-supplies'],
        ];
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
