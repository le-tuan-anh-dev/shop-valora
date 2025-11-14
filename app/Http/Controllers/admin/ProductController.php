<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Attributes;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /** Common validate rules */
    private function rules()
    {
        return [
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'cost_price'       => 'required|numeric|gt:base_price',
            'base_price'       => 'required|numeric|lt:cost_price',
            'discount_price'   => 'nullable|numeric|lt:base_price',
            'stock'            => 'required|integer|min:0',
            'variants.*.price' => 'nullable|numeric|lte:base_price',
        ];
    }

    /** Common messages */
    private function messages()
    {
        return [
            'category_id.required' => 'Bạn phải chọn danh mục.',
            'name.required'        => 'Bạn phải nhập tên sản phẩm.',
            'cost_price.gt'        => 'Giá nhập phải lớn hơn giá bán.',
            'base_price.lt'        => 'Giá bán phải nhỏ hơn giá nhập.',
            'discount_price.lt'    => 'Giá khuyến mãi phải nhỏ hơn giá bán.',
            'stock.min'            => 'Tồn kho không được âm.',
            'variants.*.price.lte' => 'Giá biến thể phải nhỏ hơn hoặc bằng giá bán.',
        ];
    }

    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->search, function ($q) {
                $term = request('search');
                $q->where('name', 'like', "%$term%")
                  ->orWhere('description', 'like', "%$term%");
            })
            ->orderByDesc('updated_at')
            ->paginate(10)->withQueryString();

        return view('admin.products.product-list', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with('category', 'variants')->findOrFail($id);
        return view('admin.products.product-detail', compact('product'));
    }

    public function create()
    {
        return view('admin.products.product-add', [
            'categories' => Category::where('is_active', 1)->get(),
            'attributes' => Attributes::with('values')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        DB::beginTransaction();
        try {
            $product = Product::create([
                ...$validated,
                'description' => $validated['description'] ?? '',
                'discount_price' => $validated['discount_price'] ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            if ($request->variants) {
                foreach ($request->variants as $v) {
                    $product->variants()->create([
                        'title'     => $v['title'],
                        'value_ids' => $v['value_ids'],
                        'price'     => $v['price'] ?? 0,
                        'stock'     => $v['stock'] ?? 0,
                        'sku'       => $v['sku'] ?? null,
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

    public function edit($id)
    {
        return view('admin.products.product-edit', [
            'product'    => Product::with('variants')->findOrFail($id),
            'categories' => Category::where('is_active', 1)->get(),
            'attributes' => Attributes::with('values')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $product   = Product::findOrFail($id);
        $validated = $request->validate($this->rules(), $this->messages());

        DB::beginTransaction();
        try {
            // Update image
            if ($request->hasFile('image_main')) {
                Storage::disk('public')->delete($product->image_main);
                $validated['image_main'] = $request->file('image_main')->store('products', 'public');
            }

            // Update product info
            $product->update([
                ...$validated,
                'description' => $validated['description'] ?? '',
                'discount_price' => $validated['discount_price'] ?? 0,
                'is_active' => $request->boolean('is_active'),
                'status'    => $request->boolean('is_active') ? 'active' : 'inactive',
                'image_main' => $validated['image_main'] ?? $product->image_main,
            ]);

            // Recreate variants
            $product->variants()->delete();

            $totalStock = 0;
            foreach (($request->variants ?? []) as $v) {
                $stock = intval($v['stock']);
                $totalStock += $stock;

                $product->variants()->create([
                    'title'     => $v['title'],
                    'value_ids' => $v['value_ids'],
                    'price'     => $v['price'] ?? 0,
                    'stock'     => $stock,
                    'sku'       => $v['sku'] ?? null,
                    'is_active' => $v['is_active'] ?? 1,
                ]);
            }

            $product->stock = count($request->variants ?? []) ? $totalStock : $validated['stock'];
            $product->save();

            DB::commit();
            return redirect()->route('admin.products.list')->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => 'Có lỗi xảy ra.']);
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        Storage::disk('public')->delete($product->image_main);
        $product->variants()->delete();
        $product->delete();

        return redirect()->route('admin.products.list')->with('success', 'Xóa sản phẩm thành công!');
    }
}
