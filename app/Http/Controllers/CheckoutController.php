<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Admin\Order;
use App\Models\Admin\OrderItem;
use App\Models\UserAddress;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use App\Models\PaymentMethod;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    //  checkout 
    public function show()
    {
        $user = auth()->user();
        
        // Get cart
        $cart = Cart::where('user_id', $user->id)->first();
        
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        // Get cart items
        $cartItems = CartItem::where('cart_id', $cart->id)
            ->with(['product', 'variant'])
            ->get();

        if ($cartItems->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        // Format cart items
        $formattedCartItems = $cartItems->map(function($item) {
            $price = $item->variant ? $item->variant->price : $item->product->base_price;
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product' => $item->product,
                'variant_id' => $item->variant_id,
                'variant' => $item->variant,
                'quantity' => $item->quantity,
                'price' => $price,
                'total' => $item->quantity * $price,
                'attribute_values' => $item->variant 
                    ? $item->variant->attributeValues()->get()->toArray() 
                    : []
            ];
        });

        // Calculate totals
        $subtotal = $formattedCartItems->sum('total');
        $shipping = 30000;
        $total = $subtotal + $shipping ;

        // Get user addresses
        $shippingAddresses = UserAddress::where('user_id', $user->id)->get();

        // Get payment methods from database
        $paymentMethods = PaymentMethod::all();

        // Get available vouchers for this user
        $availableVouchers = $this->getAvailableVouchers($user->id, $subtotal);

        return view('client.check_out', [
            'cartItems' => $formattedCartItems,
            'shippingAddresses' => $shippingAddresses,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'paymentMethods' => $paymentMethods,
            'availableVouchers' => $availableVouchers
        ]);
    }

    // Get available vouchers for user based on total amount and user eligibility
    private function getAvailableVouchers($userId, $subtotal)
    {
        $now = now();

        $vouchers = Voucher::where('is_active', true)
            ->where(function ($query) use ($userId) {
                // Vouchers applicable to all users or for this specific user
                $query->whereNull('assigned_user_id')
                    ->orWhere('assigned_user_id', $userId);
            })
            ->where(function ($query) use ($now) {
                // Check date range
                $query->where(function ($q) use ($now) {
                    $q->whereNull('starts_at')
                        ->orWhere('starts_at', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('ends_at')
                        ->orWhere('ends_at', '>=', $now);
                });
            })
            ->where('used_count', '<', DB::raw('max_uses'))
            ->get()
            ->filter(function ($voucher) use ($userId) {
                // Check per user limit
                $userUsageCount = VoucherUsage::where('voucher_id', $voucher->id)
                    ->where('user_id', $userId)
                    ->count();

                return $userUsageCount < $voucher->per_user_limit;
            })
            ->map(function ($voucher) use ($subtotal) {
                // Calculate discount for display
                $discount = 0;
                if ($voucher->type === 'percentage') {
                    $discount = round($subtotal * $voucher->value / 100);
                } else if ($voucher->type === 'fixed') {
                    $discount = $voucher->value;
                }

                if ($discount > $subtotal) {
                    $discount = $subtotal;
                }

                return [
                    'id' => $voucher->id,
                    'code' => $voucher->code,
                    'type' => $voucher->type,
                    'value' => $voucher->value,
                    'discount' => $discount,
                    'starts_at' => $voucher->starts_at,
                    'ends_at' => $voucher->ends_at,
                    'is_for_user' => $voucher->assigned_user_id === auth()->id()
                ];
            });

        return $vouchers;
    }

    // lưu địa chỉ 
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        try {
            $address = UserAddress::create([
                'user_id' => auth()->id(),
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'is_default' => 0
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Địa chỉ đã được thêm thành công',
                    'address' => $address
                ]);
            }

            return redirect()->back()->with('success', 'Địa chỉ đã được thêm thành công');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi: ' . $e->getMessage()
                ], 400);
            }

            return redirect()->back()->with('error', 'Lỗi khi thêm địa chỉ: ' . $e->getMessage());
        }
    }

    // Đặt hàng
 public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'shipping_address_id' => 'required|exists:user_addresses,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'promotion_id' => 'nullable|exists:vouchers,id',
            'shipping_fee' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:500'
        ]);

        try {
            $user = auth()->user();
            
            // LẤY GIỎ HÀNG
            $cart = Cart::where('user_id', $user->id)->firstOrFail();
            $cartItems = CartItem::where('cart_id', $cart->id)
                ->with(['product', 'variant'])
                ->get();

            if ($cartItems->count() === 0) {
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
            }
            // CHECK SỐ LƯỢNG 
           foreach ($cartItems as $item) {
                $product = $item->product;
                $variant = $item->variant;
                
                // Nếu có variant, kiểm tra stock của variant
                if ($variant) {
                    if ($variant->stock < $item->quantity) {
                        return redirect()->back()->with('error', 
                            "Sản phẩm '{$product->name}' ({$variant->name}) chỉ còn {$variant->stock} cái, không đủ {$item->quantity} cái bạn yêu cầu!"
                        );
                    }
                } else {
                    // Nếu không có variant, kiểm tra stock của product
                    if ($product->stock < $item->quantity) {
                        return redirect()->back()->with('error', 
                            "Sản phẩm '{$product->name}' chỉ còn {$product->stock} cái, không đủ {$item->quantity} cái bạn yêu cầu!"
                        );
                    }
                }
            }

            //  LẤY ĐỊA CHỈ GIAO HÀNG
            $shippingAddress = UserAddress::where('id', $validated['shipping_address_id'])
                ->where('user_id', $user->id)
                ->firstOrFail();

            //  TÍNH TOÁN TIỀN
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $price = $item->variant ? $item->variant->price : $item->product->base_price;
                $subtotal += $item->quantity * $price;
            }

            //  XỬ LÝ VOUCHER
            $promotionAmount = 0;
            $promotionId = null;
            $voucher = null;

            if ($validated['promotion_id'] ?? null) {
                $voucher = Voucher::findOrFail($validated['promotion_id']);
                
                // Tính tiền giảm
                if ($voucher->type === 'percentage') {
                    $promotionAmount = round($subtotal * $voucher->value / 100);
                } else if ($voucher->type === 'fixed') {
                    $promotionAmount = $voucher->value;
                }

                // Không cho giảm vượt quá subtotal
                if ($promotionAmount > $subtotal) {
                    $promotionAmount = $subtotal;
                }

                $promotionId = $voucher->id;
            }

            //  TÍNH TỔNG TIỀN
            $shippingFee = (float) $validated['shipping_fee'];
            $totalAmount = $subtotal - $promotionAmount + $shippingFee;
            $orderNumber= 'ORD-' . time() . '-' . $user->id;
            //  dd([
            //     'order_number' => $orderNumber,
            //     'user_id' => $user->id,
            //     'promotion_id' => $promotionId,
            //     'customer_name' => $user->name,
            //     'customer_phone' => $user->phone ?? '',
            //     'customer_email' => $user->email,
            //     'customer_address' => $shippingAddress->address,
            //     'receiver_name' => $shippingAddress->name,
            //     'receiver_phone' => $shippingAddress->phone,
            //     'receiver_email' => $user->email,
            //     'shipping_address' => $shippingAddress->address,
            //     'subtotal' => $subtotal,
            //     'promotion_amount' => $promotionAmount,
            //     'shipping_fee' => $shippingFee,
            //     'total_amount' => $totalAmount,
            //     'payment_method_id' => $validated['payment_method_id'],
            //     'payment_status' => 'unpaid',
            //     'status' => 'pending',
            //     'note' => $validated['note'] ?? null,
            //     'cart_items_count' => $cartItems->count(),
            //     'items' => $cartItems->map(function($item) {
            //         return [
            //             'product_name' => $item->product->name,
            //             'product_id' => $item->product_id,
            //             'variant_id' => $item->variant_id,
            //             'quantity' => $item->quantity,
            //             'price' => $item->variant ? $item->variant->price : $item->product->base_price
            //         ];
            //     })
            // ]);
            //  TẠO ĐƠN HÀNG
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'promotion_id' => $promotionId,
                'customer_name' => $user->name,
                'customer_phone' => $user->phone ?? '',
                'customer_email' => $user->email,
                'customer_address' => $shippingAddress->address, 
                'receiver_name' => $shippingAddress->name,
                'receiver_phone' => $shippingAddress->phone,
                'receiver_email' => $user->email,
                'shipping_address' => $shippingAddress->address, 
                'subtotal' => $subtotal,
                'promotion_amount' => $promotionAmount,
                'shipping_fee' => $shippingFee,
                'total_amount' => $totalAmount,
                'payment_method_id' => $validated['payment_method_id'],
                'payment_status' => 'unpaid',
                'status' => 'pending',
                'note' => $validated['note'] ?? null,
            ]);

            //  TẠO CHI TIẾT ĐƠN HÀNG
            foreach ($cartItems as $item) {
                $product = $item->product;
                $variant = $item->variant;
                $price = $variant ? $variant->price : $product->base_price;
                $itemSubtotal = $item->quantity * $price;

                // Tính giảm giá cho từng item (chia tỷ lệ)
                $itemDiscountAmount = 0;
                if ($promotionAmount > 0) {
                    $itemDiscountAmount = round(($itemSubtotal / $subtotal) * $promotionAmount);
                }

                // Lấy attributes của variant
                $variantAttributes = [];
                if ($variant) {
                    $attrs = $variant->attributeValues()->get();
                    foreach ($attrs as $attr) {
                        $variantAttributes[] = $attr->value;
                    }
                }

                // Lấy product_options
                $productOptions = [];
                if ($variant) {
                    $attrs = $variant->attributeValues()->with('attribute')->get();
                    foreach ($attrs as $attr) {
                        $productOptions[] = [
                            'attribute' => $attr->attribute->name ?? 'Unknown',
                            'value' => $attr->value
                        ];
                    }
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $variant->id ?? null,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku ?? '',
                    'product_image' => $product->image_main,
                    'product_description' => $product->description ?? '',
                    'variant_name' => $variant->name ?? null,
                    'variant_sku' => $variant->sku ?? null,
                    'variant_attributes' => !empty($variantAttributes) ? json_encode($variantAttributes) : null,
                    'unit_price' => $price,
                    'quantity' => $item->quantity,
                    'subtotal' => $itemSubtotal,
                    'discount_amount' => $itemDiscountAmount,
                    'total_price' => $itemSubtotal - $itemDiscountAmount,
                    'product_options' => !empty($productOptions) ? json_encode($productOptions) : null,
                ]);
                // Trừ số lượng sản phẩm
                $product->decrement('stock', $item->quantity);

                // Trừ số lượng variant (nếu có)
                if ($variant) {
                    $variant->decrement('stock', $item->quantity);
                }
            }

            // GHI NHẬN SỬ DỤNG VOUCHER
            // if ($voucher) {
            //     VoucherUsage::create([
            //         'voucher_id' => $voucher->id,
            //         'user_id' => $user->id,
            //         'order_id' => $order->id
            //     ]);

            //     $voucher->increment('used_count');
            // }

            //  XÓA GIỎ HÀNG
            CartItem::where('cart_id', $cart->id)->delete();


            // GỬI EMAIL ĐƠN HÀNG
            try {
            $orderItems = OrderItem::where('order_id', $order->id)->get();
            
                // Thử gửi email
                Mail::to($order->customer_email)->send(new OrderConfirmation($order, $orderItems));
                
                Log::info('Email gửi thành công cho: ' . $order->customer_email);
            } catch (\Exception $mailException) {
                // Ghi log lỗi nhưng không block order
                Log::error('Lỗi gửi email: ' . $mailException->getMessage());
                
                // Có thể thêm thông báo cho admin
            }

            
            // TRANG THANH TIANS THÀNH CÔNG
            return redirect()->route('order.success', $order->id)
            ->with('success', 'Đơn hàng đã được tạo thành công!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
    // Apply coupon/voucher
    // public function applyCoupon(Request $request)
    // {
    //     $validated = $request->validate([
    //         'coupon_code' => 'required|string'
    //     ]);

    //     try {
    //         $user = auth()->user();
    //         $cart = Cart::where('user_id', $user->id)->firstOrFail();
    //         $cartItems = CartItem::where('cart_id', $cart->id)
    //             ->with(['product', 'variant'])
    //             ->get();

    //         // Find voucher by code
    //         $voucher = Voucher::where('code', $validated['coupon_code'])
    //             ->where('is_active', true)
    //             ->first();

    //         if (!$voucher) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Mã voucher không hợp lệ hoặc đã hết hạn'
    //             ], 400);
    //         }

    //         // Check if voucher is still available
    //         if ($voucher->used_count >= $voucher->max_uses) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Mã voucher đã hết lượt sử dụng'
    //             ], 400);
    //         }

    //         // Check date range
    //         $now = now();
    //         if ($voucher->starts_at && $now < $voucher->starts_at) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Mã voucher chưa được kích hoạt'
    //             ], 400);
    //         }

    //         if ($voucher->ends_at && $now > $voucher->ends_at) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Mã voucher đã hết hạn'
    //             ], 400);
    //         }

    //         // Check per user limit
    //         $userUsageCount = VoucherUsage::where('voucher_id', $voucher->id)
    //             ->where('user_id', $user->id)
    //             ->count();

    //         if ($userUsageCount >= $voucher->per_user_limit) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Bạn đã sử dụng mã voucher này quá nhiều lần'
    //             ], 400);
    //         }

    //         // Check if voucher is assigned to this user or for all users
    //         if ($voucher->assigned_user_id && $voucher->assigned_user_id !== $user->id) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Mã voucher này không áp dụng cho bạn'
    //             ], 400);
    //         }

    //         // Calculate subtotal
    //         $subtotal = 0;
    //         foreach ($cartItems as $item) {
    //             $price = $item->variant ? $item->variant->price : $item->product->base_price;
    //             $subtotal += $item->quantity * $price;
    //         }

    //         // Calculate discount based on voucher type
    //         $discount = 0;
    //         if ($voucher->type === 'percentage') {
    //             $discount = round($subtotal * $voucher->value / 100);
    //         } else if ($voucher->type === 'fixed') {
    //             $discount = $voucher->value;
    //         }

    //         // Make sure discount doesn't exceed subtotal
    //         if ($discount > $subtotal) {
    //             $discount = $subtotal;
    //         }

    //         $shipping = 30000;
    //         $tax = round(($subtotal - $discount) * 0.1);
    //         $total = $subtotal - $discount + $shipping + $tax;

    //         // Store voucher code in session for order creation
    //         session(['applied_voucher' => [
    //             'id' => $voucher->id,
    //             'code' => $voucher->code,
    //             'discount' => $discount
    //         ]]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Mã voucher áp dụng thành công',
    //             'subtotal' => $subtotal,
    //             'discount' => $discount,
    //             'shipping' => $shipping,
    //             'tax' => $tax,
    //             'total' => $total
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Lỗi: ' . $e->getMessage()
    //         ], 400);
    //     }
    // }

    // Handle payment
    // private function handlePayment($order, $paymentMethod)
    // {
    //     switch ($paymentMethod) {
    //         case 'Thanh toán khi nhận hàng':
    //         case 'COD':
    //         case 'cod':
    //             $order->update(['status' => 'confirmed']);
    //             return redirect()->route('order.success', $order->id)
    //                 ->with('success', 'Đơn hàng của bạn đã được đặt thành công!');
            
    //         case 'Stripe':
    //         case 'stripe':
    //             return redirect()->route('payment.stripe', $order->id);
            
    //         case 'PayPal':
    //         case 'paypal':
    //             return redirect()->route('payment.paypal', $order->id);
            
    //         default:
    //             return redirect()->back()->with('error', 'Phương thức thanh toán không hỗ trợ');
    //     }
    // }


public function orderSuccess(Order $order)
{

    // Kiểm tra xem user có quyền truy cập không
    if ($order->user_id !== auth()->id()) {
        return redirect()->route('home')->with('error', 'Không có quyền truy cập');
    }

    try {
        // Lấy các item trong đơn hàng
        $orderItems = $order->orderItems()->with(['product', 'variant'])->get();
        
    
        
        return view('client.order_success', [
            'order' => $order,
            'orderItems' => $orderItems,
        ]);
    } catch (\Exception $e) {
        dd('Lỗi: ' . $e->getMessage());
    }
}
}