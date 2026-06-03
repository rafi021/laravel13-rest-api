<?php

use App\Ai\Agents\ChatAgent;
use App\Ai\Agents\ProductSearchAgent;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\FrontendController;
use App\Http\Controllers\Web\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Ai\Responses\StreamableAgentResponse;

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/blog/{post}', [FrontendController::class, 'show'])->name('front.posts.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('posts', PostController::class);
    Route::get('/chat', [FrontendController::class, 'chat'])->name('chat.index');
    Route::get('/chat-stream', [FrontendController::class, 'stream'])->name('chat.stream');
});

require __DIR__ . '/settings.php';
