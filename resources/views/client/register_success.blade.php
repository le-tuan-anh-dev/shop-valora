@extends('client.layouts.master')


@section('title', 'Xác thực Email')

@section('content')
<div style="display:flex; justify-content:center; align-items:center; min-height:60vh;">
    <div style="
        background:white; 
        padding:40px; 
        border-radius:20px; 
        box-shadow:0 8px 25px rgba(0,0,0,0.1); 
        text-align:center;
        max-width:450px;
        width:100%;
        ">
        
        <!-- Icon Email -->
        <i class="fa-solid fa-envelope-circle-check" 
           style="font-size:70px; color:#4A8DFF; margin-bottom:20px;"></i>

        <h2 style="font-weight:700; margin-bottom:10px;">
            Kiểm tra Email của bạn
        </h2>

        <p style="font-size:16px; color:#555; margin-bottom:25px;">
            Chúng tôi đã gửi một email xác thực tới hộp thư của bạn. 
            Vui lòng nhấp vào liên kết trong email để kích hoạt tài khoản.
        </p>

        <div class="col-12"> 
            <a href="{{ route('login.show') }}">
                <button class="btn login btn_black sm" type="submit">Đăng nhập</button></a>
        </div>
    </div>
</div>
@endsection
