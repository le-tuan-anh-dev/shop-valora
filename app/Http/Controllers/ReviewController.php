<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Lưu đánh giá sản phẩm từ trang chi tiết đơn hàng.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'order_item_id' => ['required', 'integer', 'exists:order_items,id'],
            'rating'        => ['required', 'integer', 'min:1', 'max:5'],
            'content'       => ['nullable', 'string', 'max:2000'],
        ]);

        // Lấy item kèm order để kiểm tra quyền
        $orderItem = OrderItem::with('order')->findOrFail($data['order_item_id']);

        // Đảm bảo item này thuộc đơn hàng của user hiện tại
        if ($orderItem->order->user_id !== $user->id) {
            abort(403);
        }

        // Chỉ cho đánh giá khi đơn đã hoàn thành / đã giao
        if (!in_array($orderItem->order->status, ['completed', 'delivered'])) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm sau khi đơn hàng hoàn thành.');
        }

        // Kiểm tra đã đánh giá sản phẩm + biến thể này chưa (tránh trùng)
        $alreadyReviewed = Review::where('user_id', $user->id)
            ->where('product_id', $orderItem->product_id)
            ->where('variant_id', $orderItem->variant_id)
            ->whereNull('parent_id')
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        Review::create([
            'user_id'    => $user->id,
            'product_id' => $orderItem->product_id,
            'variant_id' => $orderItem->variant_id,
            'rating'     => $data['rating'],
            'content'    => $data['content'],
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}