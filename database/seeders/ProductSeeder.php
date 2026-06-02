<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;

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
                'name' => 'Export Quality Denim Jeans',
                'description' => 'Description for Product 1',
                'sku' => 'SKU001',
                'image' => 'https://via.placeholder.com/150',
                'price' => 19.99,
                'stock' => 50,
            ],
            [
                'category_id' => 2,
                'name' => 'Blue Denim Jeans',
                'description' => 'Description for Product 2',
                'sku' => 'SKU002',
                'image' => 'https://via.placeholder.com/150',
                'price' => 29.99,
                'stock' => 30,
            ],
            [
                'category_id' => 3,
                'name' => 'Denim Jeans',
                'description' => 'Description for Product 3',
                'sku' => 'SKU003',
                'image' => 'https://via.placeholder.com/150',
                'price' => 39.99,
                'stock' => 20,
            ],
            [
                'category_id' => 3,
                'name' => 'Denim Jacket',
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
            $text = "Name: " . $product['name'] . "\n\n" .
                "Description: " . $product['description'] . "\n\n" .
                "Category ID: " . $product['category_id'] . "\n\n" .
                "Price: " . $product['price'] . "\n\n" .
                "Stock: " . $product['stock'];

            $response = Embeddings::for([$text])
                ->dimensions(1024)
                ->generate(
                    Lab::Ollama,
                    'mxbai-embed-large:latest'
                );;

            $embeddingVector = $response->embeddings[0];

            // Validate embedding dimensions
            if (count($embeddingVector) !== 1024) {
                throw new \RuntimeException(
                    'Expected 1024 embedding dimensions, got ' . count($embeddingVector) .
                        ' for product: ' . $product['name']
                );
            }

            Product::create(
                $product +
                    [
                        'embedding' => $embeddingVector,
                    ]
            );
        }
    }
}
