<?php
namespace App\Models;

use App\Models\Admin\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'max_uses',
        'used_count',
        'per_user_limit',
        'assigned_user_id',
        'applicable_variant_id',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
    ];

    // Relationships
    public function uses()
    {
        return $this->hasMany(VoucherUse::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'applicable_variant_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->active()
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    // Methods
    public function isValid()
    {
        return $this->is_active 
            && $this->used_count < $this->max_uses
            && now()->between($this->starts_at, $this->ends_at);
    }

    public function canUseByUser($userId)
    {
        $userUseCount = $this->uses()
            ->where('user_id', $userId)
            ->count();
        
        return $userUseCount < $this->per_user_limit;
    }

    public function getRemainingUses()
    {
        return $this->max_uses - $this->used_count;
    }

    public function calculateDiscount($subtotal, $variantId = null)
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->applicable_variant_id && $variantId !== $this->applicable_variant_id) {
            return 0;
        }

        if ($this->type === 'fixed') {
            return min($this->value, $subtotal);
        }

        return $subtotal * $this->value / 100;
    }
}