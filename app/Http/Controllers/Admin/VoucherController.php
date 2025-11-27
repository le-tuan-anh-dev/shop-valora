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
        return view('admin.vouchers.voucher-create', compact('variants', 'users'));
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
        'product_variants' => 'nullable|array|required_if:variant_type,specific',
        'user_type' => 'required|in:all,specific',
        'users' => 'nullable|array|required_if:user_type,specific',
    ]);

    // Tạo voucher
    $voucher = Voucher::create([
        'code' => strtoupper($validated['code']),
        'type' => $validated['type'],
        'value' => $validated['value'],
        'max_uses' => $validated['max_uses'],
        'per_user_limit' => $validated['per_user_limit'],
        'starts_at' => $validated['starts_at'],
        'ends_at' => $validated['ends_at'],
        'is_active' => $validated['is_active'] ?? true,
    ]);

    // Thêm user vào bảng voucher_users
    if ($validated['user_type'] === 'specific' && !empty($validated['users'])) {
        $voucher->users()->attach($validated['users']);
    }

    // Thêm variant vào bảng voucher_variants
    if ($validated['variant_type'] === 'specific' && !empty($validated['product_variants'])) {
        $voucher->variants()->attach($validated['product_variants']);
    }

    return redirect()->route('admin.vouchers.index')->with('success', 'Tạo voucher thành công!');
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