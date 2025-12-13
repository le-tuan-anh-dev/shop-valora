<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Attributes;
use App\Models\admin\AttributeValue;
use App\Models\Admin\Brand;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\admin\ProductImage;
use App\Models\Admin\ProductVariant;
use App\Models\Admin\VariantAttributeValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function show($id)
    {
        //  Lấy sản phẩm + Variants + Attributes
        $product = Product::with([
            'category',
            'brand',
            'variants' => function ($q) {
                $q->orderBy('id', 'asc');
            }
        ])->findOrFail($id);

        //  Tính tổng stock từ variants
        $totalVariantStock = $product->variants->sum('stock');

        return view('admin.products.product-detail', compact('product', 'totalVariantStock'));
    }


    public function index(Request $request)
    {
        $query = Product::with('category')->orderBy('updated_at', 'desc');

        // Tìm kiếm theo tên hoặc mô tả
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(10)->withQueryString();

        return view('admin.products.product-list', compact('products'));
    }



    public function create()
    {
        $categories = Category::where('is_active', 1)->get();
        $attributes = Attributes::with('values')->get();
        $brands = Brand::where('is_active', 1)->get();

        return view('admin.products.product-add', compact('categories', 'brands', 'attributes'));
    }

    public function store(Request $request)
    {
        $messages = [
            'category_id.required' => 'Bạn phải chọn danh mục.',
            'category_id.exists'   => 'Danh mục không hợp lệ.',
            'brand_id.exists'      => 'Thương hiệu không hợp lệ.',
            'name.required'        => 'Bạn phải nhập tên sản phẩm.',
            'name.max'             => 'Tên sản phẩm không được quá 150 ký tự.',
            'cost_price.required'  => 'Bạn phải nhập giá nhập.',
            'cost_price.numeric'   => 'Giá nhập phải là số.',
            'cost_price.gt'        => 'Giá nhập phải lớn hơn 0.',
            'base_price.required'  => 'Bạn phải nhập giá bán.',
            'base_price.numeric'   => 'Giá bán phải là số.',
            'base_price.gt'        => 'Giá bán phải lớn hơn giá nhập.',
            'discount_price.numeric' => 'Giá khuyến mãi phải là số.',
            'discount_price.lt'    => 'Giá khuyến mãi phải nhỏ hơn giá bán.',
            'discount_price.gt'    => 'Giá khuyến mãi phải lớn hơn giá nhập.',
            'stock.required'       => 'Bạn phải nhập số lượng tồn kho.',
            'stock.integer'        => 'Tồn kho phải là số nguyên.',
            'stock.min'            => 'Tồn kho không được âm.',
            'variants.*.price.numeric' => 'Giá biến thể phải là số.',
            'variants.*.stock.integer' => 'Tồn kho biến thể phải là số nguyên.',
            'variants.*.stock.min'     => 'Tồn kho biến thể phải lớn hơn không.',
            'variants.*.sku.unique'    => 'SKU biến thể đã tồn tại.',   
            'image_main.required'=>'Ảnh phải dược thêm',
            'description.required'=>'Mô tả không được trống',
            'product_images.max'   => 'Tối đa 5 ảnh',
            'length.max'           => 'Chiều dài phải nhỏ hơn 200 cm.',
            'width.max'            => 'Chiều rộng phải nhỏ hơn 200 cm.',
            'height.max'           => 'Chiều cao phải nhỏ hơn 200 cm.',
            'weight.max'           => 'Cân nặng phải nhỏ hơn 1.600.000 gr.',

            'length.required'      => 'Chiều dài là bắt buộc.',
            'length.numeric'       => 'Chiều dài phải là số.',
            'width.required'       => 'Chiều rộng là bắt buộc.',
            'width.numeric'        => 'Chiều rộng phải là số.',
            'height.required'      => 'Chiều cao là bắt buộc.',
            'height.numeric'       => 'Chiều cao phải là số.',
            'weight.required'      => 'Cân nặng là bắt buộc.',
            'weight.numeric'       => 'Cân nặng phải là số.',
            'variants.*.length.required' => 'Chiều dài biến thể là bắt buộc.',
            'variants.*.width.required'  => 'Chiều rộng biến thể là bắt buộc.',
            'variants.*.height.required' => 'Chiều cao biến thể là bắt buộc.',
            'variants.*.weight.required' => 'Cân nặng biến thể là bắt buộc.',
            'variants.*.length.max'      => 'Chiều dài biến thể phải nhỏ hơn 200 cm.',
            'variants.*.width.max'       => 'Chiều rộng biến thể phải nhỏ hơn 200 cm.',
            'variants.*.height.max'      => 'Chiều cao biến thể phải nhỏ hơn 200 cm.',
            'variants.*.weight.max'      => 'Cân nặng biến thể phải nhỏ hơn 1.600.000 gr.',
        ];

            // Validate dữ liệu
            $rules = [
            'category_id'      => 'required|exists:categories,id',
            'brand_id'         => 'nullable|exists:brands,id',
            'name'            => 'required|string|max:150',
            'description'      => 'required|string',
            'cost_price'       => 'required|numeric|gt:0',
            'base_price'       => 'required|numeric|gt:cost_price',
            'discount_price'   => 'nullable|numeric|lt:base_price|gt:cost_price',
            'stock'            => 'required|integer|min:0',
            'image_main'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_images'   => 'nullable|max:5',
        ];

        $hasVariants = $request->has('variants') && is_array($request->variants) && count($request->variants) > 0;
        if (!$hasVariants) {
            $rules['length']  = 'required|numeric|min:0|max:199.99';
            $rules['width']   = 'required|numeric|min:0|max:199.99';
            $rules['height']  = 'required|numeric|min:0|max:199.99';
            $rules['weight']  = 'required|numeric|min:0|max:1599999.99';
        } else {
            $rules['length']  = 'nullable|numeric|min:0|max:199.99';
            $rules['width']   = 'nullable|numeric|min:0|max:199.99';
            $rules['height']  = 'nullable|numeric|min:0|max:199.99';
            $rules['weight']  = 'nullable|numeric|min:0|max:1599999.99';
            $rules['variants']   = 'required|array|min:1';
            $rules['variants.*.length']  = 'required|numeric|min:0|max:199.99';
            $rules['variants.*.width']   = 'required|numeric|min:0|max:199.99';
            $rules['variants.*.height']  = 'required|numeric|min:0|max:199.99';
            $rules['variants.*.weight']  = 'required|numeric|min:0|max:1599999.99';
            $rules['variants.*.sku']     = 'nullable|string|unique:product_variants,sku';
            $rules['variants.*.price']   = 'required|numeric|gte:cost_price';
            $rules['variants.*.stock']   = 'required|integer|min:0';
        }
        
        $validated = $request->validate($rules, $messages);
        DB::beginTransaction();
        try {
            // Upload ảnh (nếu có)
            $imagePath = null;
            if ($request->hasFile('image_main')) {
                $imagePath = $request->file('image_main')->store('products', 'public');
            }

            //Tạo sản phẩm
            $product = Product::create([
                'category_id'    => $validated['category_id'],
                'brand_id'       => $validated['brand_id'] ?? null,
                'name'           => $validated['name'],
                'description'    => $validated['description'] ?? '',
                'cost_price'     => $validated['cost_price'],
                'base_price'     => $validated['base_price'],
                'discount_price' => $validated['discount_price'] ?? null,
                'is_active'        => $request->has('is_active') ? 1 : 0,
                'length'           => $validated['length'] ?? null,
                'width'            => $validated['width'] ?? null,
                'height'           => $validated['height'] ?? null,
                'weight'           => $validated['weight'] ?? null,
                'stock'          => $validated['stock'],    
                'image_main'     => $imagePath,
                'is_active'      => $request->has('is_active') ? 1 : 0,
            ]);

            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $imageFile) {
                    if ($imageFile) {
                        $imagePath = $imageFile->store('products', 'public');
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image'      => $imagePath,
                        ]);
                    }
                }
            }

            $totalVariantStock = 0;

            //Nếu có biến thể, lưu variants
            if ($request->has('variants') && is_array($request->variants) && count($request->variants) > 0) {
                // Gom tất cả attribute_value_ids từ variants để query một lần
                $allAttributeValueIds = collect($request->variants)
                    ->flatMap(function ($v) {
                        if (empty($v['value_ids'])) return [];
                        return array_filter(explode(',', $v['value_ids']));
                    })
                    ->unique()
                    ->values()
                    ->toArray();

                // Lấy dữ liệu attribute_values theo id
                $attributeValues = AttributeValue::whereIn('id', $allAttributeValueIds)
                    ->get()
                    ->keyBy('id');

                foreach ($request->variants as $idx => $variantData) {
                    // Tạo product_variant 
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku'        => $variantData['sku'] ?? null,
                        'price'      => isset($variantData['price']) ? (float)$variantData['price'] : (float)$validated['base_price'],
                        'stock'      => isset($variantData['stock']) ? (int)$variantData['stock'] : 0,
                        'is_active'  => 1,
                        'length'     => isset($variantData['length']) ? (float)$variantData['length'] : null,
                        'width'      => isset($variantData['width']) ? (float)$variantData['width'] : null,
                        'height'     => isset($variantData['height']) ? (float)$variantData['height'] : null,
                        'weight'     => isset($variantData['weight']) ? (float)$variantData['weight'] : null,
                    ]);

                    $totalVariantStock += $variant->stock;

                    // Liên kết attribute_values với variant
                    if (!empty($variantData['value_ids'])) {
                        $attributeValueIds = array_filter(explode(',', $variantData['value_ids']));

                        foreach ($attributeValueIds as $avId) {
                            // Kiểm tra attribute_value có tồn tại không
                            if (!$attributeValues->has($avId)) {
                                continue; // Bỏ qua nếu không tồn tại
                            }
                            VariantAttributeValue::firstOrCreate([
                                'variant_id'           => $variant->id,
                                'attribute_value_id'   => (int)$avId,  
                            ]);
                        }
                    }
                }

                // Cập nhật tồn kho tổng bằng tổng tồn kho các biến thể
                $product->update(['stock' => $totalVariantStock]);
            }

            DB::commit();
            return redirect()->route('admin.products.list')->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Lỗi khi thêm sản phẩm: ' . $e->getMessage()])
                ->withInput();
        }
    }

    //  Form sửa sản phẩm
 
    public function edit($id)
    {
        $product = Product::with('variants', 'images')->findOrFail($id);
        $categories = Category::where('is_active', 1)->get();
        $attributes = Attributes::with('values')->get();
        $brands = Brand::where('is_active', 1)->get();

        return view('admin.products.product-edit', compact('product', 'categories', 'attributes', 'brands'));
    }
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Custom messages tiếng Việt
        $messages = [
            'category_id.required' => 'Bạn phải chọn danh mục.',
            'category_id.exists'   => 'Danh mục không hợp lệ.',
            'name.required'        => 'Bạn phải nhập tên sản phẩm.',
            'name.max'             => 'Tên sản phẩm không được quá 150 ký tự.',
            'cost_price.required'  => 'Bạn phải nhập giá nhập.',
            'cost_price.numeric'   => 'Giá nhập phải là số.',
            'cost_price.gt'        => 'Giá nhập phải lớn hơn 0.',
            'base_price.required'  => 'Bạn phải nhập giá bán.',
            'base_price.numeric'   => 'Giá bán phải là số.',
            'base_price.gt'        => 'Giá bán phải lớn hơn giá nhập.',
            'discount_price.numeric' => 'Giá khuyến mãi phải là số.',
            'discount_price.lt'    => 'Giá khuyến mãi phải nhỏ hơn giá bán.',
            'discount_price.gt'    => 'Giá khuyến mãi phải lớn hơn giá nhập.',
            'stock.required'       => 'Bạn phải nhập số lượng tồn kho.',
            'stock.integer'        => 'Tồn kho phải là số nguyên.',
            'stock.min'            => 'Tồn kho không được âm.',
            'variants.*.price.numeric' => 'Giá biến thể phải là số.',
            'variants.*.price.gte'     => 'Giá biến thể phải nhỏ hơn hoặc bằng giá bán.',
            'variants.*.stock.integer' => 'Tồn kho biến thể phải là số nguyên.',
            'variants.*.stock.min'     => 'Tồn kho biến thể không được âm.',
            'product_images.max'     => 'Tối đa 5 ảnh.',
        ];

        // Validate dữ liệu
        $validated = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'brand_id'         => 'nullable|exists:brands,id',
            'name'             => 'required|string|max:150',
            'description'      => 'nullable|string',
            'cost_price'       => 'required|numeric|gt:0',
            'base_price'       => 'required|numeric|gt:cost_price',
            'discount_price'   => 'nullable|numeric|lt:base_price|gt:cost_price',
            'stock'            => 'required|integer|min:0',
            'image_main'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants'         => 'nullable|array',
            'variants.*.sku'   => 'nullable|string',
            'variants.*.price' => 'nullable|numeric|gte:cost_price',
            'variants.*.stock' => 'nullable|integer|min:0',
            'product_images'   => 'nullable|array|max:5',
        ], $messages);

        DB::beginTransaction();
        try {
            //  Upload ảnh mới nếu có 
            $mainImagePath = $product->image_main;
            if ($request->hasFile('image_main')) {
                // Xóa ảnh cũ
                if ($product->image_main) {
                    Storage::disk('public')->delete($product->image_main);
                }
                $mainImagePath = $request->file('image_main')->store('products', 'public');
            }

            // Nếu người dùng thêm ảnh phụ mới xóa hết ảnh cũ
            if ($request->has('delete_all_images') && $request->delete_all_images == '1') {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->image);
                    $image->delete();
                }
            }

            // Thêm ảnh phụ mới
            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $imageFile) {
                    if ($imageFile) {
                        $secondaryImagePath = $imageFile->store('products', 'public');
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image'      => $secondaryImagePath,
                        ]);
                    }
                }
            }
            //  UPDATE sản phẩm 
            $product->update([
                'category_id'    => $validated['category_id'],
                'brand_id'       => $validated['brand_id'] ?? null,
                'name'           => $validated['name'],
                'description'    => $validated['description'] ?? '',
                'cost_price'     => $validated['cost_price'],
                'base_price'     => $validated['base_price'],
                'discount_price' => $validated['discount_price'] ?? null,
                'image_main'     => $mainImagePath,
                'is_active'      => $request->has('is_active') ? 1 : 0,
            ]);

            $totalVariantStock = 0;
            $processedVariantIds = [];

            //  Xử lý biến thể 
            if ($request->has('variants') && is_array($request->variants) && count($request->variants) > 0) {
                // Lấy tất cả attribute_value_ids
                $allAttributeValueIds = collect($request->variants)
                    ->flatMap(function ($v) {
                        if (empty($v['value_ids'])) return [];
                        return array_filter(explode(',', $v['value_ids']));
                    })
                    ->unique()
                    ->values()
                    ->toArray();

                // Lấy dữ liệu attribute_values
                $attributeValues = AttributeValue::whereIn('id', $allAttributeValueIds)
                    ->get()
                    ->keyBy('id');

                foreach ($request->variants as $idx => $variantData) {
                    $variantStock = isset($variantData['stock']) ? (int)$variantData['stock'] : 0;
                    $totalVariantStock += $variantStock;

                    // Nếu có ID → UPDATE variant
                    if (!empty($variantData['id'])) {
                        $variant = ProductVariant::find($variantData['id']);
                        if ($variant && $variant->product_id == $product->id) {
                            $variant->update([
                                'sku'   => $variantData['sku'] ?? null,
                                'price' => isset($variantData['price']) ? (float)$variantData['price'] : (float)$validated['base_price'],
                                'stock' => $variantStock,
                            ]);
                            $processedVariantIds[] = $variant->id;
                        }
                    } else {
                        // Không có ID CREATE variant mới
                        $variant = ProductVariant::create([
                            'product_id' => $product->id,
                            'sku'        => $variantData['sku'] ?? null,
                            'price'      => isset($variantData['price']) ? (float)$variantData['price'] : (float)$validated['base_price'],
                            'stock'      => $variantStock,
                            'is_active'  => 1,
                        ]);
                        $processedVariantIds[] = $variant->id;
                    }

                    //  UPDATE/DELETE variant_attribute_values (liên kết trực tiếp tới attribute_values)
                    if (!empty($variantData['value_ids'])) {
                        $attributeValueIds = array_filter(explode(',', $variantData['value_ids']));

                        // Xóa variant_attribute_values cũ
                        VariantAttributeValue::where('variant_id', $variant->id)->delete();

                        // Thêm variant_attribute_values mới
                        foreach ($attributeValueIds as $avId) {
                            if (!$attributeValues->has($avId)) {
                                continue;
                            }

                            VariantAttributeValue::create([
                                'variant_id'           => $variant->id,
                                'attribute_value_id'   => (int)$avId,  
                            ]);
                        }
                    } else {
                        // Không có value_ids Xóa hết
                        VariantAttributeValue::where('variant_id', $variant->id)->delete();
                    }
                }

                // Xóa các biến thể không còn trong form
                ProductVariant::where('product_id', $product->id)
                    ->whereNotIn('id', $processedVariantIds)
                    ->delete();

                // Cập nhật stock = tổng stock variants
                $product->update(['stock' => $totalVariantStock]);
            } else {
                // Không có biến thể Xóa tất cả variants
                ProductVariant::where('product_id', $product->id)->delete();
                $product->update(['stock' => $validated['stock']]);
            }

            DB::commit();
            return redirect()->route('admin.products.list')->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update product error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage()]);
        }
    }
 
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Xóa ảnh nếu có
        if ($product->image_main) {
            Storage::disk('public')->delete($product->image_main);
        }

        // Xóa biến thể
        $product->variants()->delete();

        // Xóa sản phẩm
        $product->delete();

        return redirect()->route('admin.products.list')->with('success', 'Xóa sản phẩm thành công!');
    }
}