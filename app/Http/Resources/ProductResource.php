<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($product) {
                return [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'product_price' => $product->price,
                    'product_stock' => $product->stock,
                    'category' => new CategoryResource($product->category),
                ];
            }),
            'links' => [
                "first" => url('/api/products') . "?page=1",
                "last" => url('/api/products') . "?page=" . $this->lastPage(),
                'current_page' => url('/api/products') . "?page=" . $this->currentPage(),
            ],
            'meta' => [
                'total' => $this->collection->count(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
                'last_page' => $this->lastPage(),
                'path' => url('/api/products'),
                'count' => $this->collection->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
            ],
        ];
    }
}
