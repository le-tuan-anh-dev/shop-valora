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
     * Dựa trên cấu trúc bảng order_items bạn gửi.
     *
     * Lưu ý: giữ lại 'price' nếu code cũ còn dùng.
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
     * Item thuộc về 1 order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}