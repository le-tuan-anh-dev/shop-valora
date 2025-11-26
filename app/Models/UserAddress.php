<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $table = 'user_addresses';

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'is_default',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Địa chỉ thuộc về user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: chỉ địa chỉ của một user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: địa chỉ mặc định.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}