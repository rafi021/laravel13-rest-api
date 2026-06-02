<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Ai\Embeddings;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'name' => 'New Jeans',
                'description' => 'Description for Product 1',
                'sku' => 'SKU001',
                'image' => 'https://via.placeholder.com/150',
                'price' => 19.99,
                'stock' => 50,
            ],
            [
                'category_id' => 2,
                'name' => 'Blue Denim',
                'description' => 'Description for Product 2',
                'sku' => 'SKU002',
                'image' => 'https://via.placeholder.com/150',
                'price' => 29.99,
                'stock' => 30,
            ],
            [
                'category_id' => 3,
                'name' => 'Denim Jens',
                'description' => 'Description for Product 3',
                'sku' => 'SKU003',
                'image' => 'https://via.placeholder.com/150',
                'price' => 39.99,
                'stock' => 20,
            ],
            [
                'category_id' => 3,
                'name' => 'Jacket Denim',
                'description' => 'Description for Product 4',
                'sku' => 'SKU004',
                'image' => 'https://via.placeholder.com/150',
                'price' => 49.99,
                'stock' => 15,
            ],
            [
                'category_id' => 3,
                'name' => 'Jacket Blue',
                'description' => 'Description for Product 5',
                'sku' => 'SKU005',
                'image' => 'https://via.placeholder.com/150',
                'price' => 49.99,
                'stock' => 15,
            ],
        ];

        foreach ($products as $product) {
            // Generates a flat 1D array natively
            $embeddingVector = Str::of($product['name'] . ' ' . $product['description'])->toEmbeddings(dimensions: 1536);

            Product::create(
                $product +
                    [
                        'embedding' => $embeddingVector,
                    ]
            );
        }
    }
}
