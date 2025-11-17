<?php

namespace App\Http\Controllers;

use App\Models\Admin\Category;
use App\Models\Admin\Product;

class HomeController extends Controller
{
public function index()
{
    // Sản phẩm nổi bật ( giảm giá)
    $featuredProducts = Product::whereNotNull('discount_price')
        ->where('is_active', 1)
        ->orderBy('updated_at', 'desc')
        ->take(8)
        ->get();

    // Sản phẩm mới nhất
    $latestProducts = Product::where('is_active', 1)
        ->orderBy('created_at', 'desc')
        ->take(8)
        ->get();

    // sản phẩm bán nhiều nhất
    $bestSellerProducts = Product::where('is_active', 1)
        ->orderBy('sold_count', 'desc')
        ->take(8)
        ->get();

    $diverseProducts = Product::with(['category', 'variants']) 
            ->where('is_active', 1)
            ->withCount('variants') // Đếm số biến thể
            ->having('variants_count', '>', 0) 
            ->orderBy('variants_count', 'desc') 
            ->take(8) 
            ->get();

    $categories = Category::where('is_active', 1)
            ->orderBy('name', 'asc') // Sắp xếp theo tên A-Z
            ->get();

    return view('client.home', compact(
        'featuredProducts',
        'latestProducts',
        'bestSellerProducts',
        'diverseProducts',
        'categories'
    ));
}
}