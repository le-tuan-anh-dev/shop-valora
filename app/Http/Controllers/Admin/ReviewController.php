<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Review;
use App\Models\admin\ReviewImage;
use App\Models\admin\BannedWord;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // -----------------------------
    // LIST + FILTER
    // -----------------------------
    public function index(Request $request)
    {
        $query = Review::with(['user', 'images', 'replies.user']);
        $query = Review::with(['user', 'images', 'replies.user', 'product']);


        if ($request->rating) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->whereNull('parent_id')
            ->latest()
            ->paginate(10);

        return view('admin.reviews.reviews-list', compact('reviews'));
    }

    // -----------------------------
    // STORE (tạo đánh giá + reply)
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
        foreach (BannedWord::pluck('word') as $bad) {
            if (str_contains(strtolower($content), strtolower($bad))) {
                return back()->with('error', "Nội dung chứa từ cấm: $bad");
            }
        }

        $review = Review::create([
            'user_id' => auth()->id ?? 1,
            'product_id' => $request->product_id,
            'variant_id' => $request->variant_id ?? null,
            'rating' => $request->rating ?? null,
            'content' => $content,
            'parent_id' => $request->parent_id ?? null,
        ]);

        // Ảnh chỉ cho review, không cho reply
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
// UPDATE (chỉ sửa reply)
// -----------------------------
public function update(Request $request, $id)
{
    $reply = Review::findOrFail($id);

    // Chỉ cho phép sửa reply (phải có parent_id)
    if (!$reply->parent_id) {
        return back()->with('error', 'Chỉ có thể sửa phản hồi, không sửa đánh giá gốc.');
    }

    $request->validate([
        'content' => 'required|string'
    ]);

    $reply->update([
        'content' => $request->input('content')
    ]);

    return back()->with('success', 'Đã cập nhật phản hồi!');
}


    // -----------------------------
    // DELETE (xóa review hoặc phản hồi)
    // -----------------------------
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // Nếu là review cha -> xóa luôn replies + images
        if (!$review->parent_id) {
            ReviewImage::where('review_id', $review->id)->delete();
            Review::where('parent_id', $review->id)->delete();
        }

        $review->delete();

        return back()->with('success', 'Đã xóa bình luận!');
    }
}
