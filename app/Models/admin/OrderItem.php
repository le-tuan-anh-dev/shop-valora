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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

