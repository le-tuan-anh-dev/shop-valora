<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter theo role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Filter theo status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.users.user-list', compact('users'));
    }


    public function create()
    {
        return view('admin.users.user-add');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:125',
            'email' => 'required|email|unique:users,email|max:125',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'date_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'role' => 'required|in:admin,customer',
            'status' => 'required|in:active,locked,banned',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        User::create($data);

        return redirect()->route('admin.users.list')
            ->with('success', 'Thêm người dùng thành công!');
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        $orderStats = null;
        $orders = collect();
        
        if ($user->role === 'customer') {
            $orders = Order::where('user_id', $user->id)
                ->with('orderItems')
                ->orderBy('created_at', 'desc')
                ->get();
            
            if ($orders->count() > 0) {
                $orderStats = [
                    'total_orders' => $orders->count(),
                    'total_spent' => $orders->sum('total_amount'),
                    'completed_orders' => $orders->where('status', 'completed')->count(),
                    'pending_orders' => $orders->where('status', 'pending')->count(),
                    'cancelled_orders' => $orders->whereIn('status', ['cancelled_by_customer', 'cancelled_by_admin'])->count(),
                    'paid_orders' => $orders->where('payment_status', 'paid')->count(),
                    'unpaid_orders' => $orders->where('payment_status', 'unpaid')->count(),
                ];
            }
        }
        
        return view('admin.users.user-edit', compact('user', 'orderStats', 'orders'));
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        if ($user->id === $currentUser->id && $request->role !== $user->role) {
            return redirect()->back()
                ->with('error', 'Bạn không thể thay đổi vai trò của chính mình!');
        }

        $data = $request->validate([
            'name' => 'required|string|max:125',
            'email' => 'required|email|unique:users,email,' . $user->id . '|max:125',
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'date_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'role' => 'required|in:admin,customer',
            'status' => 'required|in:active,locked,banned',
            'banned_until' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($data['email'] !== $user->email) {
            $data['email_verified_at'] = null;
        }

        if (isset($data['phone']) && $data['phone'] !== $user->phone) {
            $data['phone_verified_at'] = null;
        }

        if ($data['status'] !== 'banned') {
            $data['banned_until'] = null;
        }

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $data['image'] = $request->file('image')->store('users', 'public');
        } elseif ($request->input('remove_image') == '1') {
            // Xóa avatar nếu có flag remove_image
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $data['image'] = null;
        }

        $user->update($data);

        return redirect()->route('admin.users.list')
            ->with('success', 'Cập nhật người dùng thành công!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        if ($user->id === $currentUser->id) {
            return redirect()->back()
                ->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        $orderCount = $user->orders()->count();
        if ($orderCount > 0) {
            return redirect()->back()
                ->with('error', "Không thể xóa người dùng này! Người dùng có {$orderCount} đơn hàng. Vui lòng khóa tài khoản thay vì xóa.");
        }

        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();

        return redirect()->route('admin.users.list')
            ->with('success', 'Xóa người dùng thành công!');
    }


    public function toggleLock($id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        if ($user->id === $currentUser->id) {
            return redirect()->back()
                ->with('error', 'Bạn không thể khóa tài khoản của chính mình!');
        }

        if ($user->status === 'locked') {
            $user->status = 'active';
            $user->banned_until = null;
            $message = 'Đã mở khóa tài khoản thành công!';
        } else {
            $user->status = 'locked';
            $message = 'Đã khóa tài khoản thành công!';
        }

        $user->save();

        return redirect()->back()
            ->with('success', $message);
    }

}

