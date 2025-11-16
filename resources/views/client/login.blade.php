@extends('client.layouts.master')

@section('title', 'Katie - Online Fashion Store')

@section('content')
<section class="section-b-space pt-0"> 
    <div class="heading-banner">
        <div class="custom-container container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4>Login</h4>
                </div>
                <div class="col-sm-6">
                    <ul class="breadcrumb float-end">
                        <li class="breadcrumb-item"> <a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active"> <a href="#">Login</a></li>
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
                    <img class="img-fluid" src="{{ asset('client/assets/images/login/1.svg') }}" alt="Login Illustration">
                </div>
            </div>
            <div class="col-xxl-4 col-lg-6 mx-auto">
                <div class="log-in-box">
                    <div class="log-in-title"> 
                        <h4>Welcome To Katie</h4>
                        <p>Login to your account</p>
                    </div>

                    {{-- Hiển thị message --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
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
                                           placeholder="name@example.com"
                                           value="{{ old('email') }}">
                                    <label for="floatingInputValue">Enter Your Email</label>
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
                                           placeholder="Password">
                                    <label for="floatingInputValue1">Enter Your Password</label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="forgot-box">
                                    <div>
                                        <input class="custom-checkbox me-2" id="rememberMe" type="checkbox" name="remember">
                                        <label for="rememberMe">Remember me</label>
                                    </div>
                                    <a href="#">Forgot Password?</a>
                                </div>
                            </div>
                            <div class="col-12"> 
                                <button class="btn login btn_black sm" type="submit">Log In</button>
                            </div>
                        </form>
                    </div>

                    <div class="other-log-in"> 
                        <h6>OR</h6>
                    </div>

                    <div class="log-in-button"> 
                        <ul> 
                            <li>
                                <a href="{{ route('auth.google.redirect') }}">
                                    <i class="fa-brands fa-google me-2"></i>Google
                                </a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/" target="_blank">
                                    <i class="fa-brands fa-facebook-f me-2"></i>Facebook
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="sign-up-box"> 
                        <p>Don't have an account?</p>
                        <a href="{{ route('register.show') }}">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection