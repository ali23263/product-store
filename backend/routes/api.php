<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public product and category routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Cart routes
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/items', [CartController::class, 'addItem']);
    Route::put('/cart/items/{cartItem}', [CartController::class, 'updateItem']);
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'removeItem']);
    Route::delete('/cart', [CartController::class, 'clear']);

    // Order routes
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard/stats', [App\Http\Controllers\Admin\DashboardController::class, 'stats']);

        // Categories
        Route::get('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index']);
        Route::post('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store']);
        Route::put('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy']);

        // Products
        Route::get('/products', [App\Http\Controllers\Admin\ProductController::class, 'index']);
        Route::post('/products', [App\Http\Controllers\Admin\ProductController::class, 'store']);
        Route::put('/products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'update']);
        Route::delete('/products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'destroy']);

        // Orders
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index']);
        Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show']);
        Route::put('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus']);

        // Promo Codes
        Route::get('/promo-codes', [App\Http\Controllers\Admin\PromoCodeController::class, 'index']);
        Route::post('/promo-codes', [App\Http\Controllers\Admin\PromoCodeController::class, 'store']);
        Route::post('/promo-codes/generate', [App\Http\Controllers\Admin\PromoCodeController::class, 'generate']);
        Route::put('/promo-codes/{promoCode}', [App\Http\Controllers\Admin\PromoCodeController::class, 'update']);
        Route::delete('/promo-codes/{promoCode}', [App\Http\Controllers\Admin\PromoCodeController::class, 'destroy']);

        // Users
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index']);
        Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show']);
        Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update']);
    });

    // Picker routes
    Route::middleware('role:picker')->prefix('picker')->group(function () {
        Route::get('/orders', [App\Http\Controllers\Picker\OrderController::class, 'index']);
        Route::get('/orders/{order}', [App\Http\Controllers\Picker\OrderController::class, 'show']);
        Route::put('/orders/{order}/status', [App\Http\Controllers\Picker\OrderController::class, 'updateStatus']);
    });
});
