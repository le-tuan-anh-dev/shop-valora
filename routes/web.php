<?php

use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\admin\ReviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\PostController as ClientPostController;



// Trang chủ

Route::get('/', [HomeController::class, 'index'])->name('home');

//shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');


//post
Route::get('/posts', [ClientPostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [ClientPostController::class, 'show'])->name('posts.show');

// đăng ký đăng nhập
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login-form', [AuthController::class, 'showLoginForm'])->name('login.show');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.show');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
// Google
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
// Xác nhận email
Route::get('/verify-email', [AuthController::class, 'verifyEmail'])->name('auth.verify');
Route::get('/login-demo', function () {
    return view('client.login');
});

Route::prefix('products')->group(function () {
    // Hiển thị trang product detail
    Route::get('{id}', [ProductDetailController::class, 'show'])->name('products.detail');

    Route::post('{id}/get-available-attributes', [ProductDetailController::class, 'getAvailableAttributes']);
    Route::post('{id}/get-variant', [ProductDetailController::class, 'getVariant']);
    Route::post('{id}/check-variants', [ProductDetailController::class, 'checkMultipleVariants']);

    // Add to cart
    Route::post('add-to-cart', [ProductDetailController::class, 'addToCart'])->name('cart.add');
});

// Customer  giỏ hàng và checkout
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/remove/{cartItemId}', [CartController::class, 'removeItem'])->name('cart.removeItem');
    Route::post('/cart/update-quantity/{cartItemId}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/cart/total', [CartController::class, 'getCartTotal'])->name('cart.total');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Checkout

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('place-order');
    Route::post('/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('apply-coupon');

    Route::post('/checkout/address', [CheckoutController::class, 'storeAddress'])->name('checkout.store-address');
    Route::put('/checkout/address/{id}', [CheckoutController::class, 'updateAddress'])->name('checkout.update-address');
    Route::delete('/checkout/address/{id}', [CheckoutController::class, 'deleteAddress'])->name('checkout.delete-address');

    // Ordet success
    Route::get('/order-success/{order}', [CheckoutController::class, 'orderSuccess'])->name('order.success');
});

Route::get('/momo/callback', [CheckoutController::class, 'momoCallback'])->name('momo.callback');

// Admin routes - tất cả routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Attributes
    Route::get('attributes', [AttributeController::class, 'index'])->name('admin.attributes.list');
    Route::get('attributes/add', [AttributeController::class, 'create'])->name('admin.attributes.add');
    Route::post('attributes/add', [AttributeController::class, 'store'])->name('admin.attributes.store');
    Route::get('attributes/edit/{id}', [AttributeController::class, 'edit'])->name('admin.attributes.edit');
    Route::put('attributes/edit/{id}', [AttributeController::class, 'update'])->name('admin.attributes.update');
    Route::delete('attributes/delete/{id}', [AttributeController::class, 'destroy'])->name('admin.attributes.delete');

    // Categories
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

    // Comments
    //Comments
     Route::get('/comments', [CommentController::class, 'indexComments'])
    ->name('admin.comments.list');

Route::delete('/comments/{id}', [CommentController::class, 'destroy'])
    ->name('admin.comments.destroy');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('admin.comments.destroy');
    Route::post('/comments/banned-words', [CommentController::class, 'addBannedWord'])->name('admin.comments.banned.add');
    Route::post('/banned-words/{id}', [CommentController::class, 'updateBannedWord'])->name('admin.comments.banned.update');
    Route::delete('/comments/banned-words/{id}', [CommentController::class, 'deleteBannedWord'])->name('admin.comments.banned.delete');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('admin.reviews.store');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('admin.reviews.update');

     // brand
    Route::get('/brands', [BrandController::class, 'index'])->name('admin.brands.index');
    Route::get('/brands/create', [BrandController::class, 'create'])->name('admin.brands.create');
    Route::post('/brands', [BrandController::class, 'store'])->name('admin.brands.store');
    Route::get('/brands/{id}/edit', [BrandController::class, 'edit'])->name('admin.brands.edit');
    Route::put('/brands/{id}', [BrandController::class, 'update'])->name('admin.brands.update');
    Route::delete('/brands/{id}', [BrandController::class, 'destroy'])->name('admin.brands.destroy');

    
    // Users
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.list');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/users/{id}/toggle-lock', [UserController::class, 'toggleLock'])->name('admin.users.toggleLock');
});