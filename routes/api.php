<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\Products\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    Route::prefix('orders')->group(function () {
        Route::get('', [OrderController::class, 'index']);
        Route::get('recent', [OrderController::class, 'recent']);
        Route::get('{reference_id}', [OrderController::class, 'show']);
    });

    Route::prefix('products')->group(function () {
        Route::get('', [ProductController::class, 'index']);
        Route::get('{product_id}', [ProductController::class, 'show']);
    });

});
