<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Ai\Enums\Lab;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $posts = Post::query()
            ->with('category')
            ->latest()
            ->paginate(20);
        return view('pages.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::query()->orderBy('name', 'asc')->get();
        return view('pages.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $embeddingText = "Title: {$validated['title']}\n\nContent: {$validated['content']}";

        Post::query()->create($validated + [
            'embedding' => Str::of($embeddingText)->toEmbeddings(
                provider: Lab::Ollama,
                dimensions: 1024,
                model: 'mxbai-embed-large:latest'
            ),
        ]);

        return to_route('posts.index')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $post = Post::query()->with('category')->findOrFail($id);
        return view('pages.posts.edit', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $categories = Category::query()->orderBy('name', 'asc')->get();
        $post = Post::query()->findOrFail($id);
        return view('pages.posts.edit', compact('categories', 'post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePostRequest $request, int $id): RedirectResponse
    {
        $post = Post::query()->findOrFail($id);
        $validated = $request->validated();

        $embeddingText = "Title: {$validated['title']}\n\nContent: {$validated['content']}";

        $post->update($validated + [
            'embedding' => Str::of($embeddingText)->toEmbeddings(
                provider: Lab::Ollama,
                dimensions: 1024,
                model: 'mxbai-embed-large:latest'
            ),
        ]);

        return to_route('posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $post = Post::query()->findOrFail($id);
        $post->delete($id);
        return to_route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
