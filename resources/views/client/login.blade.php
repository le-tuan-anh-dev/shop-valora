@extends('client.layouts.master')

@section('title', 'Velora - Cửa Hàng Thời Trang Online')

@section('content')
<section class="section-b-space pt-0"> 
    <div class="heading-banner">
        <div class="custom-container container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4>Đăng Nhập</h4>
                </div>
                <div class="col-sm-6">
                    <ul class="breadcrumb float-end">
                        <li class="breadcrumb-item"> <a href="{{ route('home') }}">Trang Chủ</a></li>
                        <li class="breadcrumb-item active"> <a href="#">Đăng Nhập</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-b-space pt-0 login-bg-img">
    <div class="custom-container container login-page"> 
        <div class="row align-items-center"> 
            <div class="col-xxl-7 col-6 d-none d-lg-block">
                <div class="login-img"> 
                    <img class="img-fluid" src="{{ asset('client/assets/images/other-img/dashboard.png') }}" alt="">
                </div>
            </div>
            <div class="col-xxl-4 col-lg-6 mx-auto">
                <div class="log-in-box">
                    <div class="log-in-title"> 
                        <h4>Chào Mừng Đến Velora</h4>
                        <p>Đăng nhập vào tài khoản của bạn</p>
                    </div>

                    {{-- Hiển thị thông báo --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif


                    <div class="login-box"> 
                        <form class="row g-3" method="POST" action="{{ route('login.post') }}">
                            @csrf
                            <div class="col-12"> 
                                <div class="form-floating">
                                    <input class="form-control @error('email') is-invalid @enderror"
                                           id="floatingInputValue"
                                           type="email"
                                           name="email"
                                           placeholder="email@example.com"
                                           value="{{ old('email') }}">
                                    <label for="floatingInputValue">Nhập Email</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12"> 
                                <div class="form-floating">
                                    <input class="form-control @error('password') is-invalid @enderror"
                                           id="floatingInputValue1"
                                           type="password"
                                           name="password"
                                           placeholder="Mật khẩu">
                                    <label for="floatingInputValue1">Nhập Mật Khẩu</label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-12"> 
                                <button class="btn login btn_black sm" type="submit">Đăng Nhập</button>
                            </div>
                        </form>
                    </div>

                    <div class="other-log-in"> 
                        <h6>HOẶC</h6>
                    </div>
                    <div class="sign-up-box"> 
                        <p>Chưa có tài khoản?</p>
                        <a href="{{ route('register.show') }}">Đăng Ký</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
