<?php

use App\Models\Product;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('find-product {query}', function (string $query) {
    $products = Product::query()
        ->whereVectorSimilarTo('embedding', Str::of($query)->toEmbeddings(dimensions: 1536), minSimilarity: 0.5)
        ->limit(3)
        ->get();

    if ($products->isEmpty()) {
        $this->error('No products found matching the query.');
        return 1;
    }

    $this->table(
        ['ID', 'Name', 'Description', 'Price', 'Stock'],
        $products->map(fn($product) => [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
        ])
    );

    return 0;
})->purpose('Find a product in the database');
