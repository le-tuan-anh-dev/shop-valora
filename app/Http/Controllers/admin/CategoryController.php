<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->orderBy('id', 'desc')->paginate(10);
        return view('admin.categories.category-list', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->get();
        return view('admin.categories.category-add', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:150|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'required|boolean',
        ]);

        // Nếu không nhập slug thì tự sinh từ name
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        Category::create($data);

        return redirect()->route('admin.categories.list')
            ->with('success', 'Thêm danh mục thành công!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $parents = Category::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->get();

        return view('admin.categories.category-edit', compact('category', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:150|unique:categories,slug,' . $id,
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'required|boolean',
        ]);

        //  Nếu không nhập slug thì tự sinh từ name
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return redirect()->route('admin.categories.list')
            ->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($id)
{
    $category = Category::findOrFail($id);

    // Kiểm tra xem danh mục có sản phẩm nào không
    if ($category->products()->exists()) {
        return redirect()->route('admin.categories.list')
            ->with('error', 'Không thể xóa danh mục vì đang có sản phẩm thuộc danh mục này!');
    }

    $category->delete();

    return redirect()->route('admin.categories.list')
        ->with('success', 'Xóa danh mục thành công!');
}
}