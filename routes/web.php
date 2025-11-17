<?php

use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\admin\ReviewController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// đăng ký đăng nhập
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.show');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.show');
Route::get('/verify-email', [AuthController::class, 'verifyEmail'])->name('auth.verify');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
// Google Login
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::prefix('products')->group(function () {
    // Hiển thị trang product detail 
    Route::get('{id}', [ProductDetailController::class, 'show'])->name('products.detail');
    
    Route::post('{id}/get-available-attributes', [ProductDetailController::class, 'getAvailableAttributes']);
    Route::post('{id}/get-variant', [ProductDetailController::class, 'getVariant']);
    Route::post('{id}/check-variants', [ProductDetailController::class, 'checkMultipleVariants']);
    // Add to cart
    Route::post('add-to-cart', [ProductDetailController::class, 'addToCart'])->name('cart.add');
});


Route::get('/debug-middleware', function () {
    $kernel = app('Illuminate\Contracts\Http\Kernel');
    dd($kernel->getRouteMiddleware());
});
Route::middleware('auth')->middleware('role:customer')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/remove/{cartItemId}', [CartController::class, 'removeItem'])->name('cart.removeItem');
    Route::post('/cart/update-quantity/{cartItemId}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/cart/total', [CartController::class, 'getCartTotal'])->name('cart.total');

    // đăng xuất
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::get('/test-role', function () {
    return Auth::user()->role ?? 'Not logged in';
});

Route::prefix('admin')->middleware(['auth','role:admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');


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


    //Comments
     Route::get('/comments', [CommentController::class, 'index'])->name('admin.comments.list');
     Route::delete('admin/comments/{id}', [CommentController::class, 'destroy'])
    ->name('admin.comments.destroy');
     Route::post('/comments/banned-words', [CommentController::class, 'addBannedWord'])->name('admin.comments.banned.add');
     Route::post('/banned-words/{id}', [CommentController::class, 'updateBannedWord'])->name('admin.comments.banned.update');
    Route::delete('/comments/banned-words/{id}', [CommentController::class, 'deleteBannedWord'])->name('admin.comments.banned.delete');

    //Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('admin.reviews.store');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
        Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('admin.reviews.update');

    // XÓA REVIEW + XÓA REPLY
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
});