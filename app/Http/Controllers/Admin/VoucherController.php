<?php 

namespace App\Http\Controllers\Admin;

use App\Models\Admin\ProductVariant;
use App\Models\Voucher;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController 
{
    public function index()
    {
        $vouchers = Voucher::paginate(15);
        return view('admin.vouchers.voucher-list', compact('vouchers'));
    }

    public function create()
    {
        $variants = ProductVariant::select('id', 'product_id', 'sku')->get();
        return view('admin.vouchers.voucher-create', compact('variants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:vouchers',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'max_uses' => 'required|integer|min:1',
            'per_user_limit' => 'required|integer|min:1',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
            'variant_type' => 'required|in:all,specific',
            'variant_ids' => 'nullable|array|required_if:variant_type,specific',
            'variant_ids.*' => 'exists:product_variants,id',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount_value' => 'required|numeric|min:0',
        ], [
            'code.required' => 'Mã voucher là bắt buộc',
            'code.string' => 'Mã voucher phải là chuỗi ký tự',
            'code.unique' => 'Mã voucher này đã tồn tại',
            'type.required' => 'Loại giảm giá là bắt buộc',
            'type.in' => 'Loại giảm giá phải là: cố định hoặc phần trăm',
            'value.required' => 'Giá trị giảm giá là bắt buộc',
            'value.numeric' => 'Giá trị giảm giá phải là số',
            'value.min' => 'Giá trị giảm giá không được âm',
            'max_uses.required' => 'Số lần sử dụng tối đa là bắt buộc',
            'max_uses.integer' => 'Số lần sử dụng tối đa phải là số nguyên',
            'max_uses.min' => 'Số lần sử dụng tối đa tối thiểu là 1',
            'per_user_limit.required' => 'Giới hạn sử dụng trên người dùng là bắt buộc',
            'per_user_limit.integer' => 'Giới hạn sử dụng trên người dùng phải là số nguyên',
            'per_user_limit.min' => 'Giới hạn sử dụng trên người dùng tối thiểu là 1',
            'starts_at.date' => 'Ngày bắt đầu phải là định dạng ngày tháng hợp lệ',
            'starts_at.required' => 'Ngày bắt đầu phải là phải được chọn',
            'ends_at.required' => 'Ngày kết thúc phải là phải được chọn',
            'ends_at.date' => 'Ngày kết thúc phải là định dạng ngày tháng hợp lệ',
            'ends_at.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
            'variant_type.required' => 'Loại áp dụng là bắt buộc',
            'variant_type.in' => 'Loại áp dụng phải là: tất cả sản phẩm hoặc sản phẩm cụ thể',
            'variant_ids.array' => 'Danh sách sản phẩm phải là mảng',
            'variant_ids.required_if' => 'Vui lòng chọn ít nhất một sản phẩm khi chọn áp dụng cho sản phẩm cụ thể',
            'variant_ids.*.exists' => 'Một hoặc nhiều sản phẩm được chọn không tồn tại',
            'min_order_value.numeric' => 'Giá trị đơn hàng tối thiểu phải là số',
            'min_order_value.min' => 'Giá trị đơn hàng tối thiểu không được âm',
            'max_discount_value.numeric' => 'Giá trị giảm giá tối đa phải là số',
            'max_discount_value.min' => 'Giá trị giảm giá tối đa không được âm',
            'max_discount_value.required' => 'Giá trị giảm giá tối đa là bắt buộc',
        ]);

        if ($request->type === 'percent' && $validated['value'] > 100) {
            return back()->withErrors([
                'value' => 'Giá trị phần trăm không được vượt quá 100%'
            ])->withInput();
        }

        // Xác định apply_all_products dựa trên variant_type
        $applyAllProducts = $request->variant_type === 'all' ? 1 : 0;

        // Tạo voucher
        $voucher = Voucher::create([
            'code' => strtoupper($validated['code']),
            'type' => $validated['type'],
            'value' => $validated['value'],
            'max_uses' => $validated['max_uses'],
            'per_user_limit' => $validated['per_user_limit'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'is_active' => $request->has('is_active') ? 1 : 0,
            'apply_all_products' => $applyAllProducts,
            'min_order_value' => $validated['min_order_value'] ?? 0,
            'max_discount_value' => $validated['max_discount_value'] ?? 0,
            'used_count' => 0, // Khởi tạo số lần đã sử dụng = 0
        ]);

        // Thêm variant vào bảng voucher_variants nếu chọn specific
        if ($request->variant_type === 'specific' && !empty($validated['variant_ids'])) {
            $voucher->variants()->attach($validated['variant_ids']);
        }

        return redirect()->route('admin.vouchers.index')->with('success', 'Tạo voucher thành công!');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        $variants = ProductVariant::select('id', 'product_id', 'sku')->get();
        return view('admin.vouchers.voucher-edit', compact('voucher', 'variants',));
    }

public function update(Request $request, $id)
{
    $voucher = Voucher::findOrFail($id);

    $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,' . $id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'max_uses' => 'required|integer|min:' . $voucher->used_count,
            'per_user_limit' => 'required|integer|min:1',
            'starts_at' => 'nullable|date|before:ends_at',
            'ends_at' => 'nullable|date|after:starts_at',
            'is_active' => 'nullable|boolean',
            'variant_type' => 'required|in:all,specific',
            'variant_ids' => 'nullable|array|required_if:variant_type,specific',
            'variant_ids.*' => 'exists:product_variants,id',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount_value' => 'required|numeric|min:0',
        ], [
            'code.required' => 'Mã voucher là bắt buộc',
            'code.string' => 'Mã voucher phải là chuỗi ký tự',
            'code.max' => 'Mã voucher không được vượt quá 50 ký tự',
            'code.unique' => 'Mã voucher này đã tồn tại',
            'type.required' => 'Loại giảm giá là bắt buộc',
            'type.in' => 'Loại giảm giá phải là: cố định hoặc phần trăm',
            'value.required' => 'Giá trị giảm giá là bắt buộc',
            'value.numeric' => 'Giá trị giảm giá phải là số',
            'value.min' => 'Giá trị giảm giá không được âm',
            'max_uses.required' => 'Số lần sử dụng tối đa là bắt buộc',
            'max_uses.integer' => 'Số lần sử dụng tối đa phải là số nguyên',
            'max_uses.min' => 'Tổng lượt sử dụng phải >= ' . $voucher->used_count,
            'per_user_limit.required' => 'Giới hạn sử dụng trên người dùng là bắt buộc',
            'per_user_limit.integer' => 'Giới hạn sử dụng trên người dùng phải là số nguyên',
            'per_user_limit.min' => 'Giới hạn sử dụng trên người dùng tối thiểu là 1',
            'starts_at.date' => 'Ngày bắt đầu phải là định dạng ngày tháng',
            'starts_at.before' => 'Ngày bắt đầu phải trước ngày kết thúc',
            'ends_at.date' => 'Ngày kết thúc phải là định dạng ngày tháng',
            'ends_at.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
            'variant_type.required' => 'Loại áp dụng là bắt buộc',
            'variant_type.in' => 'Loại áp dụng phải là: tất cả sản phẩm hoặc sản phẩm cụ thể',
            'variant_ids.array' => 'Danh sách sản phẩm phải là mảng',
            'variant_ids.required_if' => 'Vui lòng chọn ít nhất một sản phẩm khi chọn áp dụng cho sản phẩm cụ thể',
            'variant_ids.*.exists' => 'Một hoặc nhiều sản phẩm được chọn không tồn tại',
            'min_order_value.numeric' => 'Giá trị đơn hàng tối thiểu phải là số',
            'min_order_value.min' => 'Giá trị đơn hàng tối thiểu không được âm',
            'max_discount_value.numeric' => 'Giá trị giảm giá tối đa phải là số',
            'max_discount_value.min' => 'Giá trị giảm giá tối đa không được âm',
            'max_discount_value.required' => 'Giá trị giảm giá tối đa là bắt buộc',
        ]);
    
    if ($request->type === 'percent' && $validated['value'] > 100) {
        return back()->withErrors([
            'value' => 'Giá trị phần trăm không được vượt quá 100%'
        ])->withInput();
    }
    // Xác định apply_all_products dựa trên variant_type
    $applyAllProducts = $request->variant_type === 'all' ? 1 : 0;

    // Cập nhật voucher
    $voucher->update([
        'code' => strtoupper($validated['code']),
        'type' => $validated['type'],
        'value' => $validated['value'],
        'max_uses' => $validated['max_uses'],
        'per_user_limit' => $validated['per_user_limit'],
        'starts_at' => $validated['starts_at'],
        'ends_at' => $validated['ends_at'],
        'is_active' => $request->has('is_active') ? 1 : 0,
        'apply_all_products' => $applyAllProducts,
        'min_order_value' => $validated['min_order_value'] ?? 0,
        'max_discount_value' => $validated['max_discount_value'] ?? 0,
    ]);

    // Cập nhật variant
    $voucher->variants()->detach(); // Xóa tất cả variant cũ

    if ($request->variant_type === 'specific' && !empty($validated['variant_ids'])) {
        $voucher->variants()->attach($validated['variant_ids']);
    }

    return redirect()->route('admin.vouchers.index')
        ->with('success', 'Mã giảm giá đã được cập nhật thành công!');
}

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Mã giảm giá đã được xóa thành công!');
    }

    public function show($id)
    {
        $voucher = Voucher::with('uses.user', 'uses.order')->findOrFail($id);
        return view('admin.vouchers.show', compact('voucher'));
    }
}