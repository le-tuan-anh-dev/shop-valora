@extends('client.layouts.master')

@section('title', 'Velora - Đăng Ký')

@section('content')
<section class="section-b-space pt-0"> 
  <div class="heading-banner">
    <div class="custom-container container">
      <div class="row align-items-center">
        <div class="col-sm-6">
          <h4>Đăng Ký</h4>
        </div>
        <div class="col-sm-6">
          <ul class="breadcrumb float-end">
            <li class="breadcrumb-item">
              <a href="{{ route('home') }}">Trang Chủ</a>
            </li>
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
            <p>Tạo tài khoản mới</p>
          </div>

          <div class="login-box"> 
            <form class="row g-3" method="POST" action="{{ route('register.post') }}">
              @csrf
              <div class="col-12"> 
                <div class="form-floating">
                  <input class="form-control @error('name') is-invalid @enderror"
                         id="floatingInputValue"
                         type="text"
                         name="name"
                         value="{{ old('name') }}"
                         placeholder="Họ và Tên">
                  <label for="floatingInputValue">Nhập Họ và Tên</label>
                  @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-12"> 
                <div class="form-floating">
                  <input class="form-control @error('email') is-invalid @enderror"
                         id="floatingInputValue1"
                         {{-- type="email" --}}
                         type="text"
                         name="email"
                         value="{{ old('email') }}"
                         placeholder="email@example.com">
                  <label for="floatingInputValue1">Nhập Email</label>
                  @error('email')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-12"> 
                <div class="form-floating">
                  <input class="form-control @error('phone') is-invalid @enderror"
                         id="floatingPhone"
                         type="text"
                         name="phone"
                         value="{{ old('phone') }}"
                         placeholder="Số điện thoại">
                  <label for="floatingPhone">Nhập Số Điện Thoại</label>
                  @error('phone')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-12"> 
                <div class="form-floating">
                  <input class="form-control @error('password') is-invalid @enderror"
                         id="floatingInputValue2"
                         type="password"
                         name="password"
                         placeholder="Mật khẩu">
                  <label for="floatingInputValue2">Nhập Mật Khẩu</label>
                  @error('password')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-12"> 
                <div class="form-floating">
                  <input class="form-control"
                         id="floatingInputValue3"
                         type="password"
                         name="password_confirmation"
                         placeholder="Xác nhận mật khẩu">
                  <label for="floatingInputValue3">Xác Nhận Mật Khẩu</label>
                </div>
              </div>

             

              <div class="col-12"> 
                <button class="btn login btn_black sm" type="submit">Đăng Ký</button>
              </div>
            </form>
          </div>

          <div class="other-log-in"> 
            <h6>HOẶC</h6>
          </div>


          <div class="other-log-in"></div>

          <div class="sign-up-box"> 
            <p>Đã có tài khoản?</p>
            <a href="{{ route('login.show') }}">Đăng Nhập</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
