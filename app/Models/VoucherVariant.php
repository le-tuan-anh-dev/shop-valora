<?php

namespace App\Models;

use App\Models\Admin\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherVariant extends Model
{
    protected $table = 'voucher_variants';

    public $timestamps = false;

    protected $fillable = [
        'voucher_id',
        'product_variant_id',
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
     * Quan hệ với ProductVariant
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}