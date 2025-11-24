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
        $users = User::where('role', 'customer')->select('id', 'name', 'email')->get();
        return view('admin.vouchers.create', compact('variants', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'max_uses' => 'required|integer|min:1',
            'per_user_limit' => 'required|integer|min:1',
            'assigned_user_id' => 'nullable|exists:users,id',
            'applicable_variant_id' => 'nullable|exists:product_variants,id',
            'starts_at' => 'required|date|before:ends_at',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'nullable|boolean',
        ], [
            'code.required' => 'Mã voucher là bắt buộc',
            'code.unique' => 'Mã voucher này đã tồn tại',
            'type.required' => 'Loại giảm giá là bắt buộc',
            'value.required' => 'Giá trị giảm giá là bắt buộc',
            'value.min' => 'Giá trị phải lớn hơn 0',
            'max_uses.required' => 'Tổng lượt sử dụng là bắt buộc',
            'max_uses.min' => 'Tối thiểu 1 lượt sử dụng',
            'per_user_limit.required' => 'Giới hạn/người là bắt buộc',
            'starts_at.required' => 'Ngày bắt đầu là bắt buộc',
            'starts_at.before' => 'Ngày bắt đầu phải trước ngày kết thúc',
            'ends_at.required' => 'Ngày kết thúc là bắt buộc',
            'ends_at.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['used_count'] = 0;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        Voucher::create($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Mã giảm giá đã được tạo thành công!');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        $variants = ProductVariant::select('id', 'product_id', 'sku')->get();
        $users = User::where('role', 'customer')->select('id', 'name', 'email')->get();
        return view('admin.vouchers.edit', compact('voucher', 'variants', 'users'));
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
            'assigned_user_id' => 'nullable|exists:users,id',
            'applicable_variant_id' => 'nullable|exists:product_variants,id',
            'starts_at' => 'required|date|before:ends_at',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'nullable|boolean',
        ], [
            'max_uses.min' => 'Tổng lượt sử dụng phải >= ' . $voucher->used_count,
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $voucher->update($validated);

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