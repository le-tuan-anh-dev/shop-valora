@extends('client.layouts.master')

@section('title', 'Contact')

@section('content')
<section class="section-b-space pt-0"> 
  <div class="heading-banner">
    <div class="custom-container container">
      <div class="row align-items-center">
        <div class="col-sm-6">
          <h4>Contact</h4>
        </div>
        <div class="col-sm-6">
          <ul class="breadcrumb float-end">
            <li class="breadcrumb-item"> <a href="{{ url('/') }}">Home </a></li>
            <li class="breadcrumb-item active"> <a href="#">Contact</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-b-space pt-0"> 
  <div class="custom-container container">
    <div class="contact-main"> 
      <div class="row gy-3">
        <div class="col-12">
          <div class="title-1 address-content"> 
            <p class="pb-0">Let's Get In Touch<span></span></p>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="address-items"> 
            <div class="icon-box"> <i class="iconsax" data-icon="location"></i></div>
            <div class="contact-box"> 
              <h6>Contact Number</h6>
              <p>+91 123 - 456 - 7890</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="address-items"> 
            <div class="icon-box"> <i class="iconsax" data-icon="phone-calling"></i></div>
            <div class="contact-box"> 
              <h6>Email Address</h6>
              <p>katie098@gmail.com</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="address-items"> 
            <div class="icon-box"> <i class="iconsax" data-icon="mail"></i></div>
            <div class="contact-box"> 
              <h6>Other Address</h6>
              <p>ABC Complex, New York USA 123456</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="address-items"> 
            <div class="icon-box"> <i class="iconsax" data-icon="map-1"></i></div>
            <div class="contact-box"> 
              <h6>Bournemouth Office</h6>
              <p>Visitación de la Encina 22</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-b-space pt-0"> 
  <div class="custom-container container">
    <div class="contact-main"> 
      <div class="row align-items-center gy-4">
        <div class="col-lg-6 order-lg-1 order-2">
          <div class="contact-box"> 
            <h4>Contact Us </h4>
            <p>If you've got fantastic products or want to collaborate, reach out to us. </p>
            <div class="contact-form">  
              <div class="row gy-4">
                <div class="col-12"> 
                  <label class="form-label" for="inputEmail4">Full Name </label>
                  <input class="form-control" id="inputEmail4" type="text" name="text" placeholder="Enter Full Name">
                </div>
                <div class="col-6">
                  <label class="form-label" for="inputEmail5">Email Address</label>
                  <input class="form-control" id="inputEmail5" type="email" name="email" placeholder="Enter Email Address">
                </div>
                <div class="col-6">
                  <label class="form-label" for="inputEmail6">Phone Number</label>
                  <input class="form-control" id="inputEmail6" type="number" name="number" placeholder="Enter Phone Number">
                </div>
                <div class="col-12"> 
                  <label class="form-label" for="inputEmail7">Subject</label>
                  <input class="form-control" id="inputEmail7" type="text" name="text" placeholder="Enter Subject">
                </div>
                <div class="col-12"> 
                  <label class="form-label">Message</label>
                  <textarea class="form-control" id="message" rows="6" placeholder="Enter Your Message"></textarea>
                </div>
                <div class="col-12"> 
                  <button class="btn btn_black rounded sm" type="submit"> Send Message </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-5 col-lg-6 order-lg-2 order-1 offset-xl-1">
          <div class="contact-img"> <img class="img-fluid" src="{{ asset('client/assets/images/contact/1.svg') }}" alt=""></div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection