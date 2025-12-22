<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmailMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // ==== FORM ====

    public function showLoginForm()
    {
        return view('client.login');
    }

    public function showRegisterForm()
    {
        return view('client.sign_up');
    }

    // ==== ĐĂNG KÝ THƯỜNG ====

    public function register(Request $request)
    {
        $messages = [
            'name.required' => 'Họ và tên không được để trống.',
            'name.string'   => 'Họ và tên phải là chuỗi ký tự.',
            'name.max'      => 'Họ và tên không được vượt quá 100 ký tự.',

            'email.required' => 'Email không được để trống.',
            'email.email'    => 'Email không đúng định dạng.',
            'email.unique'   => 'Email đã tồn tại.',
            'email.max'      => 'Email không được vượt quá 100 ký tự.',

            'password.required'  => 'Mật khẩu không được để trống.',
            'password.string'    => 'Mật khẩu phải là chuỗi ký tự.',
            'password.min'       => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',

            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.regex'  => 'Số điện thoại phải bắt đầu bằng 0 và có đúng 10 chữ số.',
        ];

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email|max:100',
            'password' => 'required|string|min:6|confirmed',
            'phone'    => 'nullable|string|regex:/^0\d{9}$/',
        ], $messages);

        // Tạo token xác thực
        $token = Str::random(60);

        $user = User::create([
            'name'               => $request->name,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'phone'              => $request->phone,
            'role'               => 'customer',
            'status'             => 'locked',
            'email_verified_at'  => null,
            'verification_token' => $token,
        ]);

        // Gửi mail xác nhận
        $verifyUrl = route('auth.verify', ['token' => $token]);
        Mail::to($user->email)->send(new VerifyEmailMail($verifyUrl));

        return view('client.register_success');
    }

    // ==== XÁC NHẬN EMAIL (DÙNG CHUNG CHO CẢ FORM & GOOGLE) ====

    public function verifyEmail(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('login.show')
                ->with('error', 'Token xác nhận không hợp lệ.');
        }

        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login.show')
                ->with('error', 'Tài khoản đã được xác thực.');
        }

        // Kích hoạt tài khoản
        $user->status = 'active';
        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        Auth::login($user);
        session(['user_id' => $user->id]);


        return redirect()->route('home')->with('success', 'Tài khoản của bạn đã được kích hoạt thành công!');
    }

    // ==== ĐĂNG NHẬP THƯỜNG ====

    public function login(Request $request)
    {
        $messages = [
            'email.required'    => 'Email không được để trống.',
            'email.email'       => 'Email không đúng định dạng.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.string'   => 'Mật khẩu phải là chuỗi ký tự.',
        ];

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], $messages);

        // Chỉ cho phép user status = active
        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
            'status'   => 'active',
        ];

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            $request->session()->put('user_id', Auth::id());
            return redirect()->intended(route('home'))->with('success', 'Đăng nhập thành công!');
        }

        // Kiểm tra xem có phải do chưa kích hoạt hoặc bị khóa
        $user = User::where('email', $request->email)->first();

        if ($user && $user->status !== 'active') {
            if (!Hash::check($request->password, $user->password)) {
                return back()->withErrors([
                    'email' => 'Email hoặc mật khẩu không đúng.',
                ])->withInput();
            }

            if ($user->status === 'locked' && is_null($user->email_verified_at)) {
                return back()->withErrors([
                    'email' => 'Tài khoản chưa được kích hoạt. Vui lòng kiểm tra email để xác nhận.',
                ])->withInput();
            } elseif ($user->status === 'banned') {
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn đã bị cấm. Vui lòng liên hệ admin để được hỗ trợ.',
                ])->withInput();
            } else {
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin để được hỗ trợ.',
                ])->withInput();
            }
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput();
    }

    // ==== ĐĂNG XUẤT ====

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Đã đăng xuất.');
    }

    // ==== GOOGLE LOGIN (ĐĂNG KÝ + XÁC NHẬN EMAIL) ====

    // public function redirectToGoogle()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // public function handleGoogleCallback()
    // {
    //     try {
    //         $googleUser = Socialite::driver('google')->user();
    //     } catch (\Exception $e) {
    //         return redirect()->route('login.show')->withErrors([
    //             'google' => 'Không thể đăng nhập bằng Google. Vui lòng thử lại.',
    //         ]);
    //     }

    //     // Nếu đã có user với email này
    //     $user = User::where('email', $googleUser->getEmail())->first();

    //     if ($user) {
    //         // Nếu đã active => login ngay
    //         if ($user->status === 'active') {
    //             Auth::login($user);
    //             return redirect()->route('home')->with('success', 'Đăng nhập bằng Google thành công!');
    //         }

    //         // Nếu chưa active, gửi lại mail xác nhận nếu thiếu token
    //         if (!$user->verification_token) {
    //             $user->verification_token = Str::random(60);
    //             $user->save();
    //         }

    //         $verifyUrl = route('auth.verify', ['token' => $user->verification_token]);
    //         Mail::to($user->email)->send(new VerifyEmailMail($verifyUrl));

    //         return redirect()->route('login.show')->with('success',
    //             'Tài khoản Google của bạn chưa được kích hoạt. Vui lòng kiểm tra email để xác nhận.'
    //         );
    //     }

    //     // Nếu chưa có user => tạo user ở trạng thái locked, chờ xác nhận
    //     $token = Str::random(60);

    //     $user = User::create([
    //         'name'               => $googleUser->getName() ?? $googleUser->getNickname() ?? 'User',
    //         'email'              => $googleUser->getEmail(),
    //         'google_id'          => $googleUser->getId(),
    //         'password'           => Hash::make(uniqid('google_', true)),
    //         'image'              => $googleUser->getAvatar(),
    //         'role'               => 'customer',
    //         'status'             => 'locked',        // CHƯA ACTIVE
    //         'email_verified_at'  => null,
    //         'verification_token' => $token,
    //     ]);

    //     // Gửi email xác nhận
    //     $verifyUrl = route('auth.verify', ['token' => $token]);
    //     Mail::to($user->email)->send(new VerifyEmailMail($verifyUrl));

    //     return redirect()->route('login.show')->with('success',
    //         'Vui lòng kiểm tra email để xác nhận tài khoản Google trước khi đăng nhập.'
    //     );
    // }
}