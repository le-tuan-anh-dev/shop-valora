<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Review;
use App\Models\admin\ReviewImage;
use App\Models\admin\BannedWord;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // -----------------------------
    // LIST + FILTER (Đã cập nhật)
    // -----------------------------
   public function index(Request $request)
{
    // 1. Query cơ bản
    $query = Review::with(['user', 'images', 'replies.user', 'product', 'variant']); // Eager load thêm 'variant'

    // 2. Lọc theo Product ID (Dropdown)
    if ($request->filled('product_id')) {
        $query->where('product_id', $request->product_id);
    }

    // 3. Lọc theo Variant ID (Dropdown)
    if ($request->filled('variant_id')) {
        $query->where('variant_id', $request->variant_id);
    }

    // 4. Các bộ lọc cũ (Rating, Date)
    if ($request->filled('rating')) {
        $query->where('rating', $request->rating);
    }
    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    // 5. Lấy dữ liệu phân trang
    $reviews = $query->whereNull('parent_id')
        ->latest()
        ->cursorPaginate(10)
        ->withQueryString();

    // --- DỮ LIỆU CHO FILTER FORM ---
    
    // Lấy tất cả sản phẩm (chỉ lấy id và name cho nhẹ)
    $products = Product::select('id', 'name')->get();

    // Lấy biến thể: Chỉ lấy khi người dùng ĐÃ CHỌN một sản phẩm cụ thể
    $variants = [];
    if ($request->filled('product_id')) {
        $variants = ProductVariant::where('product_id', $request->product_id)
                    ->select('id', 'title') // Giả sử cột tên biến thể là 'title'
                    ->get();
    }

    return view('admin.reviews.reviews-list', compact('reviews', 'products', 'variants'));
}

    // -----------------------------
    // STORE (Giữ nguyên logic cũ)
    // -----------------------------
    public function store(Request $request)
    {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'content' => 'nullable|string',
            'parent_id' => 'nullable|exists:reviews,id',
        ];

        if (!$request->parent_id) {
            $rules['rating'] = 'required|integer|min:1|max:5';
        }

        $request->validate($rules);

        // Kiểm từ cấm
        $content = $request->input('content', '');
        $bannedWords = BannedWord::pluck('word')->toArray(); // Tối ưu query
        foreach ($bannedWords as $bad) {
            if (str_contains(strtolower($content), strtolower($bad))) {
                return back()->with('error', "Nội dung chứa từ cấm: $bad");
            }
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'variant_id' => $request->variant_id ?? null,
            'rating' => $request->rating ?? null,
            'content' => $content,
            'parent_id' => $request->parent_id ?? null,
        ]);

        if ($request->hasFile('images') && !$request->parent_id) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('reviews', 'public');
                ReviewImage::create([
                    'review_id' => $review->id,
                    'image_path' => $path
                ]);
            }
        }

        return back()->with('success', $request->parent_id ? 'Phản hồi đã được thêm!' : 'Đánh giá đã được thêm!');
    }

    // -----------------------------
    // UPDATE (Giữ nguyên)
    // -----------------------------
    public function update(Request $request, $id)
    {
        $reply = Review::findOrFail($id);
        if (!$reply->parent_id) {
            return back()->with('error', 'Chỉ có thể sửa phản hồi, không sửa đánh giá gốc.');
        }
        $request->validate(['content' => 'required|string']);
        $reply->update(['content' => $request->input('content')]);
        return back()->with('success', 'Đã cập nhật phản hồi!');
    }

    // -----------------------------
    // DELETE (Giữ nguyên)
    // -----------------------------
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        if (!$review->parent_id) {
            ReviewImage::where('review_id', $review->id)->delete();
            Review::where('parent_id', $review->id)->delete();
        }
        $review->delete();
        return back()->with('success', 'Đã xóa bình luận!');
    }
}