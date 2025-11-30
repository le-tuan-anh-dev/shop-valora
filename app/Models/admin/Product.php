<?php

namespace App\Models\Admin;

use App\Models\Admin\Brand;
use App\Models\Admin\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Những user đã yêu thích sản phẩm này.
     * (không bắt buộc dùng ngay, nhưng để quan hệ đầy đủ)
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