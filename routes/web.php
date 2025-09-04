<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
});

// Di web.php
Route::resource('products', ProductController::class);
Route::delete('products/destroy-multiple', [ProductController::class, 'destroyMultiple'])->name('products.destroy-multiple');
Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
Route::patch('products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
Route::get('products-export', [ProductController::class, 'export'])->name('products.export');

    Route::get('produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/{product}', [ProdukController::class, 'show'])->name('produk.show');
    
    // API Routes for AJAX
    Route::get('/api/products', [ProdukController::class, 'apiProducts'])->name('produk.api');
    Route::get('/api/search-suggestions', [ProdukController::class, 'searchSuggestions'])->name('produk.search');

// Category resource routes
    Route::resource('category', CategoryController::class);
    
    // Additional category routes
    Route::post('category/bulk-delete', [CategoryController::class, 'bulkDelete'])
        ->name('category.bulk-delete');
    
    Route::patch('category/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
        ->name('category.toggle-status');
    
    Route::get('category/export/csv', [CategoryController::class, 'export'])
        ->name('category.export');
    
    Route::get('category/stats/data', [CategoryController::class, 'getStats'])
        ->name('category.stats');

// API Routes for AJAX requests
Route::middleware(['auth:sanctum', 'admin'])->prefix('api/admin')->name('api.admin.')->group(function () {
    Route::get('category/stats', [CategoryController::class, 'getStats']);
    Route::patch('category/{category}/toggle-status', [CategoryController::class, 'toggleStatus']);
});

// Atau jika ingin struktur URL yang lebih sederhana:
Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::get('/kategori/api', [KategoriController::class, 'api'])->name('kategori.api');
Route::get('/kategori/{category}', [KategoriController::class, 'show'])->name('kategori.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

Route::resource('admin/dashboard', DashboardController::class);