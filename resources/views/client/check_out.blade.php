@extends('client.layouts.master')

@section('title', 'Katie - Check Out')

@section('content')
<section class="section-b-space pt-0"> 
  <div class="heading-banner">
    <div class="custom-container container">
      <div class="row align-items-center">
        <div class="col-sm-6">
          <h4>Check Out</h4>
        </div>
        <div class="col-sm-6">
          <ul class="breadcrumb float-end">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="#">Check Out</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-b-space pt-0">
  <div class="custom-container container"> 
    {{-- Assuming the form submits to a 'place-order' route --}}
    <form action="{{ url('/place-order') }}" method="POST">
      @csrf
      <div class="row">
        <div class="col-xxl-9 col-lg-8"> 
          <div class="left-sidebar-checkout sticky">
            
            {{-- Shipping Address --}}
            <div class="address-option">
              <div class="address-title"> 
                <h4>Shipping Address</h4>
                <a href="#" data-bs-toggle="modal" data-bs-target="#address-modal" title="add product" tabindex="0">+ Add New Address</a>
              </div>
              <div class="row">
                {{-- This part should be looped for each saved shipping address --}} 
                <div class="col-xxl-4">
                  <label for="shipping-address-0">
                    <div class="delivery-address-box">
                      <div class="form-check"> 
                        <input class="custom-radio" id="shipping-address-0" type="radio" checked="checked" name="shipping_address" value="1">
                      </div>
                      <div class="address-detail">
                        <span class="address"><span class="address-title">New Home</span></span>
                        <span class="address"><span class="address-home"><span class="address-tag">Address:</span> 26, Starts Hollow Colony, Denver, Colorado, United States</span></span>
                        <span class="address"><span class="address-home"><span class="address-tag">Pin Code:</span> 80014</span></span>
                        <span class="address"><span class="address-home"><span class="address-tag">Phone:</span> +1 5551855359</span></span>
                      </div>
                    </div>
                  </label>
                </div>
                <div class="col-xxl-4">
                  <label for="shipping-address-1">
                    <div class="delivery-address-box">
                      <div class="form-check"> 
                        <input class="custom-radio" id="shipping-address-1" type="radio" name="shipping_address" value="2">
                      </div>
                      <div class="address-detail">
                        <span class="address"><span class="address-title">Old Home</span></span>
                        <span class="address"><span class="address-home"><span class="address-tag">Address:</span> 53B, Claire New Street, San Jose, California, United States</span></span>
                        <span class="address"><span class="address-home"><span class="address-tag">Pin Code:</span> 94088</span></span>
                        <span class="address"><span class="address-home"><span class="address-tag">Phone:</span> +1 5551855359</span></span>
                      </div>
                    </div>
                  </label>
                </div>
                {{-- End of loop --}}
              </div>
            </div>

            {{-- Billing Address --}}
            <div class="address-option">
              <div class="address-title"> 
                <h4>Billing Address</h4>
              </div>
              <div class="row"> 
                {{-- This part should be looped for each saved billing address --}}
                <div class="col-xxl-4">
                  <label for="billing-address-3">
                    <div class="delivery-address-box">
                      <div class="form-check"> 
                        <input class="custom-radio" id="billing-address-3" type="radio" checked="checked" name="billing_address" value="3">
                      </div>
                      <div class="address-detail">
                        <span class="address"><span class="address-title">Old Home</span></span>
                        <span class="address"><span class="address-home"><span class="address-tag">Address:</span> 456 Elm Street, Sample City, United States</span></span>
                        <span class="address"><span class="address-home"><span class="address-tag">Pin Code:</span> 35412</span></span>
                        <span class="address"><span class="address-home"><span class="address-tag">Phone:</span> +1 9547862134</span></span>
                      </div>
                    </div>
                  </label>
                </div>
                <div class="col-xxl-4">
                  <label for="billing-address-4">
                    <div class="delivery-address-box">
                      <div class="form-check"> 
                        <input class="custom-radio" id="billing-address-4" type="radio" name="billing_address" value="4">
                      </div>
                      <div class="address-detail">
                        <span class="address"><span class="address-title">IT Office</span></span>
                        <span class="address"><span class="address-home"><span class="address-tag">Address:</span> 101 Maple Drive, Placeholder Town, United States</span></span>
                        <span class="address"><span class="address-home"><span class="address-tag">Pin Code:</span> 57412</span></span>
                        <span class="address"><span class="address-home"><span class="address-tag">Phone:</span> +1 87453312145</span></span>
                      </div>
                    </div>
                  </label>
                </div>
                {{-- End of loop --}}
              </div>
            </div>

            {{-- Payment Options --}}
            <div class="payment-options">
              <h4 class="mb-3">Payment Methods</h4>
              <div class="row gy-3">
                <div class="col-sm-6"> 
                  <div class="payment-box">
                    <input class="custom-radio me-2" id="cod" type="radio" checked="checked" name="payment_method" value="cod">
                    <label for="cod">Cash On Delivery (COD)</label>
                  </div>
                </div>
                <div class="col-sm-6"> 
                  <div class="payment-box">
                    <input class="custom-radio me-2" id="stripe" type="radio" name="payment_method" value="stripe">
                    <label for="stripe">Stripe</label>
                  </div>
                </div>
                <div class="col-sm-6"> 
                  <div class="payment-box">
                    <input class="custom-radio me-2" id="paypal" type="radio" name="payment_method" value="paypal">
                    <label for="paypal">Paypal</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="col-xxl-3 col-lg-4">
          <div class="right-sidebar-checkout">
            <h4>Order Summary</h4>
            <div class="cart-listing">
              <ul>
                {{-- This part should be looped for each item in the cart --}}
                <li>
                  <img src="{{ asset('client/assets/images/other-img/7.jpg') }}" alt="Product">
                  <div> 
                    <h6>Printed Long-sleeve Dress</h6>
                    <span>Green</span>
                  </div>
                  <p>\$50.00</p>
                </li>
                <li>
                  <img src="{{ asset('client/assets/images/other-img/6.jpg') }}" alt="Product">
                  <div> 
                    <h6>Teddy Bear Coats</h6>
                    <span>Black</span>
                  </div>
                  <p>\$40.00</p>
                </li>
                <li>
                  <img src="{{ asset('client/assets/images/other-img/5.jpg') }}" alt="Product">
                  <div> 
                    <h6>Colorful wind Coats</h6>
                    <span>White</span>
                  </div>
                  <p>\$80.00</p>
                </li>
                {{-- End of loop --}}
              </ul>
              <div class="summary-total"> 
                <ul> 
                  <li><p>Subtotal</p><span>\$220.00</span></li>
                  <li><p>Shipping</p><span>\$0.00</span></li>
                  <li><p>Tax</p><span>\$2.54</span></li>
                  <li><p>Points</p><span>-\$10.00</span></li>
                  <li><p>Wallet Balance</p><span>-\$84.40</span></li>
                </ul>
                <div class="coupon-code"> 
                  <input type="text" name="coupon_code" placeholder="Enter Coupon Code">
                  <button class="btn" type="button">Apply</button>
                </div>
              </div>
              <div class="total">
                <h6>Total :</h6>
                <h6>\$37.73</h6>
              </div>
              <div class="order-button">
                <button type="submit" class="btn btn_black sm w-100 rounded">Place Order</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection