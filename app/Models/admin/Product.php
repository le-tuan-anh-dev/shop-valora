<?php

namespace App\Models\Admin;

use App\Models\Admin\Brand;
use App\Models\Admin\ProductVariant;
use App\Models\User;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'description',
        'cost_price',
        'base_price',
        'discount_price',
        'stock',
        'image_main',
        'is_active'
    ];

    /**
     * Danh mục của sản phẩm.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Các biến thể (size, màu, ...) của sản phẩm.
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Thương hiệu của sản phẩm.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Review của khách hàng cho sản phẩm này.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'id');
    }

    /**
     * Ảnh phụ của sản phẩm.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Những user đã yêu thích (wishlist) sản phẩm này.
     */
    public function wishedByUsers()
    {
        return $this->belongsToMany(
            User::class,
            'wishlists',
            'product_id',
            'user_id'
        )->withTimestamps();
    }
}