<?php

namespace App\Http\Controllers;


use App\Models\Admin\Product;
use App\Models\admin\ProductImage;
use App\Models\Admin\ProductVariant;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProductDetailController extends Controller
{ 
    


 public function show(int $id): View
    {
        //  Lấy sản phẩm
        $product = Product::find($id);
        
        if (!$product) {
            abort(404, 'Product not found');
        }

        //  Lấy tất cả attributes của sản phẩm
        $attributes = DB::table('variant_attribute_values as vav')
            ->join('product_variants as pv', 'vav.variant_id', '=', 'pv.id')
            ->join('attribute_values as av', 'vav.attribute_value_id', '=', 'av.id')
            ->join('attributes as a', 'av.attribute_id', '=', 'a.id')
            ->where('pv.product_id', $id)
            ->select('a.id', 'a.name', 'av.id as value_id', 'av.value')
            ->distinct()
            ->get()
            ->groupBy('name')
            ->map(function ($group) {
                return [
                    'id' => $group[0]->id,
                    'name' => $group[0]->name,
                    'values' => $group->map(fn($item) => [
                        'id' => $item->value_id,
                        'value' => $item->value
                    ])->toArray()
                ];
            })
            ->values();

        //  Lấy ảnh từ product (image_main)
        $images = [];
        
        // Thêm ảnh main từ product (đây là ảnh chính)
        if ($product->image_main) {
            $images[] = $this->getImageUrl($product->image_main);
        }
        
        $secondaryImages = ProductImage::where('product_id', $id)
        ->pluck('image')
        ->toArray();

        foreach ($secondaryImages as $image) {
            $url = $this->getImageUrl($image);
            if (!in_array($url, $images)) {
                $images[] = $url;
            }
        }
        
        // Nếu không có ảnh, dùng placeholder
        if (empty($images)) {
            $images[] = asset('images/placeholder.jpg');
        }

        //  Lấy tất cả variants với attributes
        $variants = ProductVariant::where('product_id', $id)
            ->with('attributeValues')
            ->get()
            ->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'product_id' => $variant->product_id,
                    'sku' => $variant->sku,
                    'price' => $variant->price,
                    'stock' => $variant->stock,
                    'image_url' => $this->getImageUrl($variant->image_url),
                    'is_active' => $variant->is_active,
                    'attributes' => $variant->attributeValues()
                        ->get()
                        ->groupBy('attribute.name')
                        ->map(fn($values) => $values->pluck('value')->toArray())
                        ->toArray()
                ];
            });
        //  Lấy 4 sản phẩm cùng danh mục (trừ sản phẩm hiện tại)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->where('is_active', true)
            ->limit(4)
            ->get()
            ->map(function ($prod) {
                return [
                    'id' => $prod->id,
                    'name' => $prod->name,
                    'base_price' => $prod->base_price,
                    'discount_price' => $prod->discount_price,
                    'image_main' => $this->getImageUrl($prod->image_main),
                    'category_id' => $prod->category_id,
                    
                ];
            });

        $brand = $product->brand; 
 
        return view('client.product_detail', [
            'product' => $product,
            'attributes' => $attributes,
            'images' => $images,
            'variants' => $variants,
            'relatedProducts' => $relatedProducts,
            'brand' => $brand,
        ]);
}

  
    public function getAvailableAttributes(Request $request, int $id)
    {
        $request->validate([
            'selected_attribute_value_ids' => 'required|array|min:1',
            'selected_attribute_value_ids.*' => 'integer|exists:attribute_values,id'
        ]);

        $selectedIds = $request->selected_attribute_value_ids;

        //  Tìm tất cả variants có những attribute này
        $variantIds = DB::table('variant_attribute_values as vav')
            ->join('product_variants as pv', 'vav.variant_id', '=', 'pv.id')
            ->where('pv.product_id', $id)
            ->whereIn('vav.attribute_value_id', $selectedIds)
            ->distinct()
            ->pluck('pv.id');

        if ($variantIds->isEmpty()) {
            return response()->json([
                'success' => false,
                'error' => 'No variants found',
                'data' => []
            ], 404);
        }

        //  Lấy tất cả attribute values từ những variants này
        $availableAttributes = DB::table('variant_attribute_values as vav')
            ->join('attribute_values as av', 'vav.attribute_value_id', '=', 'av.id')
            ->join('attributes as a', 'av.attribute_id', '=', 'a.id')
            ->whereIn('vav.variant_id', $variantIds)
            ->select('a.id', 'a.name', 'av.id as value_id', 'av.value')
            ->distinct()
            ->get()
            ->groupBy('name')
            ->map(function ($group) {
                return [
                    'id' => $group[0]->id,
                    'name' => $group[0]->name,
                    'values' => $group->map(fn($item) => [
                        'id' => $item->value_id,
                        'value' => $item->value
                    ])->toArray()
                ];
            })
            ->values();

        //  Return JSON
        return response()->json([
            'success' => true,
            'data' => $availableAttributes
        ]);
    }

    /**
     * lấy biến thể thuộc tính (AJAX)
     * - Tìm variant 
     * - Return giá, stock, sku, ảnh
     * - Kiểm tra còn hàng hay không
     */
    public function getVariant(Request $request, int $id)
    {
        $request->validate([
            'attribute_value_ids' => 'required|array|min:1',
            'attribute_value_ids.*' => 'integer|exists:attribute_values,id'
        ]);

        $attributeValueIds = $request->attribute_value_ids;
        $attributeCount = count($attributeValueIds);

        //  Tìm variant có  những attributes này
        
        $variant = ProductVariant::where('product_id', $id)
            ->whereHas('attributeValues', function ($query) use ($attributeValueIds, $attributeCount) {
                $query->whereIn('attribute_values.id', $attributeValueIds)
                    ->select('product_variants.id')
                    ->groupBy('product_variants.id')
                    ->havingRaw('COUNT(DISTINCT attribute_values.id) = ?', [$attributeCount]);
            })
            ->first();

        //  Kiểm tra variant có tồn tại không
        if (!$variant) {
            return response()->json([
                'success' => false,
                'error' => 'Variant combination not found'
            ], 404);
        }

        // Lấy thông tin chi tiết của variant
        $attributeInfo = $variant->attributeValues()
            ->with('attribute')
            ->get()
            ->groupBy('attribute.name')
            ->map(fn($values) => $values->pluck('value')->toArray())
            ->toArray();

        //  Kiểm tra stock 
        $isInStock = $variant->stock > 0 && $variant->is_active;
        $stockStatus = $isInStock 
            ? " {$variant->stock}" 
            : '0';

        //  Return JSON
        return response()->json([
            'success' => true,
            'data' => [
                'variant' => [
                    'id' => $variant->id,
                    'product_id' => $variant->product_id,
                    'sku' => $variant->sku,
                    'price' => (float) $variant->price,
                    'stock' => $variant->stock,
                    'image_url' => $variant->image_url,
                    'is_active' => $variant->is_active
                ],
                'attributes' => $attributeInfo,
                'stock_info' => [
                    'is_in_stock' => $isInStock,
                    'stock_status' => $stockStatus,
                    'available_quantity' => $isInStock ? $variant->stock : 0
                ]
            ]
        ]);
    }
    private function getImageUrl($imagePath)
        {
            if (!$imagePath) {
                return null;
            }
            
            if (str_starts_with($imagePath, 'http')) {
                return $imagePath;
            }
            
            // Chuyển path thành URL
            return asset('storage/' . $imagePath);
        }

    /**
     * kiểm tra nhiều biến thể VARIANTS (AJAX)
     */
    public function checkMultipleVariants(Request $request, int $id)
    {
        $request->validate([
            'combinations' => 'required|array|min:1',
            'combinations.*' => 'array|min:1',
            'combinations.*.*' => 'integer|exists:attribute_values,id'
        ]);

        $results = [];

        foreach ($request->combinations as $combination) {
            $attributeCount = count($combination);

            //  Tìm variant 
            $variant = ProductVariant::where('product_id', $id)
                ->whereHas('attributeValues', function ($query) use ($combination, $attributeCount) {
                    $query->whereIn('attribute_values.id', $combination)
                        ->groupBy('product_variants.id')
                        ->havingRaw('COUNT(DISTINCT attribute_values.id) = ?', [$attributeCount]);
                })
                ->first();

            $isInStock = $variant ? ($variant->stock > 0 && $variant->is_active) : false;

            $results[] = [
                'combination' => $combination,
                'exists' => $variant !== null,
                'is_in_stock' => $isInStock,
                'price' => $variant?->price,
                'stock' => $variant?->stock,
                'sku' => $variant?->sku
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id', 
            'quantity' => 'required|integer|min:1',
            'attribute_value_ids' => 'nullable' 
        ]);

        $product = Product::findOrFail($request->product_id);
        $variantId = $request->variant_id;

        // Sản phẩm có biến thể 
        if ($variantId) {
            $variant = ProductVariant::where('id', $variantId)
                ->where('product_id', $request->product_id)
                ->first();

            if (!$variant) {
                return redirect()->back()->with('error', 'Biến thể không phù hợp');
            }

            if ($variant->stock < $request->quantity) {
                return redirect()->back()->with('error', "Số lượng sản phẩm không đủ");
            }

            if (!$variant->is_active) {
                return redirect()->back()->with('error', 'Biến thể sản phẩm không khả dụng');
            }

            $productStock = $variant->stock;
        } 
        // Sản phẩm không có biến thể 
        else {
            // Kiểm tra sản phẩm tồn tại và còn hàng
            if ($product->stock < $request->quantity) {
                return redirect()->back()->with('error', "Số lượng sản phẩm không đủ");
            }

            if (!$product->is_active) {
                return redirect()->back()->with('error', 'Sản phẩm không khả dụng');
            }

            $productStock = $product->stock;
        }

        // Lấy user_id từ session
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập');
        }

        // Lấy hoặc tạo cart
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $userId,
            ]);
        }

        // Thêm vào cart 
        if ($variantId) {
            // Có variant: kiểm tra theo variant_id
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('variant_id', $variantId)
                ->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $request->quantity;
                
                if ($product->variants()->find($variantId)->stock < $newQuantity) {
                    return redirect()->back()->with('error', "Không đủ sản phẩm trong kho");
                }
                
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'variant_id' => $variantId,
                    'quantity' => $request->quantity
                ]);
            }
        } else {
            // Không có variant: kiểm tra theo product_id, variant_id = NULL
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->whereNull('variant_id')
                ->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $request->quantity;
                
                if ($product->stock < $newQuantity) {
                    return redirect()->back()->with('error', "Không đủ sản phẩm trong kho");
                }
                
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'variant_id' => null,
                    'quantity' => $request->quantity
                ]);
            }
        }

        $cart->touch();
        if ($request->has('buy_now') && $request->buy_now == '1') {
            return redirect()->route('cart.index')->with('success', 'Sản phẩm đã thêm vào giỏ hàng');
        }

        return back()->with('success', 'Thêm sản phẩm vào giỏ hàng thành công');
    }
}