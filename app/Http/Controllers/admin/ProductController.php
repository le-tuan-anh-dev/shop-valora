<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\ProductVariant;
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

    /**
     * Form thêm sản phẩm
     */
    public function create()
    {
        $categories = Category::where('is_active', 1)->get();
        return view('admin.products.product-add', compact('categories'));
    }

    /**
     * Lưu sản phẩm mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image_main' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload ảnh chính
        if ($request->hasFile('image_main')) {
            $validated['image_main'] = $request->file('image_main')->store('products', 'public');
        }

        // Trạng thái hoạt động
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['status'] = $validated['is_active'] ? 'active' : 'inactive';

        // Tạo sản phẩm
        $product = Product::create($validated);

        // Lưu biến thể (nếu có)
        if ($request->has('variants')) {
            foreach ($request->variants as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $v['sku'] ?? null,
                    'title' => $v['title'],
                    'price' => $v['price'] ?? $product->base_price,
                    'stock' => $v['stock'] ?? 0,
                    'is_active' => 1,
                ]);
            }
        }

        return redirect()->route('admin.products.list')->with('success', 'Thêm sản phẩm thành công!');
    }

    /**
     * Form sửa sản phẩm
     */
    public function edit($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::where('is_active', 1)->get();
        return view('admin.products.product-edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image_main' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Cập nhật ảnh
        if ($request->hasFile('image_main')) {
            if ($product->image_main) {
                Storage::disk('public')->delete($product->image_main);
            }
            $validated['image_main'] = $request->file('image_main')->store('products', 'public');
        }

        // Trạng thái hoạt động
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['status'] = $validated['is_active'] ? 'active' : 'inactive';

        // Cập nhật sản phẩm
        $product->update($validated);

        // Cập nhật lại toàn bộ biến thể
        ProductVariant::where('product_id', $product->id)->delete();
        if ($request->has('variants')) {
            foreach ($request->variants as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $v['sku'] ?? null,
                    'title' => $v['title'],
                    'price' => $v['price'] ?? $product->base_price,
                    'stock' => $v['stock'] ?? 0,
                    'is_active' => 1,
                ]);
            }
        }

        return redirect()->route('admin.products.list')->with('success', 'Cập nhật sản phẩm thành công!');
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
