<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    /**
     * Các cột cho phép mass-assign.
     */
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

        // từ model cũ của bạn, để tránh vỡ chỗ khác
        'price',

        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'variant_attributes' => 'array',
        'product_options'    => 'array',

        'unit_price'         => 'decimal:2',
        'subtotal'           => 'decimal:2',
        'discount_amount'    => 'decimal:2',
        'total_price'        => 'decimal:2',
    ];

    /**
     * Item thuộc về 1 order (model chung App\Models\Order).
     */
    public function order()
    {
        // Khóa ngoại là order_id, bảng orders
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Liên kết tới sản phẩm để hoàn kho + hiển thị brand.
     * Ở đây bạn vẫn dùng Product trong namespace Admin (OK).
     */
    public function product()
    {
        return $this->belongsTo(\App\Models\Admin\Product::class, 'product_id');
    }

    /**
     * Liên kết tới biến thể để hoàn kho + thuộc tính (color, size...).
     * Vẫn dùng ProductVariant trong Admin.
     */
    public function variant()
    {
        return $this->belongsTo(\App\Models\Admin\ProductVariant::class, 'variant_id');
    }
}