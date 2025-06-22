<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\Products\ProductController;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::withoutMiddleware('auth:sanctum')->group(function () {
            Route::post('login', [AuthController::class, 'login']);
            Route::post('register', [AuthController::class, 'register']);
        });

        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('', [OrderController::class, 'index']);
        Route::get('recent', [OrderController::class, 'recent']);
        Route::get('{reference_id}', [OrderController::class, 'show']);
    });

    Route::prefix('products')->group(function () {
        Route::get('', [ProductController::class, 'index']);
        Route::get('{product_id}', [ProductController::class, 'show']);
    });

    Route::prefix('sales')->group(function () {
        Route::get('summary', [SalesController::class, 'summary']);
        Route::get('top-products', [SalesController::class, 'topProducts']);
        Route::get('top-variants', [SalesController::class, 'topVariants']);
        Route::get('revenue-over-time', [SalesController::class, 'revenueOverTime']);
    });
});
