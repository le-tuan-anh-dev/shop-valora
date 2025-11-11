<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BaoCaoThongKeController;

// Trang danh sách sản phẩm admin
Route::view('/', 'admin.products.product-list');

// Trang thống kê
Route::get('/thong-ke', [BaoCaoThongKeController::class, 'index'])->name('thongke.index');