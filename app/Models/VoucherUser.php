<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherUser extends Model
{
    protected $table = 'voucher_users';

    public $timestamps = false;

    protected $fillable = [
        'voucher_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Quan hệ với Voucher
     */
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    /**
     * Quan hệ với User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}