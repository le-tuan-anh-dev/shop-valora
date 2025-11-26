<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'product_name',
        'product_sku',
        'product_image',
        'product_description',
        'variant_name',
        'variant_sku',
        'variant_attributes',
        'unit_price',
        'quantity',
        'subtotal',
        'discount_amount',
        'total_price',
        'product_options',
        'note',
    ];

    protected $casts = [
        'variant_attributes' => 'array',
        'product_options' => 'array',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Lấy đơn hàng
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    /**
     * Lấy sản phẩm
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
     /**
     * Lấy variant sản phẩm
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    
     /**
     * Format tiền tệ
     */
    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedDiscountAttribute()
    {
        return number_format($this->discount_amount, 0, ',', '.');
    }

    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 0, ',', '.');
    }
}