<?php

namespace App\Http\Controllers;

use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\Attributes;
use App\Models\Admin\ProductVariant;
use App\Models\Admin\VariantAttributeValue;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tất cả danh mục active
        $categories = Category::where('is_active', 1)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('name', 'asc')
            ->get();

        // Lấy giá min/max từ database (lấy giá thực tế: discount_price nếu có, nếu không thì base_price)
        $priceStats = Product::where('is_active', 1)
            ->where('status', 'active')
            ->selectRaw('MIN(COALESCE(discount_price, base_price)) as min_price, MAX(COALESCE(discount_price, base_price)) as max_price')
            ->first();
        
        $minPrice = $priceStats->min_price ?? 0;
        $maxPrice = $priceStats->max_price ?? 1000000;

        // Lấy các thuộc tính đang được sử dụng trong sản phẩm active
        $activeProductIds = Product::where('is_active', 1)
            ->where('status', 'active')
            ->pluck('id');
        
        $activeVariantIds = ProductVariant::whereIn('product_id', $activeProductIds)
            ->where('is_active', 1)
            ->pluck('id');
        
        $usedAttributeValueIds = VariantAttributeValue::whereIn('variant_id', $activeVariantIds)
            ->distinct()
            ->pluck('attribute_value_id');
        
        $attributes = Attributes::with(['values' => function($query) use ($usedAttributeValueIds) {
            $query->whereIn('id', $usedAttributeValueIds);
        }])
        ->whereHas('values', function($q) use ($usedAttributeValueIds) {
            $q->whereIn('id', $usedAttributeValueIds);
        })
        ->get()
        ->map(function($attr) use ($activeProductIds) {
            // Đếm số sản phẩm cho mỗi giá trị thuộc tính
            $attr->values = $attr->values->map(function($value) use ($activeProductIds) {
                $variantIds = VariantAttributeValue::where('attribute_value_id', $value->id)
                    ->pluck('variant_id');
                
                $productIds = ProductVariant::whereIn('id', $variantIds)
                    ->whereIn('product_id', $activeProductIds)
                    ->distinct()
                    ->pluck('product_id');
                
                $value->product_count = $productIds->count();
                return $value;
            })->filter(function($value) {
                return $value->product_count > 0;
            });
            return $attr;
        })->filter(function($attr) {
            return $attr->values->count() > 0;
        });

        // Query sản phẩm
        $query = Product::with(['category', 'variants'])
            ->where('is_active', 1)
            ->where('status', 'active');

        // Filter theo danh mục
        if ($request->filled('category')) {
            $categoryId = $request->input('category');
            $query->where('category_id', $categoryId);
        }

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter theo giá (lọc theo giá thực tế: discount_price nếu có, nếu không thì base_price)
        if ($request->filled('min_price')) {
            $minPrice = $request->input('min_price');
            $query->whereRaw('COALESCE(discount_price, base_price) >= ?', [$minPrice]);
        }

        if ($request->filled('max_price')) {
            $maxPrice = $request->input('max_price');
            $query->whereRaw('COALESCE(discount_price, base_price) <= ?', [$maxPrice]);
        }

        // Filter theo thuộc tính (attribute values)
        if ($request->filled('attributes')) {
            $selectedAttributeValues = is_array($request->input('attributes')) 
                ? $request->input('attributes') 
                : [$request->input('attributes')];
            
            $selectedAttributeValues = array_filter($selectedAttributeValues);
            
            if (!empty($selectedAttributeValues)) {
                $query->whereHas('variants.attributeValues', function($q) use ($selectedAttributeValues) {
                    $q->whereIn('attribute_values.id', $selectedAttributeValues);
                });
            }
        }

        // Sắp xếp
        $sortBy = $request->input('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderByRaw('COALESCE(discount_price, base_price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(discount_price, base_price) DESC');
                break;
            case 'best_selling':
                $query->orderBy('sold_count', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Phân trang
        $products = $query->paginate(12)->withQueryString();

        return view('client.shop', compact('products', 'categories', 'minPrice', 'maxPrice', 'attributes'));
    }
}

