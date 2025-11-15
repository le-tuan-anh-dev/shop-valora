<?php

namespace App\Http\Controllers;

use App\Models\Admin\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Sản phẩm nổi bật (ví dụ: có giảm giá)
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

        // Best Seller (sản phẩm bán nhiều nhất)
        $bestSellerProducts = Product::where('is_active', 1)
            ->orderBy('sold_count', 'desc')
            ->take(8)
            ->get();

        return view('client.home', compact(
            'featuredProducts',
            'latestProducts',
            'bestSellerProducts'
        ));
    }
}
