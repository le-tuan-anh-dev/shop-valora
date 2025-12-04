<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orders';

    /**
     * Các cột cho phép mass-assign.
     */
    protected $fillable = [
        'order_number',
        'user_id',
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

        'created_at',
        'updated_at',
    ];

    /**
     * Cast kiểu dữ liệu cho một số cột.
     */
    protected $casts = [
        'payment_details'  => 'array',

        'confirmed_at'     => 'datetime',
        'delivered_at'     => 'datetime',
        'completed_at'     => 'datetime',
        'cancelled_at'     => 'datetime',

        'total_amount'     => 'decimal:2',
        'subtotal'         => 'decimal:2',
        'promotion_amount' => 'decimal:2',
        'shipping_fee'     => 'decimal:2',
    ];

    /**
     * 1 đơn hàng có nhiều item (App\Models\OrderItem).
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Đơn hàng thuộc một phương thức thanh toán.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    /**
     * Đơn hàng thuộc về 1 user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope tiện cho Dashboard: lọc theo user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}