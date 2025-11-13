<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'promotion_id',
        'shipping_provider_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'receiver_name',
        'receiver_phone',
        'receiver_email',
        'shipping_address',
        'total_amount',
        'subtotal',
        'promotion_amount',
        'shipping_fee',
        'payment_method_id',
        'payment_details',
        'payment_status',
        'status',
        'cancellation_reason_id',
        'note',
        'admin_note',
        'confirmed_at',
        'delivered_at',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'payment_details' => 'array',
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'promotion_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

