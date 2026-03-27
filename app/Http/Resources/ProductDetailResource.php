<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'product_id' => $this->id,
                'product_name' => $this->name,
                'product_sku' => $this->sku,
                'product_price' => $this->price,
                'product_stock' => $this->stock,
                'category' => new CategoryResource($this->category),
            ];
    }
}
