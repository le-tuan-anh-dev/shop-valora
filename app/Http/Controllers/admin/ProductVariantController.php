<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    /** Common validate rules */
    private function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'sku'        => 'nullable|string|max:100',
            'title'      => 'required|string|max:150',
            'price'      => 'required|numeric|min:0',
            'stock'      => 'required|integer|min:0',
        ];
    }

    /** Common validate messages */
    private function messages()
    {
        return [
            'product_id.required' => 'Bạn phải chọn sản phẩm.',
            'title.required'      => 'Bạn phải nhập tên biến thể.',
            'price.min'           => 'Giá phải lớn hơn hoặc bằng 0.',
            'stock.min'           => 'Số lượng phải >= 0.',
        ];
    }

    public function index()
    {
        $variants = ProductVariant::with('product')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('admin.products.variant-list', compact('variants'));
    }

    public function create()
    {
        return view('admin.products.variant-add', [
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        ProductVariant::create($data);

        return back()->with('success', 'Thêm biến thể thành công!');
    }

    public function edit($id)
    {
        return view('admin.products.variant-edit', [
            'variant'  => ProductVariant::findOrFail($id),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);
        $data    = $request->validate($this->rules(), $this->messages());

        $variant->update($data);

        return back()->with('success', 'Cập nhật biến thể thành công!');
    }

    public function destroy($id)
    {
        ProductVariant::findOrFail($id)->delete();

        return back()->with('success', 'Xóa biến thể thành công!');
    }
}
