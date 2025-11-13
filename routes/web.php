<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;

Route::get('/', function () {
    return view('client.home');
})->name('home');



Route::prefix('admin')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');

    //  Attributes
    Route::get('attributes', [AttributeController::class, 'index'])->name('admin.attributes.list');
    Route::get('attributes/add', [AttributeController::class, 'create'])->name('admin.attributes.add');
    Route::post('attributes/add', [AttributeController::class, 'store'])->name('admin.attributes.store');
    Route::get('attributes/edit/{id}', [AttributeController::class, 'edit'])->name('admin.attributes.edit');
    Route::put('attributes/edit/{id}', [AttributeController::class, 'update'])->name('admin.attributes.update');
    Route::delete('attributes/delete/{id}', [AttributeController::class, 'destroy'])->name('admin.attributes.delete');

    //  Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.list');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.list');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('admin.products.show');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.list');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

});
