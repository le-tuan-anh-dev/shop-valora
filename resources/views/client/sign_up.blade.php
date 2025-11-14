@extends('client.layouts.master')

@section('title', 'Katie - Sign Up')

@section('content')
    <section class="section-b-space pt-0"> 
      <div class="heading-banner">
        <div class="custom-container container">
          <div class="row align-items-center">
            <div class="col-sm-6">
              <h4>Sign Up</h4>
            </div>
            <div class="col-sm-6">
              <ul class="breadcrumb float-end">
                <li class="breadcrumb-item"> <a href="">Home </a></li>
                
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
            <div class="login-img"> <img class="img-fluid" src="{{ asset('client/assets/images/login/1.svg') }}" alt="Sign Up Illustration"></div>
          </div>
          <div class="col-xxl-4 col-lg-6 mx-auto">
            <div class="log-in-box">
              <div class="log-in-title"> 
                <h4>Welcome To Katie</h4>
                <p>Create New Account</p>
              </div>
              <div class="login-box"> 
                <form class="row g-3">
                  <div class="col-12"> 
                    <div class="form-floating">
                      <input class="form-control" id="floatingInputValue" type="text" placeholder="Full Name">
                      <label for="floatingInputValue">Enter Your Name</label>
                    </div>
                  </div>
                  <div class="col-12"> 
                    <div class="form-floating">
                      <input class="form-control" id="floatingInputValue1" type="email" placeholder="name@example.com">
                      <label for="floatingInputValue1">Enter Your Email</label>
                    </div>
                  </div>
                  <div class="col-12"> 
                    <div class="form-floating">
                      <input class="form-control" id="floatingInputValue2" type="password" placeholder="Password">
                      <label for="floatingInputValue2">Enter Your Password</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="forgot-box">
                      <div>
                        <input class="custom-checkbox me-2" id="category1" type="checkbox" name="text">
                        <label for="category1">I agree with <span><a href="#">Terms</a></span> and <span><a href="#">Privacy</a></span></label>
                      </div>
                    </div>
                  </div>
                  <div class="col-12"> 
                    <button class="btn login btn_black sm" type="submit">Sign Up</button>
                  </div>
                </form>
              </div>
              <div class="other-log-in"> 
                <h6>OR</h6>
              </div>
              <div class="log-in-button"> 
                <ul> 
                  <li> <a href="https://www.google.com/" target="_blank"> <i class="fa-brands fa-google me-2"> </i>Google</a></li>
                  <li> <a href="https://www.facebook.com/" target="_blank"><i class="fa-brands fa-facebook-f me-2"></i>Facebook </a></li>
                </ul>
              </div>
              <div class="other-log-in"></div>
              <div class="sign-up-box"> 
                <p>Already have an account?</p><a href="">Log In </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection