@extends('client.layouts.master')

@section('title', 'Katie - Dashboard')

@section('content')
<section class="section-b-space pt-0"> 
  <div class="heading-banner">
    <div class="custom-container container">
      <div class="row align-items-center">
        <div class="col-sm-6">
          <h4>Dashboard</h4>
        </div>
        <div class="col-sm-6">
          <ul class="breadcrumb float-end">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active"><a href="#">Dashboard</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-b-space pt-0"> 
  <div class="custom-container container user-dashboard-section"> 
    <div class="row">
      <div class="col-xl-3 col-lg-4">
        <div class="left-dashboard-show">
          <button class="btn btn_black sm rounded bg-primary">Show Menu</button>
        </div>
        <div class="dashboard-left-sidebar sticky">
          <div class="profile-box"> 
            <div class="profile-bg-img"></div>
            <div class="dashboard-left-sidebar-close"><i class="fa-solid fa-xmark"></i></div>
            <div class="profile-contain">
              <div class="profile-image"><img class="img-fluid" src="{{ asset('client/assets/images/user/12.jpg') }}" alt=""></div>
              <div class="profile-name"> 
                <h4>John Doe</h4>
                <h6>john.customer@example.com</h6><span data-bs-toggle="modal" data-bs-target="#edit-box" title="Quick View" tabindex="0">Edit Profile</span>
              </div>
            </div>
          </div>
          <ul class="nav flex-column nav-pills dashboard-tab" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <li>
              <button class="nav-link active" id="dashboard-tab" data-bs-toggle="pill" data-bs-target="#dashboard" role="tab" aria-controls="dashboard" aria-selected="true"><i class="iconsax" data-icon="home-1"></i> Dashboard</button>
            </li>
            <li>
              <button class="nav-link" id="notifications-tab" data-bs-toggle="pill" data-bs-target="#notifications" role="tab" aria-controls="notifications" aria-selected="false"><i class="iconsax" data-icon="lamp-2"></i>Notifications</button>
            </li>
            <li>
              <button class="nav-link" id="order-tab" data-bs-toggle="pill" data-bs-target="#order" role="tab" aria-controls="order" aria-selected="false"><i class="iconsax" data-icon="receipt-square"></i>Order</button>
            </li>
            <li>
              <button class="nav-link" id="wishlist-tab" data-bs-toggle="pill" data-bs-target="#wishlist" role="tab" aria-controls="wishlist" aria-selected="false"><i class="iconsax" data-icon="heart"></i>Wishlist</button>
            </li>
            <li>
              <button class="nav-link" id="address-tab" data-bs-toggle="pill" data-bs-target="#address" role="tab" aria-controls="address" aria-selected="false"><i class="iconsax" data-icon="cue-cards"></i>Address</button>
            </li>
          </ul>
          <div class="logout-button"><a class="btn btn_black sm" data-bs-toggle="modal" data-bs-target="#Confirmation-modal" title="Quick View" tabindex="0"><i class="iconsax me-1" data-icon="logout-1"></i> Logout</a></div>
        </div>
      </div>

      <div class="col-xl-9 col-lg-8">
        <div class="tab-content" id="v-pills-tabContent">
          {{-- Dashboard Tab --}}
          <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
            <div class="dashboard-right-box">
              <div class="my-dashboard-tab">
                <div class="sidebar-title">
                  <div class="loader-line"></div>
                  <h4>My Dashboard</h4>
                </div>
                <div class="dashboard-user-name"> 
                  <h6>Hello, <b>John Doe</b></h6>
                  <p>From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account information. Select a link below to view or edit information.</p>
                </div>
                <div class="total-box"> 
                  <div class="row gy-4"> 
                    <div class="col-xl-4"> 
                      <div class="totle-contain">
                        <div class="wallet-point"><img src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/wallet.svg" alt=""><img class="img-1" src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/wallet.svg" alt=""></div>
                        <div class="totle-detail"> 
                          <h6>Balance</h6>
                          <h4>$ 84.40</h4>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-4"> 
                      <div class="totle-contain">
                        <div class="wallet-point"><img src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/coin.svg" alt=""><img class="img-1" src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/coin.svg" alt=""></div>
                        <div class="totle-detail"> 
                          <h6>Total Points</h6>
                          <h4>500</h4>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-4"> 
                      <div class="totle-contain">
                        <div class="wallet-point"><img src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/order.svg" alt=""><img class="img-1" src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/order.svg" alt=""></div>
                        <div class="totle-detail"> 
                          <h6>Total Orders</h6>
                          <h4>12</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="profile-about"> 
                  <div class="row"> 
                    <div class="col-xl-7"> 
                      <div class="sidebar-title">
                        <div class="loader-line"></div>
                        <h5>Profile Information</h5>
                      </div>
                      <ul class="profile-information"> 
                        <li> 
                          <h6>Name :</h6>
                          <p>John Doe</p>
                        </li>
                        <li> 
                          <h6>Phone:</h6>
                          <p>+1 5551855359</p>
                        </li>
                        <li> 
                          <h6>Address:</h6>
                          <p>26, Starts Hollow Colony Denver, Colorado, United States 80014</p>
                        </li>
                      </ul>
                    </div>
                    <div class="col-xl-5">
                      <div class="profile-image d-none d-xl-block"> <img class="img-fluid" src="{{ asset('client/assets/images/other-img/dashboard.png') }}" alt=""></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          {{-- Notifications Tab --}}
          <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab"> 
            <div class="dashboard-right-box">
              <div class="notification-tab">
                <div class="sidebar-title">
                  <div class="loader-line"></div>
                  <h4>Notifications</h4>
                </div>
                <ul class="notification-body">
                  <li> 
                    <div class="user-img"> <img src="{{ asset('client/assets/images/notification/1.jpg') }}" alt=""></div>
                    <div class="user-contant"> 
                      <h6>Mint - is your budget ready for spring spending?<span>2:14PM</span></h6>
                      <p>A quick weekend trip, a staycation in your own town, or a weeklong vacay with the family—it’s your choice if it’s in the budget.</p>
                    </div>
                  </li>
                  <li> 
                    <div class="user-img"> <img src="{{ asset('client/assets/images/notification/2.jpg') }}" alt=""></div>
                    <div class="user-contant"> 
                      <h6>Flipkart - Confirmed order<span>2:14PM</span></h6>
                      <p>Thanks for signing up for CodePen! We're happy you're here. Let's get your email address verified.</p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          
          {{-- Order Tab --}}
          <div class="tab-pane fade" id="order" role="tabpanel" aria-labelledby="order-tab">
            <div class="dashboard-right-box">
              <div class="order">
                <div class="sidebar-title">
                  <div class="loader-line"></div>
                  <h4>My Orders History</h4>
                </div>
                <div class="row gy-4"> 
                  <div class="col-12">
                    <div class="order-box">
                      <div class="order-container"> 
                        <div class="order-icon"><i class="iconsax" data-icon="box"></i>
                          <div class="couplet"><i class="fa-solid fa-check"></i></div>
                        </div>
                        <div class="order-detail"> 
                          <h5>Delivered</h5>
                          <p>on Fri, 1 Mar</p>
                        </div>
                      </div>
                      <div class="product-order-detail"> 
                        <div class="product-box"> <a href="product.html"> <img src="{{ asset('client/assets/images/notification/1.jpg') }}" alt=""></a>
                          <div class="order-wrap">
                            <h5>Rustic Minidress with Halterneck</h5>
                            <p>Versatile sporty slogans short sleeve quirky laid back orange lux hoodies vests pins badges.</p>
                            <ul> 
                              <li><p>Prize :</p><span>\$20.00</span></li>
                              <li><p>Size :</p><span>M</span></li>
                              <li><p>Order Id :</p><span>ghat56han50</span></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="order-box">
                      <div class="order-container"> 
                        <div class="order-icon"><i class="iconsax" data-icon="undo"></i>
                          <div class="couplet"><i class="fa-solid fa-check"></i></div>
                        </div>
                        <div class="order-detail"> 
                          <h5>Refund Credited</h5>
                          <p>Your Refund Of <b>\$389.00</b> for the return has been processed Successfully on 4th Apr.<a href="#"> View Refund details</a></p>
                        </div>
                      </div>
                      <div class="product-order-detail"> 
                        <div class="product-box"> <a href="product.html"> <img src="{{ asset('client/assets/images/notification/9.jpg') }}" alt=""></a>
                          <div class="order-wrap">
                            <h5>Rustic Minidress with Halterneck</h5>
                            <p>Versatile sporty slogans short sleeve quirky laid back orange lux hoodies vests pins badges.</p>
                            <ul> 
                              <li><p>Prize :</p><span>\$20.00</span></li>
                              <li><p>Size :</p><span>M</span></li>
                              <li><p>Order Id :</p><span>ghat56han50</span></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          {{-- Wishlist Tab --}}
          <div class="tab-pane fade" id="wishlist" role="tabpanel" aria-labelledby="wishlist-tab">
            <div class="dashboard-right-box">
              <div class="wishlist-box ratio1_3"> 
                <div class="sidebar-title">
                  <div class="loader-line"></div>
                  <h4>Wishlist</h4>
                </div>
                <div class="row-cols-md-3 row-cols-2 grid-section view-option row gy-4 g-xl-4">
                  <div class="col"> 
                    <div class="product-box-3 product-wishlist">
                      <div class="img-wrapper">
                        <div class="label-block"><a class="label-2 wishlist-icon delete-button" href="javascript:void(0)" title="Remove from Wishlist" tabindex="0"><i class="iconsax" data-icon="trash" aria-hidden="true"></i></a></div>
                        <div class="product-image"><a class="pro-first" href="#"><img class="bg-img" src="{{ asset('client/assets/images/product/product-3/1.jpg') }}" alt="product"></a><a class="pro-sec" href="#"><img class="bg-img" src="{{ asset('client/assets/images/product/product-3/20.jpg') }}" alt="product"></a></div>
                        <div class="cart-info-icon"><a href="#" data-bs-toggle="modal" data-bs-target="#addtocart" title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true"></i></a></div>
                      </div>
                      <div class="product-detail">
                        <ul class="rating">      
                          <li><i class="fa-solid fa-star"></i></li>
                          <li><i class="fa-solid fa-star"></i></li>
                          <li><i class="fa-solid fa-star"></i></li>
                          <li><i class="fa-solid fa-star-half-stroke"></i></li>
                          <li><i class="fa-regular fa-star"></i></li>
                        </ul>
                        <a href="#"><h6>Greciilooks Women's Stylish Top</h6></a>
                        <p>\$100.00<del>\$140.00</del><span>-20%</span></p>
                      </div>
                    </div>
                  </div>
                  <div class="col"> 
                    <div class="product-box-3 product-wishlist">
                      <div class="img-wrapper">
                        <div class="label-block"><a class="label-2 wishlist-icon delete-button" href="javascript:void(0)" title="Remove from Wishlist" tabindex="0"><i class="iconsax" data-icon="trash" aria-hidden="true"></i></a></div>
                        <div class="product-image"><a class="pro-first" href="product.html"><img class="bg-img" src="{{ asset('client/assets/images/product/product-3/2.jpg') }}" alt="product"></a><a class="pro-sec" href="product.html"><img class="bg-img" src="{{ asset('client/assets/images/product/product-3/19.jpg') }}" alt="product"></a></div>
                        <div class="cart-info-icon"><a href="#" data-bs-toggle="modal" data-bs-target="#addtocart" title="Add to cart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true"></i></a></div>
                      </div>
                      <div class="product-detail">
                        <ul class="rating">      
                          <li><i class="fa-solid fa-star"></i></li>
                          <li><i class="fa-solid fa-star"></i></li>
                          <li><i class="fa-solid fa-star"></i></li>
                          <li><i class="fa-solid fa-star"></i></li>
                          <li><i class="fa-regular fa-star"></i></li>
                        </ul>
                        <a href="product.html"><h6>Wide Linen-Blend Trousers</h6></a>
                        <p>\$100.00<del>\$18.00</del></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          {{-- Address Tab --}}
          <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab"> 
            <div class="dashboard-right-box">
              <div class="address-tab">
                <div class="sidebar-title">
                  <div class="loader-line"></div>
                  <h4>My Address Details</h4>
                </div>
                <div class="row gy-3">
                  <div class="col-xxl-4 col-md-6">
                    <div class="address-option">
                      <label for="address-billing-0">
                        <span class="delivery-address-box">
                          <span class="form-check"> 
                            <input class="custom-radio" id="address-billing-0" type="radio" checked="checked" name="radio">
                          </span>
                          <span class="address-detail">
                            <span class="address"><span class="address-title">New Home</span></span>
                            <span class="address"><span class="address-home"><span class="address-tag">Address:</span>26, Starts Hollow Colony, Denver, Colorado, United States</span></span>
                            <span class="address"><span class="address-home"><span class="address-tag">Pin Code:</span>80014</span></span>
                            <span class="address"><span class="address-home"><span class="address-tag">Phone :</span>+1 5551855359</span></span>
                          </span>
                        </span>
                        <span class="buttons">
                          <a class="btn btn_black sm" href="#" data-bs-toggle="modal" data-bs-target="#edit-box" title="Edit Address" tabindex="0">Edit</a>
                          <a class="btn btn_outline sm" href="#" data-bs-toggle="modal" data-bs-target="#bank-card-modal" title="Delete Address" tabindex="0">Delete</a>
                        </span>
                      </label>
                    </div>
                  </div>
                  <div class="col-xxl-4 col-md-6">
                    <div class="address-option">
                      <label for="address-billing-3">
                        <span class="delivery-address-box">
                          <span class="form-check"> 
                            <input class="custom-radio" id="address-billing-3" type="radio" name="radio">
                          </span>
                          <span class="address-detail">
                            <span class="address"><span class="address-title">IT Office</span></span>
                            <span class="address"><span class="address-home"><span class="address-tag">Address:</span>55B, Claire Cav Street, San Jose, California, United States</span></span>
                            <span class="address"><span class="address-home"><span class="address-tag">Pin Code:</span>94088</span></span>
                            <span class="address"><span class="address-home"><span class="address-tag">Phone :</span>+1 5551855359</span></span>
                          </span>
                        </span>
                        <span class="buttons">
                          <a class="btn btn_black sm" href="#" data-bs-toggle="modal" data-bs-target="#edit-box" title="Edit Address" tabindex="0">Edit</a>
                          <a class="btn btn_outline sm" href="#" data-bs-toggle="modal" data-bs-target="#bank-card-modal" title="Delete Address" tabindex="0">Delete</a>
                        </span>
                      </label>
                    </div>
                  </div>
                </div>
                <button class="btn add-address" data-bs-toggle="modal" data-bs-target="#add-address" title="Add Address" tabindex="0">+ Add Address</button>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</section>
@endsection