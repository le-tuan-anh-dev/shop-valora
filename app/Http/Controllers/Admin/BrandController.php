<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::paginate(15);
        return view('admin.brands.brand-list', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.brand-add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'slug' => 'required|string|max:255|unique:brands',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        // Tạo slug tự động nếu không có
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Upload logo
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        Brand::create($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Thương hiệu đã được tạo thành công!');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        
        return view('admin.brands.brand-edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'slug' => 'required|string|max:255|unique:brands,slug,' . $brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
            'remove_logo' => 'nullable|boolean',
        ]);

        // Tạo slug tự động nếu không có
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Xóa logo cũ nếu được chọn
        if ($request->has('remove_logo') && $brand->logo) {
            Storage::disk('public')->delete($brand->logo);
            $validated['logo'] = null;
        }

        // Upload logo mới
        if ($request->hasFile('logo')) {
            // Xóa logo cũ
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $brand->update($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Thương hiệu đã được cập nhật thành công!');
    }

    public function destroy(Brand $brand)
    {
        // Xóa logo
        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')
            ->with('success', 'Thương hiệu đã được xóa thành công!');
    }
}