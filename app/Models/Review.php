<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'variant_id',
        'rating',
        'content',
        'parent_id',
    ];

    /**
     * Người dùng viết review.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Sản phẩm được review.
     */
    public function product()
    {
        return $this->belongsTo(\App\Models\Admin\Product::class, 'product_id');
    }

    /**
     * Biến thể được review (nếu có).
     */
    public function variant()
    {
        return $this->belongsTo(\App\Models\Admin\ProductVariant::class, 'variant_id');
    }

    /**
     * Review cha (nếu đây là reply).
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Các review con (reply).
     * 
     * Bạn có thể dùng tên `children` hoặc giữ `replies` tùy style:
     * - $review->replies
     * - $review->children
     */
    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    // Nếu thích tên "children" hơn, có thể thêm:
    // public function children()
    // {
    //     return $this->hasMany(self::class, 'parent_id');
    // }
}