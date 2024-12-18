<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('categories', [CategoryController::class, 'index']);          // GET /categories
Route::post('categories', [CategoryController::class, 'store']);         // POST /categories
Route::get('categories/{id}', [CategoryController::class, 'show']);      // GET /categories/{id}
Route::put('categories/{id}', [CategoryController::class, 'update']);    // PUT /categories/{id}
Route::delete('categories/{id}', [CategoryController::class, 'destroy']); // DELETE /categories/{id}
Route::post('categories/bulk', [CategoryController::class, 'storeBulkCategories']); // POST /categories/bulk

Route::get('products', [ProductController::class, 'index']);             // GET /products
Route::post('products', [ProductController::class, 'store']);            // POST /products
Route::get('products/{id}', [ProductController::class, 'show']);         // GET /products/{id}
Route::put('products/{id}', [ProductController::class, 'update']);       // PUT /products/{id}
Route::delete('products/{id}', [ProductController::class, 'destroy']);   // DELETE /products/{id}
Route::post('products/bulk', [ProductController::class, 'storeBulkProducts']); // POST /products/bulk

