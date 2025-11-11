<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Attributes;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\ProductVariant;
use App\Models\admin\VariantAttributeValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function show($id)
{
    $product = Product::with('category', 'variants')->findOrFail($id);
    return view('admin.products.product-detail', compact('product'));
}

    /**
     * Hiển thị danh sách sản phẩm (có tìm kiếm)
     */

    public function index(Request $request)
    {
        $query = Product::with('category')->orderBy('updated_at', 'desc');

        // 🔍 Tìm kiếm theo tên hoặc mô tả
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

            return view('admin.products.product-add', compact('categories', 'attributes'));
        }

    /**
     * Lưu sản phẩm mới và các biến thể
     */
        public function store(Request $request)
            {
                // Custom messages tiếng Việt
                $messages = [
                    'category_id.required' => 'Bạn phải chọn danh mục.',
                    'category_id.exists'   => 'Danh mục không hợp lệ.',
                    'name.required'        => 'Bạn phải nhập tên sản phẩm.',
                    'name.max'             => 'Tên sản phẩm không được quá 255 ký tự.',
                    'cost_price.required'  => 'Bạn phải nhập giá nhập.',
                    'cost_price.numeric'   => 'Giá nhập phải là số.',
                    'cost_price.gt'        => 'Giá nhập phải lớn hơn giá bán.',
                    'base_price.required'  => 'Bạn phải nhập giá bán.',
                    'base_price.numeric'   => 'Giá bán phải là số.',
                    'base_price.lt'        => 'Giá bán phải nhỏ hơn giá nhập.',
                    'discount_price.numeric' => 'Giá khuyến mãi phải là số.',
                    'discount_price.lt'    => 'Giá khuyến mãi phải nhỏ hơn giá bán.',
                    'stock.required'       => 'Bạn phải nhập số lượng tồn kho.',
                    'stock.integer'        => 'Tồn kho phải là số nguyên.',
                    'stock.min'            => 'Tồn kho không được âm.',
                    'variants.*.price.numeric' => 'Giá biến thể phải là số.',
                    'variants.*.price.lte'     => 'Giá biến thể phải nhỏ hơn hoặc bằng giá bán.',
                ];

                // Validate dữ liệu
                $validated = $request->validate([
                    'category_id'      => 'required|exists:categories,id',
                    'name'             => 'required|string|max:255',
                    'description'      => 'nullable|string',
                    'cost_price'       => 'required|numeric|gt:base_price',
                    'base_price'       => 'required|numeric|lt:cost_price',
                    'discount_price'   => 'nullable|numeric|lt:base_price',
                    'stock'            => 'required|integer|min:0',
                    'variants.*.price' => 'nullable|numeric|lte:base_price',
                ], $messages);

                // Bắt đầu transaction để đảm bảo dữ liệu
                DB::beginTransaction();

                try {
                    // Tạo sản phẩm
                    $product = Product::create([
                        'category_id'    => $validated['category_id'],
                        'name'           => $validated['name'],
                        'description'    => $validated['description'] ?? '',
                        'cost_price'     => $validated['cost_price'],
                        'base_price'     => $validated['base_price'],
                        'discount_price' => $validated['discount_price'] ?? 0,
                        'stock'          => $validated['stock'],
                        'is_active'      => $request->has('is_active'),
                    ]);

                    // Lưu biến thể nếu có
                    if ($request->has('variants')) {
                        foreach ($request->variants as $variant) {
                            $product->variants()->create([
                                'title'      => $variant['title'],
                                'value_ids'  => $variant['value_ids'],
                                'price'      => $variant['price'] ?? 0,
                                'stock'      => $variant['stock'] ?? 0,
                                'sku'        => $variant['sku'] ?? null,
                            ]);
                        }
                    }

                    DB::commit();
                    return redirect()->route('admin.products.list')->with('success', 'Thêm sản phẩm thành công!');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return back()->withInput()->withErrors(['general' => 'Có lỗi xảy ra, vui lòng thử lại.']);
                }
            }

    /**
     * Form sửa sản phẩm
     */
        public function edit($id)
        {
            $product = Product::with('variants')->findOrFail($id);
            $categories = Category::where('is_active', 1)->get();
           $attributes = Attributes::with('values')->get(); // Thêm attributes cho màn edit
            return view('admin.products.product-edit', compact('product', 'categories', 'attributes'));
        }

        /**
         * Cập nhật sản phẩm
         */
        public function update(Request $request, $id)
        {
            $product = Product::findOrFail($id);

            // Custom messages tiếng Việt
            $messages = [
                'category_id.required' => 'Bạn phải chọn danh mục.',
                'category_id.exists'   => 'Danh mục không hợp lệ.',
                'name.required'        => 'Bạn phải nhập tên sản phẩm.',
                'name.max'             => 'Tên sản phẩm không được quá 255 ký tự.',
                'cost_price.required'  => 'Bạn phải nhập giá nhập.',
                'cost_price.numeric'   => 'Giá nhập phải là số.',
                'cost_price.gt'        => 'Giá nhập phải lớn hơn giá bán.',
                'base_price.required'  => 'Bạn phải nhập giá bán.',
                'base_price.numeric'   => 'Giá bán phải là số.',
                'base_price.lt'        => 'Giá bán phải nhỏ hơn giá nhập.',
                'discount_price.numeric' => 'Giá khuyến mãi phải là số.',
                'discount_price.lt'    => 'Giá khuyến mãi phải nhỏ hơn giá bán.',
                'stock.required'       => 'Bạn phải nhập số lượng tồn kho.',
                'stock.integer'        => 'Tồn kho phải là số nguyên.',
                'stock.min'            => 'Tồn kho không được âm.',
                'variants.*.price.numeric' => 'Giá biến thể phải là số.',
                'variants.*.price.lte'     => 'Giá biến thể phải nhỏ hơn hoặc bằng giá bán.',
            ];

            // Validate dữ liệu
            $validated = $request->validate([
                'category_id'      => 'required|exists:categories,id',
                'name'             => 'required|string|max:255',
                'description'      => 'nullable|string',
                'cost_price'       => 'required|numeric|gt:base_price',
                'base_price'       => 'required|numeric|lt:cost_price',
                'discount_price'   => 'nullable|numeric|lt:base_price',
                'stock'            => 'required|integer|min:0',
                'variants.*.price' => 'nullable|numeric|lte:base_price',
            ], $messages);

            DB::beginTransaction();
            try {
                // Cập nhật ảnh nếu có
                if ($request->hasFile('image_main')) {
                    if ($product->image_main) {
                        Storage::disk('public')->delete($product->image_main);
                    }
                    $validated['image_main'] = $request->file('image_main')->store('products', 'public');
                }

                // Cập nhật trạng thái
                $validated['is_active'] = $request->input('is_active', 0); 
$validated['status'] = $validated['is_active'] ? 'active' : 'inactive';


                // Cập nhật sản phẩm cơ bản
                $product->update([
                    'category_id'    => $validated['category_id'],
                    'name'           => $validated['name'],
                    'description'    => $validated['description'] ?? '',
                    'cost_price'     => $validated['cost_price'],
                    'base_price'     => $validated['base_price'],
                    'discount_price' => $validated['discount_price'] ?? 0,
                    'is_active'      => $validated['is_active'],
                    'status'         => $validated['status'],
                    'image_main'     => $validated['image_main'] ?? $product->image_main,
                ]);

                // Xóa biến thể cũ
                ProductVariant::where('product_id', $product->id)->delete();

                $totalStock = 0;
                if ($request->has('variants')) {
                    foreach ($request->variants as $v) {
                        $variantStock = isset($v['stock']) ? intval($v['stock']) : 0;
                        $totalStock += $variantStock;

                        $product->variants()->create([
                            'title'      => $v['title'],
                            'value_ids'  => $v['value_ids'],
                            'price'      => $v['price'] ?? 0,
                            'stock'      => $variantStock,
                            'sku'        => $v['sku'] ?? null,
                            'is_active'  => $v['is_active'] ?? 1,
                        ]);
                    }
                    // Nếu có biến thể, stock sản phẩm = tổng biến thể
                    $product->update(['stock' => $totalStock]);
                } else {
                    // Nếu không có biến thể, dùng stock nhập tay
                    $product->update(['stock' => $validated['stock']]);
                }

                DB::commit();
                return redirect()->route('admin.products.list')->with('success', 'Cập nhật sản phẩm thành công!');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withInput()->withErrors(['general' => 'Có lỗi xảy ra, vui lòng thử lại.']);
            }
        }




    /**
     * Xóa sản phẩm
     */
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