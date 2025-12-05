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
    // ==================================================
    // CẤP 1: DANH SÁCH SẢN PHẨM CÓ ĐÁNH GIÁ
    // ==================================================
    public function index(Request $request)
    {
        // Lấy danh sách sản phẩm ĐÃ CÓ đánh giá
        // Kèm theo: Số lượng đánh giá (reviews_count) và Điểm trung bình (reviews_avg_rating)
        $query = Product::has('reviews')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        // Tìm kiếm tên sản phẩm (nếu cần)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest('created_at')->paginate(10);

        return view('admin.reviews.index', compact('products'));
    }

    // ==================================================
    // CẤP 2: XEM CHI TIẾT ĐÁNH GIÁ CỦA 1 SẢN PHẨM
    // ==================================================
    public function show($id, Request $request)
    {
        $product = Product::findOrFail($id);

        // Query đánh giá của sản phẩm này
        $query = Review::with(['user', 'images', 'replies.user', 'variant'])
            ->where('product_id', $id) // Chỉ lấy của sản phẩm này
            ->whereNull('parent_id'); // Chỉ lấy đánh giá gốc

        // --- BỘ LỌC (Filter) ---
        
        // 1. Lọc theo Biến thể
        if ($request->filled('variant_id')) {
            $query->where('variant_id', $request->variant_id);
        }

        // 2. Lọc theo Sao
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // 3. Lọc theo ngày
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $reviews = $query->latest()->cursorPaginate(10)->withQueryString();

        // Lấy danh sách biến thể để làm dropdown filter
        $variants = ProductVariant::where('product_id', $id)->select('id', 'title')->get();

        return view('admin.reviews.show', compact('product', 'reviews', 'variants'));
    }

    // ==================================================
    // CÁC HÀM XỬ LÝ (Store, Update, Destroy) - Giữ nguyên logic
    // ==================================================
    
    public function store(Request $request)
    {
        // Logic giữ nguyên như code cũ của bạn
        $rules = [
            'product_id' => 'required|exists:products,id',
            'content' => 'nullable|string',
            'parent_id' => 'nullable|exists:reviews,id',
        ];

        if (!$request->parent_id) {
            $rules['rating'] = 'required|integer|min:1|max:5';
        }

        $request->validate($rules);

        $content = $request->input('content', '');
        $bannedWords = BannedWord::pluck('word')->toArray();
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

    public function update(Request $request, $id)
    {
        $reply = Review::findOrFail($id);
        if (!$reply->parent_id) {
            return back()->with('error', 'Chỉ có thể sửa phản hồi.');
        }
        $request->validate(['content' => 'required|string']);
        $reply->update(['content' => $request->input('content')]);
        return back()->with('success', 'Đã cập nhật phản hồi!');
    }

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