<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Admin\Order;
use App\Models\Admin\OrderItem;
use App\Models\UserAddress;
use App\Models\PaymentMethod;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        
        // lấy giỏ hàng
        $cart = Cart::where('user_id', $user->id)->first();
        
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        // lấy sản phẩm giỏ hàng hàng
        $cartItems = CartItem::where('cart_id', $cart->id)
            ->with(['product', 'variant'])
            ->get();

        if ($cartItems->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        // sản phẩm 
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

        // tính tiền
        $subtotal = $formattedCartItems->sum('total');
        $shipping = 30000;
        $total = $subtotal + $shipping ;

        // lấy địa chỉ
        $shippingAddresses = UserAddress::where('user_id', $user->id)->get();

        // lấy phương thức thanh tonas
        $paymentMethods = PaymentMethod::all();

        // lấy voucher của người dùng này
        // $availableVouchers = $this->getAvailableVouchers($user->id, $subtotal);

        return view('client.check_out', [
            'cartItems' => $formattedCartItems,
            'shippingAddresses' => $shippingAddresses,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'paymentMethods' => $paymentMethods,
            // 'availableVouchers' => $availableVouchers
        ]);
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

    // đặt hàng
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
            
            $cart = Cart::where('user_id', $user->id)->firstOrFail();
            $cartItems = CartItem::where('cart_id', $cart->id)
                ->with(['product', 'variant'])
                ->get();

            if ($cartItems->count() === 0) {
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
            }

            // kiểm tra số lương
            foreach ($cartItems as $item) {
                $product = $item->product;
                $variant = $item->variant;
                
                if ($variant) {
                    if ($variant->stock < $item->quantity) {
                        return redirect()->back()->with('error', 
                            "Sản phẩm '{$product->name}' ({$variant->name}) chỉ còn {$variant->stock} cái!"
                        );
                    }
                } else {
                    if ($product->stock < $item->quantity) {
                        return redirect()->back()->with('error', 
                            "Sản phẩm '{$product->name}' chỉ còn {$product->stock} cái!"
                        );
                    }
                }
            }

            $shippingAddress = UserAddress::where('id', $validated['shipping_address_id'])
                ->where('user_id', $user->id)
                ->firstOrFail();

            // tính tiền
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $price = $item->variant ? $item->variant->price : $item->product->base_price;
                $subtotal += $item->quantity * $price;
            }

            $promotionAmount = 0;
            $promotionId = null;

            $shippingFee = (float) $validated['shipping_fee'];
            $totalAmount = $subtotal - $promotionAmount + $shippingFee;
            $orderNumber = 'ORD-' . time() . '-' . $user->id;

            $paymentMethodId = $validated['payment_method_id'];

            // Cod
            if ($paymentMethodId == 1) {
                $order = $this->createOrder(
                    $user, 
                    $cartItems, 
                    $shippingAddress, 
                    $validated, 
                    $subtotal, 
                    $promotionAmount, 
                    $shippingFee, 
                    $totalAmount, 
                    $promotionId,
                    $orderNumber
                );

                CartItem::where('cart_id', $cart->id)->delete();
                $this->sendOrderEmail($order);

                return redirect()->route('order.success', $order->id)
                    ->with('success', 'Đơn hàng đã được tạo! Vui lòng thanh toán khi nhận hàng.');
            } 
            // momo
            else if ($paymentMethodId == 2) {
                // Lưu thông tin đơn hàng vào session trước
                session(['pending_order' => [
                    'user_id' => $user->id,
                    'cart_id' => $cart->id,
                    'cart_items' => $cartItems->toArray(),
                    'shipping_address' => $shippingAddress->toArray(),
                    'validated' => $validated,
                    'subtotal' => $subtotal,
                    'promotion_amount' => $promotionAmount,
                    'shipping_fee' => $shippingFee,
                    'total_amount' => $totalAmount,
                    'order_number' => $orderNumber,
                ]]);

                // gọi MoMo
                $payUrl = $this->momoPayment($orderNumber, $totalAmount);

                if ($payUrl) {
                    return redirect()->away($payUrl);
                } else {
                    session()->forget('pending_order');
                    return redirect()->back()->with('error', 'Lỗi kết nối MoMo. Vui lòng thử lại.');
                }
            }
            else {
                return redirect()->back()->with('error', 'Phương thức thanh toán không hỗ trợ');
            }

        } catch (\Exception $e) {
            Log::error('Lỗi đặt hàng: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    // Tạo đơn hàng
    private function createOrder($user, $cartItems, $shippingAddress, $validated, $subtotal, $promotionAmount, $shippingFee, $totalAmount, $promotionId, $orderNumber)
    {
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

        foreach ($cartItems as $item) {
            $product = $item->product;
            $variant = $item->variant;
            $price = $variant ? $variant->price : $product->base_price;
            $itemSubtotal = $item->quantity * $price;

            $itemDiscountAmount = 0;
            if ($promotionAmount > 0) {
                $itemDiscountAmount = round(($itemSubtotal / $subtotal) * $promotionAmount);
            }

            $variantAttributes = [];
            if ($variant) {
                $attrs = $variant->attributeValues()->get();
                foreach ($attrs as $attr) {
                    $variantAttributes[] = $attr->value;
                }
            }

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

            $product->decrement('stock', $item->quantity);
            if ($variant) {
                $variant->decrement('stock', $item->quantity);
            }
        }

        return $order;
    }

    // Gửi email đơn hàng
    private function sendOrderEmail($order)
    {
        try {
            $orderItems = OrderItem::where('order_id', $order->id)->get();
            Mail::to($order->customer_email)->send(new OrderConfirmation($order, $orderItems));
            Log::info('Email gửi thành công cho: ' . $order->customer_email);
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email: ' . $e->getMessage());
        }
    }

    // thanh toán momo
    private function momoPayment($orderNumber, $totalAmount)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        
        $partnerCode = env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529');
        $accessKey = env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j');
        $secretKey = env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa');
        
        $orderInfo = "Valora Store";
        $orderId = $orderNumber;
        $redirectUrl = route('momo.callback'); 
        $ipnUrl = route('momo.callback');        
        $amount = (int) $totalAmount;
        $requestId = time() . "";
        $requestType = "captureWallet";
        $extraData = "";

        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Valora Store",
            'storeId' => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        if ($jsonResult && isset($jsonResult['payUrl'])) {
            Log::info('MoMo request success: ' . json_encode($jsonResult));
            return $jsonResult['payUrl'];
        } else {
            Log::error('MoMo request failed: ' . json_encode($jsonResult));
            return null;
        }
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        Log::info("MoMo Response ($httpCode): " . $result);
        return $result;
    }

    
    public function momoCallback(Request $request)
    {
        $resultCode = $request->query('resultCode');
        $orderId = $request->query('orderId');
        $message = $request->query('message');


        // Thanh toán thành công
        if ($resultCode == 0) {
            $pendingOrder = session('pending_order');

            if (!$pendingOrder) {
                return redirect()->route('checkout')->with('error', 'Không tìm thấy đơn hàng');
            }

            try {
                $user = auth()->user();
                
                // tạo đơn hàng
                $order = Order::create([
                    'order_number' => $pendingOrder['order_number'],
                    'user_id' => $user->id,
                    'promotion_id' => null,
                    'customer_name' => $user->name,
                    'customer_phone' => $user->phone ?? '',
                    'customer_email' => $user->email,
                    'customer_address' => $pendingOrder['shipping_address']['address'],
                    'receiver_name' => $pendingOrder['shipping_address']['name'],
                    'receiver_phone' => $pendingOrder['shipping_address']['phone'],
                    'receiver_email' => $user->email,
                    'shipping_address' => $pendingOrder['shipping_address']['address'],
                    'subtotal' => $pendingOrder['subtotal'],
                    'promotion_amount' => $pendingOrder['promotion_amount'],
                    'shipping_fee' => $pendingOrder['shipping_fee'],
                    'total_amount' => $pendingOrder['total_amount'],
                    'payment_method_id' => 2, 
                    'payment_status' => 'paid',   
                    'status' => 'pending',        
                    'note' => $pendingOrder['validated']['note'] ?? null,
                ]);

                // tạo đơn hàng chi tiết
                foreach ($pendingOrder['cart_items'] as $item) {
                    
                    $product = \App\Models\Admin\Product::findOrFail($item['product_id']);
                    $variant = $item['variant_id'] ? \App\Models\Admin\ProductVariant::findOrFail($item['variant_id']) : null;
                    
                    $price = $variant ? $variant->price : $product->base_price;
                    $itemSubtotal = $item['quantity'] * $price;
                    $itemDiscountAmount = 0;

                    if ($pendingOrder['promotion_amount'] > 0) {
                        $itemDiscountAmount = round(($itemSubtotal / $pendingOrder['subtotal']) * $pendingOrder['promotion_amount']);
                    }

                    // Lấy attributes
                    $variantAttributes = [];
                    $productOptions = [];
                    if ($variant) {
                        $attrs = $variant->attributeValues()->get();
                        foreach ($attrs as $attr) {
                            $variantAttributes[] = $attr->value;
                        }

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
                        'quantity' => $item['quantity'],
                        'subtotal' => $itemSubtotal,
                        'discount_amount' => $itemDiscountAmount,
                        'total_price' => $itemSubtotal - $itemDiscountAmount,
                        'product_options' => !empty($productOptions) ? json_encode($productOptions) : null,
                    ]);

                    // trừ số lượng
                    $product->decrement('stock', $item['quantity']);
                    if ($variant) {
                        $variant->decrement('stock', $item['quantity']);
                    }
                }

                // xóa giỏ hàng
                CartItem::where('cart_id', $pendingOrder['cart_id'])->delete();

                // gửi email
                try {
                    $orderItems = OrderItem::where('order_id', $order->id)->get();
                    Mail::to($order->customer_email)->send(new OrderConfirmation($order, $orderItems));
                    
                } catch (\Exception $mailException) {
                    Log::error('Lỗi gửi email: ' . $mailException->getMessage());
                }

                // xóa sestion
                session()->forget('pending_order');

                return redirect()->route('order.success', $order->id)
                    ->with('success', 'Thanh toán thành công! Đơn hàng đã được xác nhận.');

            } catch (\Exception $e) {
                Log::error('Lỗi tạo đơn hàng từ MoMo callback: ' . $e->getMessage());
                session()->forget('pending_order');
                return redirect()->route('checkout')->with('error', 'Lỗi xử lý: ' . $e->getMessage());
            }
        } 
        //  Thanh toán thất bại
        else {
            session()->forget('pending_order');
            return redirect()->route('checkout')
                ->with('error', "Thanh toán thất bại: $message");
        }
    }


    
    public function orderSuccess(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('home')->with('error', 'Không có quyền truy cập');
        }

        try {
            $orderItems = OrderItem::where('order_id', $order->id)->with(['product', 'variant'])->get();
            
            return view('client.order_success', [
                'order' => $order,
                'orderItems' => $orderItems,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

}