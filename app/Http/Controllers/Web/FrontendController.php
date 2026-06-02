<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function index(): View
    {
        return view('welcome');
    }

    public function show(Post $post): View
    {
        $post->loadMissing('category');

        return view('post-details', compact('post'));
    }
}
