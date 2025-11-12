@extends('client.layouts.master')

@section('title', 'Katie - Cart')

@section('content')
<section class="section-b-space pt-0"> 
  <div class="heading-banner">
    <div class="custom-container container">
      <div class="row align-items-center">
        <div class="col-sm-6">
          <h4>Cart</h4>
        </div>
        <div class="col-sm-6">
          <ul class="breadcrumb float-end">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="#">Cart</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-b-space pt-0">
  <div class="custom-container container"> 
    <div class="row g-4">
      <div class="col-12"> 
        <div class="cart-countdown">
          <img src="{{ asset('client/assets/images/gif/fire-2.gif') }}" alt="Fire Icon">
          <h6>Please, hurry! Someone has placed an order on one of the items you have in the cart. We'll keep it for you for<span id="countdown"></span> minutes.</h6>
        </div>
      </div>
      <div class="col-xxl-9 col-xl-8">
        <div class="cart-table">
          <div class="table-title"> 
            <h5>Cart<span id="cartTitle">(3)</span></h5>
            <button id="clearAllButton">Clear All</button>
          </div>
          <div class="table-responsive theme-scrollbar"> 
            <table class="table" id="cart-table">
              <thead>
                <tr> 
                  <th>Product</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Total</th>
                  <th></th>
                </tr>
              </thead>
              <tbody> 
                {{-- This part should be looped for each item in the cart --}}
                <tr> 
                  <td> 
                    <div class="cart-box">
                      <a href="#"><img src="{{ asset('client/assets/images/cart/category/1.jpg') }}" alt="Product Image"></a>
                      <div>
                        <a href="#"><h5>Concrete Jungle Pack</h5></a>
                        <p>Sold By: <span>Roger Group</span></p>
                        <p>Size: <span>Small</span></p>
                      </div>
                    </div>
                  </td>
                  <td>\$20.00</td>
                  <td>
                    <div class="quantity">
                      <button class="minus" type="button"><i class="fa-solid fa-minus"></i></button>
                      <input type="number" value="1" min="1" max="20">
                      <button class="plus" type="button"><i class="fa-solid fa-plus"></i></button>
                    </div>
                  </td>
                  <td>\$195.00</td>
                  <td><a class="deleteButton" href="javascript:void(0)"><i class="iconsax" data-icon="trash"></i></a></td>
                </tr>
                <tr> 
                  <td> 
                    <div class="cart-box">
                      <a href="#"><img src="{{ asset('client/assets/images/cart/category/2.jpg') }}" alt="Product Image"></a>
                      <div>
                        <a href="#"><h5>Mini dress with ruffled straps</h5></a>
                        <p>Sold By: <span>luisa Shop</span></p>
                        <p>Size: <span>Medium</span></p>
                      </div>
                    </div>
                  </td>
                  <td>\$20.00</td>
                  <td>
                    <div class="quantity">
                      <button class="minus" type="button"><i class="fa-solid fa-minus"></i></button>
                      <input type="number" value="1" min="1" max="20">
                      <button class="plus" type="button"><i class="fa-solid fa-plus"></i></button>
                    </div>
                  </td>
                  <td>\$150.00</td>
                  <td><a class="deleteButton" href="javascript:void(0)"><i class="iconsax" data-icon="trash"></i></a></td>
                </tr>
                <tr> 
                  <td> 
                    <div class="cart-box">
                      <a href="#"><img src="{{ asset('client/assets/images/cart/category/3.jpg') }}" alt="Product Image"></a>
                      <div>
                        <a href="#"><h5>Long Sleeve Asymmetric</h5></a>
                        <p>Sold By: <span>Brown Shop</span></p>
                        <p>Size: <span>Large</span></p>
                      </div>
                    </div>
                  </td>
                  <td>\$25.00</td>
                  <td>
                    <div class="quantity">
                      <button class="minus" type="button"><i class="fa-solid fa-minus"></i></button>
                      <input type="number" value="1" min="1" max="20">
                      <button class="plus" type="button"><i class="fa-solid fa-plus"></i></button>
                    </div>
                  </td>
                  <td>\$120.00</td>
                  <td><a class="deleteButton" href="javascript:void(0)"><i class="iconsax" data-icon="trash"></i></a></td>
                </tr>
                 {{-- End of loop --}}
              </tbody>
            </table>
          </div>
          {{-- This part should be shown when the cart is empty --}}
          <div class="no-data" id="data-show" style="display: none;">
            <img src="{{ asset('client/assets/images/cart/1.gif') }}" alt="Empty Cart">
            <h4>You have nothing in your shopping cart!</h4>
            <p>Today is a great day to purchase the things you have been holding onto! or <span>Carry on Buying</span></p>
          </div>
        </div>
      </div>
      <div class="col-xxl-3 col-xl-4">
        <div class="cart-items">      
          <div class="cart-progress">
            <div class="progress">
              <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 43%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                <span><i class="iconsax" data-icon="truck-fast"></i></span>
              </div>
            </div>
            <p>Almost there, add <span>\$267.00</span> more to get <span>FREE Shipping !!</span></p>
          </div>
          <div class="cart-body"> 
            <h6>Price Details (3 Items)</h6>
            <ul> 
              <li><p>Bag total</p><span>\$220.00</span></li>
              <li><p>Bag savings</p><span class="theme-color">-\$20.00</span></li>
              <li><p>Coupon Discount</p><span class="Coupon">Apply Coupon</span></li>
              <li><p>Delivery</p><span>\$50.00</span></li>
            </ul>
          </div>
          <div class="cart-bottom"> 
            <p><i class="iconsax me-1" data-icon="tag-2"></i>SPECIAL OFFER (-\$1.49)</p>
            <h6>Subtotal <span>\$158.41</span></h6>
            <span>Taxes and shipping calculated at checkout</span>
          </div>
          <div class="coupon-box"> 
            <h6>Coupon</h6>
            <ul> 
              <li>
                <span><input type="text" placeholder="Apply Coupon"><i class="iconsax me-1" data-icon="tag-2"></i></span>
                <button class="btn">Apply</button>
              </li>
              <li><span><a class="theme-color" href="{{ url('/login') }}">Login</a> to see best coupon for you</span></li>
            </ul>
          </div>
          <a class="btn btn_black w-100 rounded sm" href="{{ url('/checkout') }}">Check Out</a>
        </div>
      </div>
      <div class="col-12"> 
        <div class="cart-slider"> 
          <div class="d-flex align-items-start justify-content-between">
            <div> 
              <h6>For a trendy and modern twist, especially during transitional seasons.</h6>
              <p><img class="me-2" src="{{ asset('client/assets/images/gif/discount.gif') }}" alt="Discount">You will get 10% OFF on each product</p>
            </div>
            <a class="btn btn_outline sm rounded" href="{{ url('/shop') }}">View All
              <svg>
                <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow"></use>
              </svg>
            </a>
          </div>
          <div class="swiper cart-slider-box">
            <div class="swiper-wrapper"> 
              <div class="swiper-slide">
                <div class="cart-box">
                  <a href="#"><img src="{{ asset('client/assets/images/user/4.jpg') }}" alt="Product Image"></a>
                  <div>
                    <a href="#"><h5>Polo-neck Body Dress</h5></a>
                    <h6>Sold By: <span>Brown Shop</span></h6>
                    <p>\$19.90 <span><del>\$14.90</del></span></p>
                    <a class="btn" href="#">Add</a>
                  </div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="cart-box">
                  <a href="#"><img src="{{ asset('client/assets/images/user/5.jpg') }}" alt="Product Image"></a>
                  <div>
                    <a href="#"><h5>Short Sleeve Sweater</h5></a>
                    <h6>Sold By: <span>Brown Shop</span></h6>
                    <p>\$22.90 <span><del>\$24.90</del></span></p>
                    <a class="btn" href="#">Add</a>
                  </div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="cart-box">
                  <a href="#"><img src="{{ asset('client/assets/images/user/6.jpg') }}" alt="Product Image"></a>
                  <div>
                    <a href="#"><h5>Oversized Cotton Short</h5></a>
                    <h6>Sold By: <span>Brown Shop</span></h6>
                    <p>\$10.90 <span><del>\$18.90</del></span></p>
                    <a class="btn" href="#">Add</a>
                  </div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="cart-box">
                  <a href="#"><img src="{{ asset('client/assets/images/user/7.jpg') }}" alt="Product Image"></a>
                  <div>
                    <a href="#"><h5>Oversized Women Shirt</h5></a>
                    <h6>Sold By: <span>Brown Shop</span></h6>
                    <p>\$15.90 <span><del>\$20.90</del></span></p>
                    <a class="btn" href="#">Add</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection