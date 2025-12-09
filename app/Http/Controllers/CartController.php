<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Hiển thị trang giỏ hàng
     */
  public function index()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return redirect()->route('login.show')->with('error', 'Vui lòng đăng nhập');
        }

        $cart = Cart::where('user_id', $userId)->first();
        
        if (!$cart) {
            return view('client.cart', [
                'cartItems' => collect(),
                'total' => 0,
                'itemCount' => 0,
                'subtotal' => 0,
                'shipping' => 30000,
                'discountProducts' => collect()
            ]);
        }

        // ===== LẤY CART ITEMS =====
        $cartItemsModel = CartItem::where('cart_id', $cart->id)
            ->with(['product', 'variant'])
            ->get();

        $cartItems = $cartItemsModel->map(function ($item) {
            $price = 0;
            
            if ($item->variant) {
                $price = (float)$item->variant->price;
            } 
            elseif ($item->product->discount_price) {
                $price = (float)$item->product->discount_price;
            }else{
                $price = (float)$item->product->base_price;
            }

            $total = $price * $item->quantity;

            // Lấy attribute values
            $attributeValues = [];
            
            if ($item->variant_id) {
                $variantAttributes = DB::table('variant_attribute_values')
                    ->where('variant_id', $item->variant_id)
                    ->pluck('attribute_value_id')
                    ->toArray();

                if (!empty($variantAttributes)) {
                    $attributes = DB::table('attribute_values')
                        ->whereIn('id', $variantAttributes)
                        ->get(['id', 'value']);

                    foreach ($attributes as $attr) {
                        $attributeValues[] = [
                            'id' => $attr->id,
                            'value' => $attr->value
                        ];
                    }
                }
            }

            return [
                'id' => $item->id,
                'cart_id' => $item->cart_id,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'quantity' => $item->quantity,
                'product' => $item->product,
                'variant' => $item->variant,
                'price' => $price,
                'total' => $total,
                'attribute_values' => $attributeValues
            ];
        });

        // TÍNH TOÁN TỔNG TIỀN
        $subtotal = $cartItems->sum('total');
        $shipping = 30000;
        $total = $subtotal + $shipping;


        //  LẤY SẢN PHẨM GIẢM GIÁ
        $discountProducts = Product::where('discount_price', '!=', null)
            ->where('discount_price', '!=', '')
            ->where('discount_price', '!=', 0)
            ->where('is_active', 1)
            ->limit(4)
            ->get();

        if ($discountProducts->count() > 0) {
            $discountProducts->each(function ($prod) {
                Log::info('Product: ' . $prod->id . ' | ' . $prod->name . ' | Base: ' . $prod->base_price . ' | Discount: ' . $prod->discount_price);
            });
        }

        $discountProducts = $discountProducts->map(function ($prod) {
            return [
                'id' => $prod->id,
                'name' => $prod->name,
                'base_price' => $prod->base_price,
                'discount_price' => $prod->discount_price,
                'image_main' => $prod->image_main,
                'brand' => $prod->brand_id ?? 'Shop'
            ];
        });

        return view('client.cart', [
            'cart' => $cart,
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'itemCount' => $cartItems->count(),
            'discountProducts' => $discountProducts
        ]);
    }

    /**
     * Cập nhật số lượng item trong cart (AJAX)
     */
    public function updateQuantity(Request $request, $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:20'
        ]);

        try {
            $cartItem = CartItem::with(['product', 'variant'])->findOrFail($cartItemId);

            $maxStock = $cartItem->variant ? $cartItem->variant->stock : $cartItem->product->stock;
            
            if ($request->quantity > $maxStock) {
                return response()->json([
                    'success' => false,
                    'error' => "Chỉ còn {$maxStock} sản phẩm trong kho"
                ], 400);
            }

            $cartItem->update(['quantity' => $request->quantity]);
            if ($cartItem->variant) {
                $price = (float)$cartItem->variant->price;
            } 
            elseif ($cartItem->product->discount_price) {
                $price = (float)$cartItem->product->discount_price;
            }else{
                $price = (float)$cartItem->product->base_price;
            }
            $itemTotal = $price * $request->quantity;

            return response()->json([
                'success' => true,
                'itemTotal' => $itemTotal,
                'itemPrice' => $price
            ]);
        } catch (\Exception $e) {
            Log::error('Update quantity error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Xóa item khỏi cart (AJAX)
     */
    public function removeItem($cartItemId)
    {
        try {
            Log::info('Attempting to remove cart item: ' . $cartItemId);
            
            $cartItem = CartItem::find($cartItemId);
            
            if (!$cartItem) {
                Log::error('Cart item not found: ' . $cartItemId);
                return response()->json([
                    'success' => false,
                    'error' => 'Sản phẩm không tồn tại'
                ], 404);
            }

            $cartItem->delete();
            
            Log::info('Cart item deleted successfully: ' . $cartItemId);

            return response()->json([
                'success' => true,
                'message' => 'Xóa sản phẩm thành công'
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing cart item: ' . $e->getMessage() . ' | Stack: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'error' => 'Không thể xóa sản phẩm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa tất cả items trong cart (AJAX)
     */
    public function clearCart()
    {
        try {
            $userId = session()->get('user_id');
            
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Không tìm thấy user'
                ], 401);
            }

            $cart = Cart::where('user_id', $userId)->first();

            if ($cart) {
                CartItem::where('cart_id', $cart->id)->delete();
                Log::info('Cart cleared for user: ' . $userId);
            }

            return response()->json([
                'success' => true,
                'message' => 'Xóa tất cả thành công'
            ]);
        } catch (\Exception $e) {
            Log::error('Error clearing cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy tổng tiền cart (AJAX)
     */
    public function getCartTotal()
    {
        try {
            $userId = session()->get('user_id');
            
            if (!$userId) {
                return response()->json([
                    'itemCount' => 0,
                    'subtotal' => 0,
                    'shipping' => 0,
                    'total' => 0
                ]);
            }

            $cart = Cart::where('user_id', $userId)->first();

            if (!$cart) {
                return response()->json([
                    'itemCount' => 0,
                    'subtotal' => 0,
                    'shipping' => 0,
                    'total' => 0
                ]);
            }

            $cartItems = CartItem::where('cart_id', $cart->id)
                ->with(['product', 'variant'])
                ->get();

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
               
                $subtotal += $price * $item->quantity;
            }

            $shipping = 30000;
            $total = $subtotal + $shipping;

            return response()->json([
                'itemCount' => $cartItems->count(),
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $total
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting cart total: ' . $e->getMessage());
            return response()->json([
                'itemCount' => 0,
                'subtotal' => 0,
                'shipping' => 0,
                'total' => 0
            ]);
        }
    }
}