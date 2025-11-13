<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use App\Models\admin\Attributes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attributes::withCount('values')->get();
        return view('admin.attributes.attributes-list', compact('attributes'));
    }

    public function create()
    {
        return view('admin.attributes.attributes-add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'values' => 'required|array',
            'values.*' => 'nullable|string|max:255',
        ]);

        $slug = Str::slug($request->name);

        if (Attributes::where('slug', $slug)->exists()) {
            return back()
                ->withErrors(['name' => 'Thuộc tính này đã tồn tại!'])
                ->withInput();
        }

        $attribute = Attributes::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        if ($request->has('values')) {
            foreach ($request->values as $value) {
                if (!empty($value)) {
                    $attribute->values()->create([
                        'value' => $value,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.attributes.list')
            ->with('success', 'Thêm thuộc tính và giá trị thành công!');
    }

    public function edit($id)
    {
        $attribute = Attributes::findOrFail($id);
        return view('admin.attributes.attributes-edit', compact('attribute'));
    }

    public function update(Request $request, $id)
    {
        $attribute = Attributes::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'values' => 'required|array',
            'values.*.value' => 'nullable|string|max:255',
        ]);

        // Cập nhật tên thuộc tính
        $attribute->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        $existingIds = $attribute->values->pluck('id')->toArray();
        $incomingIds = collect($request->values)->pluck('id')->filter()->toArray();

        // Xóa những giá trị không còn trong form
        $toDelete = array_diff($existingIds, $incomingIds);
        $attribute->values()->whereIn('id', $toDelete)->delete();

        // Thêm hoặc cập nhật từng giá trị
        foreach ($request->values as $val) {
            if (!empty($val['id'])) {
                $attribute->values()->where('id', $val['id'])->update([
                    'value' => $val['value'],
                ]);
            } elseif (!empty($val['value'])) {
                $attribute->values()->create(['value' => $val['value']]);
            }
        }

        return redirect()->route('admin.attributes.list')->with('success', 'Cập nhật thuộc tính thành công!');
    }



    public function destroy($id)
    {
        try {
            $attribute = Attributes::findOrFail($id);

            // Lấy tất cả các giá trị thuộc tính (attribute_values) của attribute này
            $attributeValueIds = DB::table('attribute_values')
                ->where('attribute_id', $id)
                ->pluck('id')
                ->toArray();

            // Nếu attribute có các giá trị
            if (!empty($attributeValueIds)) {
                // Kiểm tra xem có giá trị nào đang được sử dụng trong biến thể sản phẩm không
                $isUsedInVariants = DB::table('variant_attribute_values')
                    ->whereIn('attribute_value_id', $attributeValueIds)
                    ->exists();

                if ($isUsedInVariants) {
                    // Lấy thông tin chi tiết để hiển thị cho user
                    $variantCount = DB::table('variant_attribute_values')
                        ->whereIn('attribute_value_id', $attributeValueIds)
                        ->distinct('variant_id')
                        ->count('variant_id');

                    return redirect()
                        ->route('admin.attributes.list')
                        ->withErrors([
                            'error' => "Không thể xóa thuộc tính '{$attribute->name}' vì có {$variantCount} biến thể sản phẩm đang sử dụng giá trị của thuộc tính này."
                        ]);
                }
            }

            // Nếu không có biến thể nào sử dụng, cho phép xóa
            $attribute->delete();

            return redirect()
                ->route('admin.attributes.list')
                ->with('success', "Xóa thuộc tính '{$attribute->name}' thành công!");

        } catch (\Exception $e) {
            \Log::error('Error deleting attribute: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.attributes.list')
                ->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }
}