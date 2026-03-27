<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->with('category')
            ->latest()
            ->take(12)
            ->get();

        return view('welcome', compact('posts'));
    }

    public function show(Post $post): View
    {
        $post->loadMissing('category');

        return view('post-details', compact('post'));
    }
}
