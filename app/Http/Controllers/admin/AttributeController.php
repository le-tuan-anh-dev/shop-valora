<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use App\Models\admin\Attributes;
use Illuminate\Http\Request;

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
        $attribute = Attributes::findOrFail($id);
        $attribute->delete();

        return redirect()->route('admin.attributes.list')->with('success', 'Xóa thuộc tính thành công!');
    }
}