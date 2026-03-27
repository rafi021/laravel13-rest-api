<?php

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
});
