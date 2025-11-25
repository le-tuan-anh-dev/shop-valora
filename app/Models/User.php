<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * Các cột cho phép mass-assign.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'date_birth',
        'gender',
        'image',
        'role',              // enum('admin','customer')
        'status',            // enum('active','locked','banned')
        'banned_until',
        'google_id',
        'email_verified_at',
        'phone_verified_at',
        'verification_token',
    ];

    /**
     * Các cột ẩn khi serialize (JSON…)
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * Kiểu dữ liệu đặc biệt.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'banned_until' => 'datetime',
        'date_birth' => 'date',
    ];


    /* -----------------------------------------------------------------
     |  Quan hệ cho Dashboard
     |------------------------------------------------------------------*/

    /**
     * User có nhiều đơn hàng.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * User có nhiều địa chỉ giao hàng.
     */
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    /* -----------------------------------------------------------------
     |  Helper (tuỳ chọn)
     |------------------------------------------------------------------*/

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';

    public function orders()
    {
        return $this->hasMany(\App\Models\Admin\Order::class);

    }
}