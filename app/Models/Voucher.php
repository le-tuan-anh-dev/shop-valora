<?php

namespace App\Models;

use App\Models\Admin\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'max_uses',
        'used_count',
        'per_user_limit',
        'starts_at',
        'ends_at',
        'is_active',
        'apply_all_products',
        'min_order_value',
        'max_discount_value',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Quan hệ N:N với User (thông qua bảng voucher_users)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'voucher_users',
            'voucher_id',
            'user_id'
        )->withTimestamps();
    }

    /**
     * Quan hệ N:N với ProductVariant (thông qua bảng voucher_variants)
     */
    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'voucher_variants',
            'voucher_id',
            'product_variant_id'
        )->withTimestamps();
    }

    /**
     * Quan hệ 1:N với VoucherUse
     */
    public function uses(): HasMany
    {
        return $this->hasMany(VoucherUse::class);
    }

    /**
     * Quan hệ 1:N với VoucherUser
     */
    public function voucherUsers(): HasMany
    {
        return $this->hasMany(VoucherUser::class);
    }

    /**
     * Quan hệ 1:N với VoucherVariant
     */
    public function voucherVariants(): HasMany
    {
        return $this->hasMany(VoucherVariant::class);
    }

    /**
     * Kiểm tra voucher còn hạn sử dụng không
     */
    public function hasRemainingUses(): bool
    {
        return $this->used_count < $this->max_uses;
    }

    /**
     * Kiểm tra voucher còn trong thời gian áp dụng không
     */
    public function isValid(): bool
    {
        $now = now();
        return $this->is_active 
            && $now->between($this->starts_at, $this->ends_at)
            && $this->hasRemainingUses();
    }

    /**
     * Kiểm tra user có được phép dùng voucher không
     */
    public function canBeUsedByUser(int $userId): bool
    {
        // Nếu không có user nào trong danh sách → công khai cho tất cả
        if ($this->users()->count() === 0) {
            return true;
        }

        // Nếu có user trong danh sách → chỉ những user đó được dùng
        return $this->users()->where('users.id', $userId)->exists();
    }

    /**
     * Kiểm tra sản phẩm có được áp dụng voucher không
     */
    public function canBeAppliedToVariant(int $variantId): bool
    {
        // Nếu không có variant nào trong danh sách → áp dụng cho tất cả
        if ($this->variants()->count() === 0) {
            return true;
        }

        // Nếu có variant trong danh sách → chỉ những variant đó được áp dụng
        return $this->variants()->where('product_variants.id', $variantId)->exists();
    }

    /**
     * Lấy số lần user đã dùng voucher này
     */
    public function getUserUsageCount(int $userId): int
    {
        return $this->uses()
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Kiểm tra user có vượt giới hạn dùng không
     */
    public function hasUserExceededLimit(int $userId): bool
    {
        return $this->getUserUsageCount($userId) >= $this->per_user_limit;
    }
}