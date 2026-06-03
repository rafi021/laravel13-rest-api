<?php

namespace App\Ai\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchProduct implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'The primary goal of this tool is to search for products based on a given query.
        It should return a list of products that match the search criteria,
        including relevant details such as product name, description, category, price, and availability.
        ';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $query = trim((string) $request->string('query') ?? '');
        $maxPrice = $request->float('max_price') ?: null;
        $minPrice = $request->float('min_price') ?: null;
        $inStock = $request->boolean('in_stock');

        if (empty($query) || $query === '') {
            return 'Please provide a search query.';
        }

        // Find Product using Meilisearch (Laravel Scout)
        $products = Product::search($query)
            // ->select('id', 'category_id', 'name', 'description', 'price', 'stock')
            // ->with('category')
            ->query(fn($q) => $q->with('category'))
            ->when($maxPrice, function ($search) use ($maxPrice) {
                return $search->where('price', '<=', $maxPrice);
            })
            ->when($minPrice, function ($search) use ($minPrice) {
                return $search->where('price', '>=', $minPrice);
            })
            ->when($inStock, function ($search) {
                return $search->where('stock', '>', 0);
            })
            ->take(10)
            ->get();

        if ($products->isEmpty()) {
            return 'No products found for the given search criteria.';
        }

        return $products->map(
            fn($product) =>
            "Name-> {$product->name} | Category -> {$product->category->name} | \$ -> {$product->price} | Stock: -> {$product->stock} "
        )
            ->implode("\n\n");
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()
                ->description('Product name, description, or category to search for')
                ->required(),
            'max_price' => $schema->number()
                ->description('Optional maximum price filter for the search results'),
            'min_price' => $schema->number()
                ->description('Optional minimum price filter for the search results'),
            'in_stock' => $schema->boolean()
                ->description('Optional filter to return only products that are in stock'),
        ];
    }
}
