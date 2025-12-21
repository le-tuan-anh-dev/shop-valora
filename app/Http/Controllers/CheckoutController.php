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
use App\Models\VoucherUse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Notifications\OrderStatusChanged;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    protected $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }
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
            if ($item->variant) {
                $price = (float)$item->variant->price;
            } 
            elseif ($item->product->discount_price) {
                $price = (float)$item->product->discount_price;
            }else{
                $price = (float)$item->product->base_price;
            }
            
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

        // tỉnh
        $provinces = $this->getProvinces();
        // lấy địa chỉ: ưu tiên mặc định
        $shippingAddresses = UserAddress::where('user_id', $user->id)
            ->orderByDesc('is_default')
            ->orderByDesc('id')
            ->get();

        // lấy phương thức thanh toán
        $paymentMethods = PaymentMethod::all();


        session()->forget('applied_voucher');
        return view('client.check_out', [
            'cartItems'         => $formattedCartItems,
            'shippingAddresses' => $shippingAddresses,
            'subtotal'          => $subtotal,
            'shipping'          => $shipping,
            'total'             => $total,
            'paymentMethods'    => $paymentMethods,
            'provinces'    => $provinces,
            
        ]);
    }

    // lấy tỉnh
    public function getProvinces()
    {
        try {
            $response = Http::withHeaders([
                'Token' => env('GHN_TOKEN'),
            ])->get('https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/province');

            if ($response->ok() && isset($response->json()['data'])) {
                return $response->json()['data'];
            }
        } catch (\Exception $e) {
            Log::error('GHN getProvinces error: ' . $e->getMessage());
        }

        return [];
    }

    // láy quận huyện
    public function getDistricts(Request $request)
    {
        $provinceId = $request->query('province_id');
        
        if (!$provinceId) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng chọn tỉnh'
            ], 400);
        }

        try {
            $response = Http::withHeaders([
                'Token' => env('GHN_TOKEN'),
            ])->get('https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/district', [
                'province_id' => $provinceId
            ]);

            if ($response->ok() && isset($response->json()['data'])) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy quận/huyện'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi kết nối dữ liệu'
            ], 500);
        }
    }

    //lấy phường xã
    public function getWards(Request $request)
    {
        $districtId = $request->query('district_id');
        
        if (!$districtId) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng chọn quận/huyện'
            ], 400);
        }

        try {
            $response = Http::withHeaders([
                'Token' => env('GHN_TOKEN'),
            ])->get('https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/ward', [
                'district_id' => $districtId
            ]);

            if ($response->ok() && isset($response->json()['data'])) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy phường/xã'
            ], 400);

        } catch (\Exception $e) {
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi kết nối dữ liệu'
            ], 500);
        }
    }

    // lấy phí ship 
    public function getShippingFee(Request $request)
    {
        try {
            $validated = $request->validate([
                'to_district_id' => 'required|numeric',
                'to_ward_code' => 'required|string',
            ]);

            $user = auth()->user();
            $cart = Cart::where('user_id', $user->id)->first();
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng trống'
                ], 400);
            }

            $cartItems = CartItem::where('cart_id', $cart->id)
                ->with(['product', 'variant'])
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng trống'
                ], 400);
            }

            $fromDistrictId = (int)env('GHN_FROM_DISTRICT_ID', 3440);
            $fromWardCode = env('GHN_FROM_WARD_CODE', '13004');

            $items = [];
            $totalWeight = 0;

            foreach ($cartItems as $item) {
                $product = $item->product;
                $variant = $item->variant;

                
                if ($variant) {
                    $weight = $variant->weight ?? $product->weight ?? 1000;
                    $length = $variant->length ?? $product->length ?? 30;
                    $width = $variant->width ?? $product->width ?? 20;
                    $height = $variant->height ?? $product->height ?? 15;
                } else {
                    $weight = $product->weight ?? 1000;
                    $length = $product->length ?? 30;
                    $width = $product->width ?? 20;
                    $height = $product->height ?? 15;
                }

                
                for ($i = 0; $i < $item->quantity; $i++) {
                    $items[] = [
                        'name' => $product->name,
                        'quantity' => 1,
                        'length' => $length,
                        'width' => $width,
                        'height' => $height,
                        'weight' => $weight
                    ];
                }

                $totalWeight += $weight * $item->quantity;
            }

            // Hàng nặng
            $requestBody = [
                'service_type_id' => 2,
                'from_district_id' => (int)$fromDistrictId,
                'from_ward_code' => $fromWardCode,
                'to_district_id' => (int)$validated['to_district_id'],
                'to_ward_code' => $validated['to_ward_code'],
                'weight' => $totalWeight,
                'insurance_value' => 0,
                'coupon' => null,
                'items' => $items
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => env('GHN_TOKEN'),
                'ShopId' => env('GHN_SHOP_ID')
            ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee', $requestBody);

            if ($response->ok()) {
                $data = $response->json();
                
                if (isset($data['code']) && $data['code'] == 200 && isset($data['data']['total'])) {
                    return response()->json([
                        'success' => true,
                        'total' => (int)$data['data']['total']
                    ]);
                } else {
                    $message = $data['message'] ?? 'Không tính được phí vận chuyển';
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Lỗi tính phí giao hàng'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    // lưu địa chỉ 
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
        'name'       => 'required|string|max:255',
        'phone'      => 'required|regex:/^[0-9]{10}$/',
        'province_id' => 'required|numeric',
        'district_id' => 'required|numeric',
        'ward_code'   => 'required|string',
        'address'    => 'required|string|max:500',
        'is_default' => 'nullable|boolean',
        ], [
            'name.required' => 'Tên địa chỉ không được để trống',
            'phone.regex' => 'Số điện thoại không hợp lệ (10 chữ số)',
            'phone.required' => 'Số điện thoại là bắt buộc',
            'province_id.required' => 'Vui lòng chọn tỉnh / thành',
            'district_id.required' => 'Vui lòng chọn quận / huyện',
            'ward_code.required' => 'Vui lòng chọn phường / xã',
            'address.required' => 'Vui lòng nhập đại chỉ cụ thể',
        ]);

        try {
            $userId    = auth()->id();
            $isDefault = $request->boolean('is_default');

            // Nếu chọn làm mặc định, reset các địa chỉ khác về 0
            if ($isDefault) {
                UserAddress::where('user_id', $userId)->update(['is_default' => 0]);
            }

            $address = UserAddress::create([
                'user_id'    => $userId,
                'name'       => $validated['name'],
                'province_id'  => $validated['province_id']??null,
                'district_id'  => $validated['district_id']??null,
                'ward_code'    => $validated['ward_code']??null,
                'phone'      => $validated['phone'],
                'address'    => $validated['address'],
                'is_default' => $isDefault ? 1 : 0,
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
                    'message' => 'Lỗi ' ,
                ], 400);
            }

            return redirect()->back()->with('error', 'Lỗi khi thêm địa chỉ ');
        }
    }

    // dùng voucher
     public function applyVoucher(Request $request)
    {
        
        $request->validate([
            'code' => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0'
        ]);

        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn cần đăng nhập'
                ], 401);
            }
            
            $cart = Cart::where('user_id', $user->id)->first();
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng không tìm thấy'
                ]);
            }
            
            $cartItems = CartItem::where('cart_id', $cart->id)
                ->with(['product', 'variant'])
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng trống'
                ]);
            }

            $subtotal = (float) $request->input('subtotal');
            
            $result = $this->voucherService->applyVoucher(
                $cartItems->toArray(),
                $request->input('code'),
                $user->id,
                $subtotal
            );


            if ($result['success']) {
                session([
                    'applied_voucher' => [
                        'voucher_id' => $result['voucher_id'],
                        'code' => $result['code'],
                        'discount_amount' => $result['discount_amount'],
                        'discount_type' => $result['discount_type'],
                        'discount_value' => $result['discount_value']
                    ]
                ]);
            }

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Apply voucher exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 400);
        }
    }

    public function applyCoupon(Request $request)
    {
        return $this->applyVoucher($request);
    }

    public function removeVoucher(Request $request)
    {
        try {
            $appliedVoucher = session('applied_voucher');
            
            if ($appliedVoucher && isset($appliedVoucher['voucher_id'])) {
                $this->voucherService->removeVoucher($appliedVoucher['voucher_id']);
            }

            session()->forget('applied_voucher');

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa mã voucher'
            ]);

        } catch (\Exception $e) {
            Log::error('Remove voucher error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 400);
        }
    }
    


    // Cập nhật địa chỉ
    public function updateAddress(Request $request, $id)
    {
        $address = UserAddress::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Xóa rule exists:provinces,id vì dùng API GHN
        $validated = $request->validate([
            'edit_name'        => 'required|string|max:255',
            'edit_phone'       => 'required|regex:/^[0-9]{10}$/',
            'edit_province_id' => 'required|numeric',
            'edit_district_id' => 'required|numeric',
            'edit_ward_code'   => 'required|string',
            'edit_address'     => 'required|string|max:500',
            'edit_is_default'  => 'nullable|boolean',
        ], [
            'edit_name.required'        => 'Tên địa chỉ không được để trống',
            'edit_phone.required'       => 'Số điện thoại là bắt buộc',
            'edit_phone.regex'          => 'Số điện thoại không hợp lệ (10 chữ số)',
            'edit_province_id.required' => 'Vui lòng chọn tỉnh / thành',
            'edit_district_id.required' => 'Vui lòng chọn quận / huyện',
            'edit_ward_code.required'   => 'Vui lòng chọn phường / xã',
            'edit_address.required'     => 'Vui lòng nhập địa chỉ cụ thể',
        ]);

        try {
            $userId    = auth()->id();
            $isDefault = $request->boolean('edit_is_default');
            if ($isDefault) {
                UserAddress::where('user_id', $userId)
                    ->where('id', '!=', $id)
                    ->update(['is_default' => 0]);
            }

            $address->update([
                'name'        => $validated['edit_name'],
                'phone'       => $validated['edit_phone'],
                'province_id' => $validated['edit_province_id'],
                'district_id' => $validated['edit_district_id'],
                'ward_code'   => $validated['edit_ward_code'],
                'address'     => $validated['edit_address'],
                'is_default'  => $isDefault ? 1 : 0,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Địa chỉ đã được cập nhật thành công',
                    'address' => $address
                ]);
            }

            return redirect()->back()->with('success', 'Địa chỉ đã được cập nhật thành công');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi khi cập nhật địa chỉ',
                ], 400);
            }

            return redirect()->back()->with('error', 'Lỗi khi cập nhật địa chỉ');
        }
    }

    public function deleteAddress($id)
    {
        $address = UserAddress::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $userId     = auth()->id();
        $wasDefault = (bool) $address->is_default;

        $address->delete();

        // Nếu xóa địa chỉ mặc định -> gán 1 địa chỉ khác làm mặc định (nếu còn)
        if ($wasDefault) {
            $newDefault = UserAddress::where('user_id', $userId)->first();
            if ($newDefault) {
                $newDefault->is_default = 1;
                $newDefault->save();
            }
        }

        return redirect()->back()->with('success', 'Xóa địa chỉ thành công.');
    }

    public function setDefaultAddress($id)
    {
        $userId = auth()->id();

        $address = UserAddress::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        UserAddress::where('user_id', $userId)->update(['is_default' => 0]);

        $address->is_default = 1;
        $address->save();

        return redirect()->back()->with('success', 'Đã đặt địa chỉ mặc định.');
    }

    // đặt hàng
    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'shipping_address_id' => 'required|exists:user_addresses,id',
            'payment_method_id'   => 'required|exists:payment_methods,id',
            'promotion_id'        => 'nullable|exists:vouchers,id',
            'shipping_fee'        => 'required|numeric|min:0',
            'note'                => 'nullable|string|max:500'
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
                    if ($item->variant) {
                    $price = (float)$item->variant->price;
                } 
                elseif ($item->product->discount_price) {
                    $price = (float)$item->product->discount_price;
                }else{
                    $price = (float)$item->product->base_price;
                }
               
                $subtotal += $item->quantity * $price;
            }

            $promotionAmount = 0;
            $promotionId = null;

            if (session()->has('applied_voucher')) {
                $appliedVoucher = session('applied_voucher');
                $promotionAmount = (float) $appliedVoucher['discount_amount'];
                $promotionId = $appliedVoucher['voucher_id'];
            }

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
                $appliedVoucher = session('applied_voucher');
                if ($appliedVoucher && isset($appliedVoucher['voucher_id'])) {
                    VoucherUse::create([
                        'voucher_id' => $appliedVoucher['voucher_id'],
                        'user_id' => auth()->user()->id, 
                        'order_id' => $order->id,
                        'used_at' => now()
                    ]);
                    $voucher = Voucher::find($appliedVoucher['voucher_id']);
                    if ($voucher) {
                        $voucher->increment('used_count');
                    }
                }
                session()->forget('applied_voucher'); 
                return redirect()->route('order.success', $order->id)
                    ->with('success', 'Đơn hàng đã được tạo! Vui lòng thanh toán khi nhận hàng.');
            } 
            // momo
            else if ($paymentMethodId == 2) {
                // Lưu thông tin đơn hàng vào session trước
                session(['pending_order' => [
                    'user_id'          => $user->id,
                    'cart_id'          => $cart->id,
                    'cart_items'       => $cartItems->toArray(),
                    'shipping_address' => $shippingAddress->toArray(),
                    'validated'        => $validated,
                    'subtotal'         => $subtotal,
                    'promotion_amount' => $promotionAmount,
                    'shipping_fee'     => $shippingFee,
                    'total_amount'     => $totalAmount,
                    'order_number'     => $orderNumber,
                    'promotion_amount' => $promotionAmount,
                    'promotion_id' => $promotionId,
                ]]);

                // gọi MoMo
                $payUrl = $this->momoPayment($orderNumber, $totalAmount);

                if ($payUrl) {
                    return redirect()->away($payUrl);
                } else {
                    session()->forget('pending_order');
                    return redirect()->back()->with('error', 'Lỗi kết nối MoMo. Vui lòng thử sau.');
                }
            }
            //vnpay
            else if ($paymentMethodId == 3) {
                // Lưu thông tin đơn hàng vào session trước
                session(['pending_order' => [
                    'user_id'          => $user->id,
                    'cart_id'          => $cart->id,
                    'cart_items'       => $cartItems->toArray(),
                    'shipping_address' => $shippingAddress->toArray(),
                    'validated'        => $validated,
                    'subtotal'         => $subtotal,
                    'promotion_amount' => $promotionAmount,
                    'shipping_fee'     => $shippingFee,
                    'total_amount'     => $totalAmount,
                    'order_number'     => $orderNumber,
                    'promotion_amount' => $promotionAmount,
                    'promotion_id' => $promotionId,
                ]]);

                // gọi VnPay
                $payUrl = $this->vnpayPayment($orderNumber, $totalAmount);

                if ($payUrl) {
                    return redirect()->away($payUrl);
                } else {
                    session()->forget('pending_order');
                    return redirect()->back()->with('error', 'Lỗi kết nối VNPay. Vui lòng thử sau.');
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
            if ($variant ) {
                $price = (float)$variant->price;
            } 
            elseif ($item->product->discount_price) {
                $price = (float)$product->discount_price;
            }else{
                $price = (float)$product->base_price;
            }
            
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
                    if ($variant) {
                        $price = (float)$variant->price;
                    } 
                    elseif ($item->product->discount_price) {
                        $price = (float)$product->discount_price;
                    }else{
                        $price = (float)$product->base_price;
                    }
                   
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

                
                // xóa session
                session()->forget('pending_order');
                session()->forget('applied_voucher');

                return redirect()->route('order.success', $order->id)
                    ->with('success', 'Thanh toán thành công!');

            } catch (\Exception $e) {
                Log::error('Lỗi tạo đơn hàng từ MoMo callback: ' . $e->getMessage());
                session()->forget('pending_order');
                return redirect()->route('checkout')->with('error', 'Lỗi xử lý: ' . $e->getMessage());
            }
        } 
        //  Thanh toán thất bại
        else {
            session()->forget('pending_order');
            session()->forget('pending_order');
            return redirect()->route('checkout')
                ->with('error', "Thanh toán thất bại: $message");
        }
    }

    // thanh toán vnpay
    public function vnpayPayment($orderNumber, $totalAmount ){
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.callback');;
        $vnp_TmnCode = "CC3I4YWJ";
        $vnp_HashSecret = "V1JV39ZXK07JIQIUISQD12V8BN504H00";

        $vnp_TxnRef = $orderNumber;
        $vnp_Amount = $totalAmount * 100 ;
        $vnp_OrderInfo = 'Thanh toán đơn hàng'; 
        $vnp_OrderType = 'billpayment'; 
        $vnp_Locale = 'vn'; 
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $startTime = date("YmdHis");
       

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            // "vnp_ExpireDate"=>$expire
        );
        
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        return $vnp_Url;
    }

    public function vnpayCallback(Request $request){
        $vnp_HashSecret = "V1JV39ZXK07JIQIUISQD12V8BN504H00";
        
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnpSecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);
        $hashdata = "";
        $i = 0;
        
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $orderId = $inputData['vnp_TxnRef'];
        $amount = $inputData['vnp_Amount'] / 100;
        $responseCode = $inputData['vnp_ResponseCode'];

        // Kiểm tra signature
        if ($secureHash == $vnpSecureHash) {
            // Thanh toán thành công
            if ($responseCode == '00') {
                $pendingOrder = session('pending_order');

                if (!$pendingOrder) {
                    return redirect()->route('checkout')->with('error', 'Không tìm thấy đơn hàng');
                }

                try {
                    $user = auth()->user();
                    
                    $order = Order::create([
                        'order_number' => $pendingOrder['order_number'],
                        'user_id' => $user->id,
                        'promotion_id' => $pendingOrder['promotion_id'] ?? null,
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
                        'payment_method_id' => 3,  // VNPay
                        'payment_status' => 'paid',   
                        'status' => 'pending',        
                        'note' => $pendingOrder['validated']['note'] ?? null,
                    ]);

                    // Tạo order items và trừ stock
                    foreach ($pendingOrder['cart_items'] as $item) {
                        $product = \App\Models\Admin\Product::findOrFail($item['product_id']);
                        $variant = $item['variant_id'] ? \App\Models\Admin\ProductVariant::findOrFail($item['variant_id']) : null;
                        
                        if ($variant) {
                            $price = (float)$variant->price;
                        } elseif ($product->discount_price) {
                            $price = (float)$product->discount_price;
                        } else {
                            $price = (float)$product->base_price;
                        }
                    
                        $itemSubtotal = $item['quantity'] * $price;
                        $itemDiscountAmount = 0;

                        if ($pendingOrder['promotion_amount'] > 0) {
                            $itemDiscountAmount = round(($itemSubtotal / $pendingOrder['subtotal']) * $pendingOrder['promotion_amount']);
                        }

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

                        $product->decrement('stock', $item['quantity']);
                        if ($variant) {
                            $variant->decrement('stock', $item['quantity']);
                        }
                    }

                    // Xóa giỏ hàng
                    CartItem::where('cart_id', $pendingOrder['cart_id'])->delete();

                    // Gửi email
                    try {
                        $orderItems = OrderItem::where('order_id', $order->id)->get();
                        Mail::to($order->customer_email)->send(new OrderConfirmation($order, $orderItems));
                    } catch (\Exception $mailException) {
                        Log::error('Lỗi gửi email: ' . $mailException->getMessage());
                    }

                    $appliedVoucher = session('applied_voucher');
                    if ($appliedVoucher && isset($appliedVoucher['voucher_id'])) {
                        VoucherUse::create([
                            'voucher_id' => $appliedVoucher['voucher_id'],
                            'user_id' => auth()->user()->id, 
                            'order_id' => null,
                            'used_at' => now()
                        ]);
                        $voucher = Voucher::find($appliedVoucher['voucher_id']);
                        if ($voucher) {
                            $voucher->increment('used_count');
                        }
                    }
                    session()->forget('pending_order');
                    session()->forget('applied_voucher');

                    return redirect()->route('order.success', $order->id)
                        ->with('success', 'Thanh toán thành công!');

                } catch (\Exception $e) {
                    Log::error('Lỗi xử lý VNPay callback: ' . $e->getMessage());
                    session()->forget('pending_order');
                    return redirect()->route('checkout')->with('error', 'Lỗi xử lý: ' . $e->getMessage());
                }
            } else {
                // Thanh toán thất bại
                session()->forget('pending_order');
                return redirect()->route('checkout')
                    ->with('error', 'Thanh toán thất bại. ' );
            }
        } else {
            // Signature không hợp lệ
            session()->forget('pending_order');
            Log::error('VNPay signature mismatch');
            return redirect()->route('checkout')
                ->with('error', 'Lỗi xác thực. Vui lòng thử lại.');
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



    // Phần của Dashboard
    /**
     * Danh sách đơn hàng của user hiện tại.
     */
    public function myOrders()
    {
        $userId = auth()->id();

        
        $orders = Order::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(10); 

        
        $allOrders = Order::where('user_id', $userId)->get();

        $paymentMethods = PaymentMethod::pluck('name', 'id');

        return view('client.orders.index', [
            'orders'         => $orders,
            'allOrders'      => $allOrders,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function showOrder(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('orders.index')
                ->with('error', 'Bạn không có quyền xem đơn hàng này.');
        }

        $orderItems = OrderItem::where('order_id', $order->id)
            ->with([
                'product.brand',                     // sản phẩm + thương hiệu
                'variant.attributeValues.attribute', // biến thể + giá trị thuộc tính + tên thuộc tính
            ])
            ->get();

        $paymentMethod = PaymentMethod::find($order->payment_method_id);

        return view('client.orders.show', [
            'order'         => $order,
            'orderItems'    => $orderItems,
            'paymentMethod' => $paymentMethod,
        ]);
    }

  public function cancelOrder(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('orders.index')
                ->with('error', 'Bạn không có quyền hủy đơn hàng này.');
        }

        if ($order->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Đơn đã xác nhận nên không thể hủy.');
        }

        DB::transaction(function () use ($order) {
            $items = $order->items()
                ->with(['product', 'variant'])
                ->lockForUpdate()
                ->get();

            foreach ($items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
                if ($item->variant) {
                    $item->variant->increment('stock', $item->quantity);
                }
            }

            $order->update([
                'status'         => 'cancelled_by_customer',
                // 'payment_status' => $order->payment_status === 'paid' ? 'refunded' : $order->payment_status,
                'cancelled_at'   => now(),
            ]);

            $order->user->notify(new OrderStatusChanged($order));
        });

        return redirect()->back()->with('success', 'Đã hủy đơn hàng thành công');
    }
    
}