<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index()
    {
        $variants = ProductVariant::with('product')->paginate(15);
        return view('admin.products.variant-list', compact('variants'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.products.variant-add', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'sku' => 'nullable|string|max:100',
            'title' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        ProductVariant::create($data);
        return back()->with('success', 'Thêm biến thể thành công!');
    }

    public function edit($id)
    {
        $variant = ProductVariant::findOrFail($id);
        $products = Product::all();
        return view('admin.products.variant-edit', compact('variant', 'products'));
    }

    public function update(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'sku' => 'nullable|string|max:100',
            'title' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);
        $variant->update($data);
        return back()->with('success', 'Cập nhật biến thể thành công!');
    }

    public function destroy($id)
    {
        ProductVariant::findOrFail($id)->delete();
        return back()->with('success', 'Xóa biến thể thành công!');
    }
}
