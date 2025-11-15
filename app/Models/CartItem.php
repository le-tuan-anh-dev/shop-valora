<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'variant_id', 'quantity'];
    public $timestamps = true;
    protected $table = 'cart_items';

    // Relationship với Cart
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Relationship với Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // Relationship với ProductVariant
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    // Accessor để lấy giá
    public function getPrice()
    {
        if ($this->variant) {
            return $this->variant->price;
        }
        return $this->product->base_price ?? 0;
    }

    // Accessor để lấy tổng tiền của item
    public function getTotalPrice()
    {
        return $this->getPrice() * $this->quantity;
    }
}