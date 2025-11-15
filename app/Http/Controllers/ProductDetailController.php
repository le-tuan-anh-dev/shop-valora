<?php

namespace App\Http\Controllers;

use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProductDetailController extends Controller
{ 
    

    /**
     * hiển thị sản phẩm chi tiết
     * 
     * Route: GET /products/{id}

     */
 public function show(int $id): View
    {
        // : Lấy sản phẩm
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
        
        // Thêm ảnh từ variants nếu có
        $variantImages = ProductVariant::where('product_id', $id)
            ->where('image_url', '!=', null)
            ->distinct()
            ->pluck('image_url')
            ->toArray();
        
        foreach ($variantImages as $image) {
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

    
        Log::info('=== PRODUCT DEBUG ===');
        Log::info('Product:', $product->toArray());
        Log::info('Attributes:', $attributes->toArray());
        Log::info('Images:', $images);
        Log::info('Variants Count:', ['count' => $variants->count()]);
        if ($variants->isNotEmpty()) {
            Log::info('First Variant:', $variants->first());
        }

        //  Return view 
        return view('client.product_detail', [
            'product' => $product,
            'attributes' => $attributes,
            'images' => $images,
            'variants' => $variants,
            'relatedProducts' => $relatedProducts
        ]);
    }

    /**
     * ═══════════════════════════════════════════════════════════════
     * lấy các thuộc tính (AJAX)
     * ═══════════════════════════════════════════════════════════════
     * 
     * Route: POST /products/{id}/get-available-attributes
     * - Tìm tất cả variants có attribute này
     * - Return những attributes khác khả dụng
     */
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
     * 
     * Route: POST /products/{id}/check-variants
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
            'variant_id' => 'required|integer|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'attribute_value_ids' => 'required|min:1'
        ]);

        // Xác minh biến thể thuộc về sản phẩm
        $variant = ProductVariant::where('id', $request->variant_id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$variant) {
            return redirect()->back()->with('error', 'Invalid variant for this product');
        }

                
        if ($variant->stock < $request->quantity) {
            return redirect()->back()->with('error', "Số lượng sản phẩm không đủ");
        }

         
        if (!$variant->is_active) {
            return redirect()->back()->with('error', 'Biến thể sản phẩm không khả dụng');
        }

        // thêm cart
        //  sử dụng session
        $cart = session()->get('cart', []);
        
        $cartKey = "variant_{$variant->id}";
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'variant_id' => $variant->id,
                'product_id' => $request->product_id,
                'product_name' => $variant->product->name,
                'quantity' => $request->quantity,
                'price' => $variant->price,
                'sku' => $variant->sku,
                'image' => $variant->image_url
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Thêm sản phẩm vào giỏ hàng thành công');
    }
}