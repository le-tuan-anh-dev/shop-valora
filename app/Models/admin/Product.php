<?php

namespace App\Models\Admin;

use App\Models\Admin\Brand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'brand_id', 'name', 'description', 'cost_price', 'base_price', 'discount_price',
        'stock', 'sold_count', 'image_main', 'is_active', 'status'
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
}