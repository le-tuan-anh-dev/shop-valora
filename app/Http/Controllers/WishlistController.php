<?php

namespace App\Http\Controllers;

use App\Models\Admin\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Thêm sản phẩm vào wishlist.
     * Route: POST /wishlist/{product}
     */
    public function store(Product $product, Request $request)
    {
        $user = auth()->user();

        // Thêm sản phẩm mà không xóa các sản phẩm yêu thích khác
        $user->wishlistProducts()->syncWithoutDetaching([$product->id]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào danh sách yêu thích.',
            ]);
        }

        return back()->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích.');
    }

    /**
     * Xóa sản phẩm khỏi wishlist.
     * Route: DELETE /wishlist/{product}
     */
    public function destroy(Product $product, Request $request)
    {
        $user = auth()->user();

        $user->wishlistProducts()->detach($product->id);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích.',
            ]);
        }

        return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích.');
    }

    /**
     * (Tùy chọn) Trang riêng cho Wishlist, nếu sau này muốn tách khỏi Dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        $wishlistProducts = $user->wishlistProducts()->get();

        return view('client.wishlist.index', [
            'wishlistProducts' => $wishlistProducts,
        ]);
    }
}