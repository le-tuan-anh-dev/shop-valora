<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherUse;
use App\Models\Admin\Order;
use App\Models\CartItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class VoucherService
{

    public function applyVoucher(array $cartItems, string $code, int $userId, float $subtotal): array
    {
        try {

            // 1. Tìm voucher
            $voucher = Voucher::where('code', strtoupper($code))
                ->orWhere('code', $code)
                ->first();
                
            if (!$voucher) {
                return [
                    'success' => false,
                    'message' => 'Mã voucher không tồn tại'
                ];
            }

            // 2. Kiểm tra voucher còn hạn sử dụng
            if (!$voucher->is_active) {
                return [
                    'success' => false,
                    'message' => 'Mã voucher đã bị vô hiệu hóa'
                ];
            }

            // Kiểm tra ngày
            $now = now();
            if ($now->isBefore($voucher->starts_at)) {
                return [
                    'success' => false,
                    'message' => 'Mã voucher chưa bắt đầu có hiệu lực'
                ];
            }

            if ($now->isAfter($voucher->ends_at)) {
                return [
                    'success' => false,
                    'message' => 'Mã voucher đã hết hạn'
                ];
            }

            // Kiểm tra số lần sử dụng
            if ($voucher->used_count >= $voucher->max_uses) {
                return [
                    'success' => false,
                    'message' => 'Mã voucher đã hết lượt sử dụng'
                ];
            }

            // 3. Kiểm tra user vượt giới hạn
            if ($voucher->hasUserExceededLimit($userId)) {
                return [
                    'success' => false,
                    'message' => "Bạn đã sử dụng hết lượt dùng voucher này"
                ];
            }

            // 4. Kiểm tra tất cả sản phẩm có áp dụng được không
            if (!$voucher->apply_all_products) {
                $allowedVariantIds = $voucher->variants()->pluck('product_variants.id')->toArray();
                
                if (empty($allowedVariantIds)) {
                    return [
                        'success' => false,
                        'message' => 'Voucher này không áp dụng cho sản phẩm nào'
                    ];
                }

                foreach ($cartItems as $item) {
                    $variantId = $item['variant_id'] ?? null;
                    
                    // Nếu sản phẩm là variant, kiểm tra có trong danh sách không
                    if ($variantId) {
                        if (!in_array($variantId, $allowedVariantIds)) {
                            $productName = $item['product']['name'] ?? 'Sản phẩm';
                            return [
                                'success' => false,
                                'message' => "Sản phẩm '{$productName}' không được áp dụng voucher này"
                            ];
                        }
                    } else {
                        // Nếu không có variant, không được áp dụng
                        $productName = $item['product']['name'] ?? 'Sản phẩm';
                        return [
                            'success' => false,
                            'message' => "Sản phẩm '{$productName}' không được áp dụng voucher này"
                        ];
                    }
                }
            }

            // 5. Kiểm tra đơn hàng đạt giá trị tối thiểu
            if ($subtotal < $voucher->min_order_value) {
                $minValue = number_format($voucher->min_order_value, 0, ',', '.');
                return [
                    'success' => false,
                    'message' => "Đơn hàng phải có giá trị từ {$minValue} VND"
                ];
            }

            // 6. Tính toán discount
            $discountAmount = $this->calculateDiscount($subtotal, $voucher);
            
            // Áp dụng max_discount_value nếu có
            if ($voucher->max_discount_value && $discountAmount > $voucher->max_discount_value) {
                $discountAmount = $voucher->max_discount_value;
            }

            return [
                'success' => true,
                'message' => 'Áp dụng mã voucher thành công',
                'voucher_id' => $voucher->id,
                'code' => $voucher->code,
                'discount_amount' => $discountAmount,
                'discount_type' => $voucher->type,
                'discount_value' => $voucher->value
            ];

        } catch (Exception $e) {
            Log::error('Apply voucher error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Tính toán số tiền giảm
     */
    public function calculateDiscount(float $subtotal, Voucher $voucher): float
    {
        if ($voucher->type === 'percent') {
            $discount = ($subtotal * $voucher->value) / 100;
            return round($discount, 2);
        } else { 
            return min((float)$voucher->value, $subtotal);
        }
    }

    /**
     * Confirm sử dụng voucher (lưu vào VoucherUse & cập nhật used_count)
     */
    public function confirmVoucherUsage(int $voucherId, int $userId, int $orderId): bool
    {
        try {
            $voucher = Voucher::find($voucherId);
            if (!$voucher) {
                Log::warning('Voucher not found for confirmation', ['voucher_id' => $voucherId]);
                return false;
            }
            
            // Tạo record VoucherUse
            VoucherUse::create([
                'voucher_id' => $voucherId,
                'user_id' => $userId,
                'order_id' => $orderId,
                'used_at' => now()
            ]);

            // Cập nhật used_count
            $voucher->increment('used_count');

            Log::info('Voucher usage confirmed', [
                'voucher_id' => $voucherId,
                'order_id' => $orderId
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Confirm voucher usage error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa ứng dụng voucher (rollback)
     */
    public function removeVoucher(int $voucherId): bool
    {
        try {
            $voucher = Voucher::find($voucherId);
            if ($voucher && $voucher->used_count > 0) {
                $voucher->decrement('used_count');
               
            }
            return true;
        } catch (Exception $e) {
            
            return false;
        }
    }

    /**
     * Lấy thông tin voucher theo code
     */
    public function getVoucherDetails(string $code): ?array
    {
        $voucher = Voucher::where('code', $code)->first();
        
        if (!$voucher) {
            return null;
        }

        return [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'type' => $voucher->type,
            'value' => $voucher->value,
            'min_order_value' => $voucher->min_order_value,
            'max_discount_value' => $voucher->max_discount_value,
            'description' => $this->getVoucherDescription($voucher),
            'remaining_uses' => $voucher->max_uses - $voucher->used_count
        ];
    }

    /**
     * Tạo mô tả voucher
     */
    private function getVoucherDescription(Voucher $voucher): string
    {
        $discountText = $voucher->type === 'percentage' 
            ? "{$voucher->value}%" 
            : number_format($voucher->value, 0, ',', '.') . " VND";
        return "Giảm {$discountText}";
    }
}