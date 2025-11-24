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
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:brands,name',
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:brands,slug',
            ],
            'description' => 'nullable|string|max:1000',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ], [
            
            'name.required' => 'Tên thương hiệu là bắt buộc',
            'name.string' => 'Tên thương hiệu phải là chuỗi ký tự',
            'name.max' => 'Tên thương hiệu không được vượt quá 255 ký tự',
            'name.unique' => 'Tên thương hiệu này đã tồn tại',

            'slug.required' => 'Slug là bắt buộc',
            'slug.string' => 'Slug phải là chuỗi ký tự',
            'slug.max' => 'Slug không được vượt quá 255 ký tự',
            'slug.unique' => 'Slug này đã tồn tại',

            'description.string' => 'Mô tả phải là chuỗi ký tự',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự',

            'logo.image' => 'Logo phải là một hình ảnh',
            'logo.mimes' => 'Logo phải là định dạng: jpeg, png, jpg, gif',
            'logo.max' => 'Logo không được vượt quá 2MB',
            'logo.required' => 'Logo là bắt buộc',

            'is_active.boolean' => 'Trạng thái phải là đúng hoặc sai',
        ]);

        // Tạo slug tự động nếu không có
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Upload logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('brands', 'public');
            $validated['logo'] = $logoPath;
        }

        // Xử lý checkbox
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        try {
            Brand::create($validated);

            return redirect()->route('admin.brands.index')
                ->with('success', 'Thương hiệu đã được tạo thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo thương hiệu: ' . $e->getMessage());
        }
    }

     public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.brand-edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

       
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:brands,name,' . $id,
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:brands,slug,' . $id,
            ],
            'description' => 'nullable|string|max:1000',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Tên thương hiệu là bắt buộc',
            'name.string' => 'Tên thương hiệu phải là chuỗi ký tự',
            'name.max' => 'Tên thương hiệu không được vượt quá 255 ký tự',
            'name.unique' => 'Tên thương hiệu này đã tồn tại',

            'slug.required' => 'Slug là bắt buộc',
            'slug.string' => 'Slug phải là chuỗi ký tự',
            'slug.max' => 'Slug không được vượt quá 255 ký tự',
            'slug.unique' => 'Slug này đã tồn tại',

            'description.string' => 'Mô tả phải là chuỗi ký tự',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự',

            'logo.image' => 'Logo phải là một hình ảnh',
            'logo.mimes' => 'Logo phải là định dạng: jpeg, png, jpg, gif',
            'logo.max' => 'Logo không được vượt quá 2MB',
            'logo.required' => 'Logo là bắt buộc',

            'is_active.boolean' => 'Trạng thái phải là bật hoặc tắt',
        ]);

        
        if ($validated['name'] !== $brand->name && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Upload logo mới (xóa cái cũ nếu có)
        if ($request->hasFile('logo')) {
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
            $logo = $request->file('logo');
            $logoPath = $logo->store('brands', 'public');
            $validated['logo'] = $logoPath;
        }

        // Xử lý checkbox
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        try {
            $brand->update($validated);

            return redirect()->route('admin.brands.index')
                ->with('success', 'Thương hiệu đã được cập nhật thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật thương hiệu: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);

            // Xóa logo nếu có
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }

            $brand->delete();

            return redirect()->route('admin.brands.index')
                ->with('success', 'Thương hiệu đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa thương hiệu: ' . $e->getMessage());
        }
    }
}