<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckUserStatus
{
    /**
     * Nếu tài khoản bị khóa (locked/banned), tự động logout user.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Lấy user ID từ session
            $userId = Auth::id();
            
            // Log để debug
            Log::info('CheckUserStatus: Checking user', ['user_id' => $userId]);
            
            // Force query database để lấy thông tin mới nhất (không dùng cache)
            $freshUser = \App\Models\User::where('id', $userId)->first();
            
            if ($freshUser) {
                Log::info('CheckUserStatus: User found', [
                    'user_id' => $userId,
                    'status' => $freshUser->status,
                    'is_active' => $freshUser->status === 'active'
                ]);
            } else {
                Log::warning('CheckUserStatus: User not found in database', ['user_id' => $userId]);
            }
            
            // Nếu user không tồn tại hoặc status không phải 'active'
            if (!$freshUser || $freshUser->status !== 'active') {
                Log::warning('CheckUserStatus: Logging out user', [
                    'user_id' => $userId,
                    'reason' => !$freshUser ? 'user_not_found' : 'status_not_active',
                    'status' => $freshUser?->status
                ]);
                
                // Logout user
                Auth::logout();
                
                // Invalidate session
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Redirect về trang login với thông báo
                return redirect()->route('login.show')
                    ->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin để được hỗ trợ.');
            }
        }

        return $next($request);
    }
}