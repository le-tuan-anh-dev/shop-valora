<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $freshUser = \App\Models\User::where('id', $userId)->first();

            if (!$freshUser || $freshUser->status !== 'active') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Bắn noti khác nhau cho từng trạng thái
                $message = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin để được hỗ trợ.';

                if ($freshUser && $freshUser->status === 'banned') {
                    $message = 'Tài khoản của bạn đã bị cấm. Vui lòng liên hệ admin để được hỗ trợ.';
                }

                return redirect()->route('login.show')->with('error', $message);
            }
        }

        return $next($request);
    }
}
