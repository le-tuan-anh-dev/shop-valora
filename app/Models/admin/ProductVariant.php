<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'sku', 'title', 'price', 'stock', 'image_url', 'is_active'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
