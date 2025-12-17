<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

        // ĐOẠN SỬA: load sâu tới attributeValues.attribute
        $baseOrdersQuery = $user->orders()
            ->with([
                'items.product.brand',               // sản phẩm + brand
                'items.variant.attributeValues.attribute', // biến thể + giá trị thuộc tính + tên thuộc tính
                'paymentMethod',
            ])
            ->orderByDesc('created_at');

        // Lấy 10 đơn gần nhất
        $orders      = (clone $baseOrdersQuery)->take(10)->get();
        // Đếm tổng số đơn (không bị ảnh hưởng bởi take(10))
        $totalOrders = (clone $baseOrdersQuery)->count();
        // Tổng tiền đã chi
        $totalSpent  = $user->orders()->sum('total_amount');

        // Thông báo mới nhất
        $notifications = $user->notifications()->latest()->take(10)->get();

        // Danh sách sản phẩm yêu thích
        $wishlistProducts = $user->wishlistProducts()
            ->orderByDesc('wishlists.created_at')
            ->get();
            
        $provinces = $this->getProvinces();
        

        return view('client.dashboard', [
            'user'              => $user,
            'addresses'         => $addresses,
            'defaultAddress'    => $defaultAddress,
            'orders'            => $orders,
            'totalOrders'       => $totalOrders,
            'totalSpent'        => $totalSpent,
            'notifications'     => $notifications,
            'wishlistProducts'  => $wishlistProducts,
            'provinces' => $provinces,
        ]);
    }

        // lấy tỉnh
    public function getProvinces()
    {
        try {
            $response = Http::withHeaders([
                'Token' => env('GHN_TOKEN'),
            ])->get('https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/province');

            if ($response->ok() && isset($response->json()['data'])) {
                return $response->json()['data'];
            }
        } catch (\Exception $e) {
            Log::error('GHN getProvinces error: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Cập nhật thông tin tài khoản (tên, email, sđt, avatar).
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'profile_name'  => ['required', 'string', 'max:100'],
            'profile_email' => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'profile_phone' => ['nullable', 'string', 'max:10'],
            'profile_image' => ['nullable', 'image',],
        ], [
            'profile_name.required'    => 'Vui lòng nhập họ tên.',
            'profile_name.string'      => 'Họ tên phải là ký tự.',
            'profile_name.max'         => 'Họ tên không được vượt quá 100 ký tự.',
            
            'profile_email.required'   => 'Vui lòng nhập địa chỉ email.',
            'profile_email.email'      => 'Địa chỉ email không hợp lệ.',
            'profile_email.max'        => 'Email không được vượt quá 100 ký tự.',
            'profile_email.unique'     => 'Email này đã được sử dụng.',
            
            'profile_phone.string'     => 'Số điện thoại phải là ký tự.',
            'profile_phone.max'        => 'Số điện thoại không đúng định dạng',
            
            'profile_image.image'      => 'Tệp tải lên phải là hình ảnh.',
        ]);


        $user->name  = $request->profile_name;
        $user->email = $request->profile_email;
        $user->phone = $request->profile_phone;

        if ($request->hasFile('profile_image')) {
            // Xoá ảnh cũ nếu có
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // Lưu ảnh mới
            $path        = $request->file('profile_image')->store('avatars', 'public');
            $user->image = $path;
        }

        $user->save();

        return back()->with('success', 'Cập nhật thông tin tài khoản thành công.');
    }

   public function changePassword(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'current_password' => [
            'required',
            function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Mật khẩu hiện tại không chính xác.');
                }
            },
        ],
        'new_password' => [
            'required',
            'string',
            'min:6',
            'confirmed',
            'different:current_password',
        ],
        'new_password_confirmation' => [
            'required',
            'string',
        ],
    ], [
        'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
        
        'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
        'new_password.string' => 'Mật khẩu mới phải là ký tự.',
        'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
        'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        'new_password.different' => 'Mật khẩu mới không được giống mật khẩu hiện tại.',
        
        'new_password_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới.',
        'new_password_confirmation.string' => 'Xác nhận mật khẩu phải là ký tự.',
    ]);

    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Mật khẩu đã được thay đổi thành công.');
}
}