<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang Dashboard khách hàng.
     */
    public function index()
    {
        $user = auth()->user();

        // Lấy địa chỉ của user, ưu tiên địa chỉ mặc định
        $addresses = $user->addresses()
            ->orderByDesc('is_default')
            ->orderByDesc('id')
            ->get();

        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        // Lấy đơn hàng, kèm items + paymentMethod
        $baseOrdersQuery = $user->orders()
            ->with(['items', 'paymentMethod'])
            ->orderByDesc('created_at');

        // Lấy 10 đơn gần nhất
        $orders      = (clone $baseOrdersQuery)->take(10)->get();
        // Đếm tổng số đơn (không bị ảnh hưởng bởi take(10))
        $totalOrders = (clone $baseOrdersQuery)->count();
        // Tổng tiền đã chi
        $totalSpent  = $user->orders()->sum('total_amount');

        // Thông báo mới nhất
        $notifications = $user->notifications()->latest()->take(10)->get();

        return view('client.dashboard', [
            'user'           => $user,
            'addresses'      => $addresses,
            'defaultAddress' => $defaultAddress,
            'orders'         => $orders,
            'totalOrders'    => $totalOrders,
            'totalSpent'     => $totalSpent,
            'notifications'  => $notifications,
        ]);
    }

    /**
     * Cập nhật thông tin tài khoản (tên, email, sđt, avatar).
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->hasFile('image')) {
            // Xoá ảnh cũ nếu có
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // Lưu ảnh mới
            $path        = $request->file('image')->store('avatars', 'public');
            $user->image = $path;
        }

        $user->save();

        return back()->with('success', 'Cập nhật thông tin tài khoản thành công.');
    }
}