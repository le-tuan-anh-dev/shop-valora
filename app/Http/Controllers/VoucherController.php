<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function applyVoucher(Request $request)
    {
        $code = $request->input('code');
        $orderTotal = $request->input('order_total');

        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return response()->json(['message' => 'Mã không tồn tại'], 404);
        }

        if (!$voucher->isValid($orderTotal)) {
            return response()->json(['message' => 'Mã không hợp lệ hoặc hết hạn'], 400);
        }

        $discount = $voucher->discountAmount($orderTotal);
        $finalTotal = $orderTotal - $discount;

        return response()->json([
            'message' => 'Áp dụng voucher thành công',
            'discount' => $discount,
            'final_total' => $finalTotal
        ]);
    }
}
  