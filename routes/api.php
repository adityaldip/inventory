<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\ImportController;
use Illuminate\Support\Facades\Route;

// API Routes
Route::prefix('v1')->group(function () {
    // Posts
    Route::get('posts', [PostController::class, 'index']);
    Route::post('posts', [PostController::class, 'store']);
    Route::get('posts/{post}', [PostController::class, 'show']);
    Route::put('posts/{post}', [PostController::class, 'update']);
    Route::delete('posts/{post}', [PostController::class, 'destroy']);

    // Products
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{product}', [ProductController::class, 'show']);
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);

    // Tags
    Route::get('tags', [TagController::class, 'index']);

    // Image routes
    Route::post('images', [ImageController::class, 'store']);
    Route::delete('images/{image}', [ImageController::class, 'destroy']);

    Route::apiResource('transactions', TransactionController::class)->except(['update', 'destroy']);

    // Import routes
    Route::post('imports', [ImportController::class, 'store']);
    Route::get('imports/{importId}/progress', [ImportController::class, 'progress']);
});
