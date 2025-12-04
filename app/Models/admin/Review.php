<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Review extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'variant_id', 'rating', 'content', 'parent_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function variant() {
        return $this->belongsTo(ProductVariant::class);
    }

    public function parent() {
        return $this->belongsTo(Review::class, 'parent_id');
    }

    public function replies() {
        return $this->hasMany(Review::class, 'parent_id');
    }

    public function images() {
        return $this->hasMany(ReviewImage::class);
    }
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
