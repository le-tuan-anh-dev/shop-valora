<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;


// Khai báo rõ controller cho client vs admin
use App\Http\Controllers\DashboardController as ClientDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\PostController as ClientPostController;
// Admin controllers
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\WishlistController;





// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');



// Shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');

// Post
Route::get('/posts', [ClientPostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [ClientPostController::class, 'show'])->name('posts.show');
Route::post('/posts/{id}/comment', [ClientPostController::class, 'storeComment'])
    ->name('comments.store')
    ->middleware('auth');
    Route::delete('/comments/{id}', [ClientPostController::class, 'destroyComment'])
    ->name('comments.destroy')
    ->middleware('auth');
Route::post('/posts/{id}/like', [ClientPostController::class, 'toggleLike'])
    ->name('posts.toggleLike')
    ->middleware('auth');
// Đăng ký đăng nhập
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login-form', [AuthController::class, 'showLoginForm'])->name('login.show');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.show');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


// Xác nhận email
Route::get('/verify-email', [AuthController::class, 'verifyEmail'])->name('auth.verify');
Route::get('/login-demo', function () {
    return view('client.login');
});

// chi tiết sản phẩm
Route::prefix('products')->group(function () {
    Route::get('{id}', [ProductDetailController::class, 'show'])->name('products.detail');
    Route::post('{id}/get-available-attributes', [ProductDetailController::class, 'getAvailableAttributes']);
    Route::post('{id}/get-variant', [ProductDetailController::class, 'getVariant']);
    Route::post('{id}/check-variants', [ProductDetailController::class, 'checkMultipleVariants']);
    Route::post('add-to-cart', [ProductDetailController::class, 'addToCart'])->name('cart.add');
});

// Customer routes 
Route::middleware(['auth', 'role:customer'])->group(function () {
    // Dashboard khách hàng
    
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])
        ->name('wishlist.add');

    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])
        ->name('wishlist.remove');
    // ============================
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])
        ->name('client.dashboard');

    Route::post('/dashboard/update-profile', [ClientDashboardController::class, 'updateProfile'])
        ->name('client.dashboard.update-profile');

    // Cart
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
    Route::post('/checkout/address/{id}/set-default', [CheckoutController::class, 'setDefaultAddress'])
        ->name('checkout.set-default-address');

    // Orders (customer)
    Route::get('/orders', [CheckoutController::class, 'myOrders'])->name('orders.index');
    Route::get('/orders/{order}', [CheckoutController::class, 'showOrder'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [CheckoutController::class, 'cancelOrder'])->name('orders.cancel');

    // Order success
    Route::get('/order-success/{order}', [CheckoutController::class, 'orderSuccess'])->name('order.success');
});


// MoMo Callback

Route::get('/momo/callback', [CheckoutController::class, 'momoCallback'])->name('momo.callback');

// Admin routes 
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

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
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.list');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('/products/{id}', [AdminProductController::class, 'show'])->name('admin.products.show');

    // Orders (admin)
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.list');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // Comments
    Route::get('/comments', [CommentController::class, 'indexComments'])
        ->name('admin.comments.list');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])
        ->name('admin.comments.destroy');
    Route::post('/comments/banned-words', [CommentController::class, 'addBannedWord'])
        ->name('admin.comments.banned.add');
    Route::post('/banned-words/{id}', [CommentController::class, 'updateBannedWord'])
        ->name('admin.comments.banned.update');
    Route::delete('/comments/banned-words/{id}', [CommentController::class, 'deleteBannedWord'])
        ->name('admin.comments.banned.delete');

    

 // --- Quản lý Đánh giá (Reviews) ---
    
    // CẤP 1: Danh sách Sản phẩm có đánh giá (Hàm index)
    // URI: /admin/reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('admin.reviews.index');

    // CẤP 2: Xem chi tiết tất cả đánh giá của 1 SẢN PHẨM (Hàm show)
    // {id} ở đây là ID của PRODUCT
    // URI: /admin/reviews/{product_id}
    Route::get('/reviews/{id}', [ReviewController::class, 'show'])->name('admin.reviews.show'); // <--- Đã thêm

    // Trả lời (Store) - Tạo Review/Reply mới
    // URI: /admin/reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('admin.reviews.store');

    // Sửa phản hồi (Update) - {id} là ID của REVIEW/REPLY
    // URI: /admin/reviews/{review_id}
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('admin.reviews.update');
      
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');


    // Posts
  Route::resource('posts', PostController::class)->names('admin.posts');
      Route::post('/post-comments/{comment}/reply', [AdminPostController::class, 'replyComment'])
        ->name('admin.post_comments.reply');
         Route::put('/post-comments/{comment}/update', [AdminPostController::class, 'updateComment'])
        ->name('admin.post_comments.update');
    
    Route::delete('/post-comments/{comment}', [AdminPostController::class, 'deleteComment'])
        ->name('admin.post_comments.delete');
        


    // CKEditor / TinyMCE upload
    Route::post('/tinymce/upload', [AdminPostController::class, 'tinymceUpload'])
        ->withoutMiddleware([VerifyCsrfToken::class])
        ->name('admin.tinymce.upload');

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

    //voucher 
    // Danh sách voucher
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('admin.vouchers.index');
    // Tạo voucher
    Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('admin.vouchers.create');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('admin.vouchers.store');
    // Chỉnh sửa voucher
    Route::get('/vouchers/{voucher}/edit', [VoucherController::class, 'edit'])->name('admin.vouchers.edit');
    Route::put('/vouchers/{voucher}', [VoucherController::class, 'update'])->name('admin.vouchers.update');
    // Xem chi tiết voucher
    Route::get('/vouchers/{voucher}', [VoucherController::class, 'show'])->name('admin.vouchers.show');
    // Xóa voucher
    Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy'])->name('admin.vouchers.destroy');

});