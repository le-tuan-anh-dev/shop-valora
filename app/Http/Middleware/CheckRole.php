<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Kiểm tra đăng nhập trước
        if (!Auth::check()) {
            return redirect()->route('login.show')->with('error', 'Vui lòng đăng nhập.');
        }

        $user = Auth::user();
        
        // Kiểm tra user có role attribute không
        if (!$user || !isset($user->role)) {
            return redirect()->route('home')->with('error', 'Lỗi: Không tìm thấy role.');
        }

        $userRole = $user->role;

        // Admin có toàn quyền
        if ($userRole === 'admin') {
            return $next($request);
        }

        // Các role khác kiểm tra bình thường
        if (!in_array($userRole, $roles)) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}