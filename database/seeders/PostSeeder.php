<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable Scout syncing during seeding
        Post::withoutSyncingToSearch(function () {
            // Generate 100 posts and process them in chunks of 10 for efficiency
            Post::factory()->count(100)->make()->chunk(10)->each(function ($chunk) {
                $inputs = $chunk->map(function ($post) {
                    return "Title: {$post->title}\n\nContent: {$post->content}";
                })->values()->all();

                // Generate embeddings for the batch
                $response = Embeddings::for($inputs)
                    ->dimensions(1024)
                    ->generate(Lab::Ollama, 'mxbai-embed-large:latest');

                // Assign embeddings and save each post
                foreach ($chunk->values() as $index => $post) {
                    $post->embedding = $response->embeddings[$index];
                    $post->save();
                }
            });
        });
    }
}
