<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $products = [
        //     [
        //         'category_id' => 1,
        //         'name' => 'Export Quality Denim Jeans',
        //         'description' => 'Description for Product 1',
        //         'sku' => 'SKU001',
        //         'image' => 'https://via.placeholder.com/150',
        //         'price' => 19.99,
        //         'stock' => 50,
        //     ],
        //     [
        //         'category_id' => 2,
        //         'name' => 'Blue Denim Jeans',
        //         'description' => 'Description for Product 2',
        //         'sku' => 'SKU002',
        //         'image' => 'https://via.placeholder.com/150',
        //         'price' => 29.99,
        //         'stock' => 30,
        //     ],
        //     [
        //         'category_id' => 3,
        //         'name' => 'Denim Jeans',
        //         'description' => 'Description for Product 3',
        //         'sku' => 'SKU003',
        //         'image' => 'https://via.placeholder.com/150',
        //         'price' => 39.99,
        //         'stock' => 20,
        //     ],
        //     [
        //         'category_id' => 3,
        //         'name' => 'Denim Jacket',
        //         'description' => 'Description for Product 4',
        //         'sku' => 'SKU004',
        //         'image' => 'https://via.placeholder.com/150',
        //         'price' => 49.99,
        //         'stock' => 15,
        //     ],
        //     [
        //         'category_id' => 3,
        //         'name' => 'Jacket Blue',
        //         'description' => 'Description for Product 5',
        //         'sku' => 'SKU005',
        //         'image' => 'https://via.placeholder.com/150',
        //         'price' => 49.99,
        //         'stock' => 15,
        //     ],
        // ];

        // foreach ($products as $product) {
        //     Product::create($product);
        // }

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

        // Sample product words to generate semi-realistic random product names
        $adjectives = ['Premium', 'Wireless', 'Ultra', 'Eco-Friendly', 'Smart', 'Deluxe', 'Essential', 'Classic', 'Portable', 'Pro'];
        $nouns = ['Gadget', 'Pack', 'Kit', 'Device', 'Set', 'Item', 'Solution', 'Gear', 'Companion', 'Selection'];

        for ($i = 1; $i <= 200; $i++) {
            // Pick a random category index (0 to 14)
            $randomCategoryIndex = array_rand($categories);
            $category = $categories[$randomCategoryIndex];

            // Calculate category_id (Electronics index 0 becomes ID 1)
            $categoryId = $randomCategoryIndex + 1;

            // Generate a random product name based on the category
            $randomAdj = $adjectives[array_rand($adjectives)];
            $randomNoun = $nouns[array_rand($nouns)];
            $productName = "{$randomAdj} {$category['name']} {$randomNoun} ";

            Product::create([
                'category_id' => $categoryId,
                'name'        => $productName,
                'price'       => rand(5, 500) + (rand(0, 99) / 100),       // Random price between 5.00 and 500.99
                'stock'       => rand(0, 150),                             // Random stock between 0 and 150
                'description' => 'Description for Product ' . $i,
                'sku' => 'SKU00' . $i,
                'image' => 'https://via.placeholder.com/150',
            ]);
        }
    }
}
