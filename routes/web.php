<?php

use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminVoucherController;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return view('admin.products.product-list');
});
Route::post('/voucher/apply', [VoucherController::class, 'applyVoucher']);

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('vouchers', AdminVoucherController::class);
});
Route::get('/voucher', [VoucherController::class, 'index']);
Route::get('/admin/voucher', [AdminVoucherController::class, 'index'])->name('vouchers.index');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('customer', AdminCustomerController::class);
});
Route::get('/customer', [CustomerController::class, 'index']);
Route::get('/admin/customer', [AdminCustomerController::class, 'index'])->name('admin.customer.index');

