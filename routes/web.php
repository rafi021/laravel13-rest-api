<?php

use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\FrontendController;
use App\Http\Controllers\Web\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/blog/{post}', [FrontendController::class, 'show'])->name('front.posts.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('posts', PostController::class);
});

require __DIR__ . '/settings.php';
