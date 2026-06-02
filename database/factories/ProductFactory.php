<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        $description = fake()->paragraph();
        $textToEmbed = $name . ' ' . $description;

        $embeddingVector = Embeddings::for([$textToEmbed])
            ->generate()->embeddings;

        return [
            'category_id'   => Category::get()->random()->id,
            'name'          => $name,
            'description'   => $description,
            'sku'           => fake()->uuid(),
            'image'         => fake()->imageUrl(),
            'price'         => fake()->randomFloat(2, 1, 100),
            'stock'         => fake()->numberBetween(0, 100),
            'embedding'     => $embeddingVector, // This will now correctly save as 768 dimensions
        ];
    }
}
