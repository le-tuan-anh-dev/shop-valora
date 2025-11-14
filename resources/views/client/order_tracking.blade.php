@extends('client.layouts.master')

@section('title', 'Katie - Order Tracking')

@section('content')
<section class="section-b-space pt-0"> 
  <div class="heading-banner">
    <div class="custom-container container">
      <div class="row align-items-center">
        <div class="col-6">
          <h4>Order Tracking</h4>
        </div>
        <div class="col-6">
          <ul class="breadcrumb float-end">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="#">Order Tracking</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-b-space pt-0">
  <div class="custom-container container order-tracking">
    <div class="row g-4">
      <div class="col-12"> 
        <div class="order-table"> 
          <div class="table-responsive theme-scrollbar">  
            <table class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Order Date</th>
                  <th>Location</th>
                  <th>Employee</th>
                  <th>Delivery Date</th>
                  <th>Courier</th>
                </tr>
              </thead>
              <tbody> 
                {{-- This part should be populated with the order's main details --}}
                <tr> 
                  <td>1.</td>
                  <td>Jan 13, 2024</td>
                  <td>26, Starts Hollow Colony Denver Colorado United States 80014</td>
                  <td>JCartherin Forres</td>
                  <td>Jan 25, 2024</td>
                  <td> 
                    <a href="#">
                      <img class="img-fluid" src="{{ asset('client/assets/images/other-img/brand-logo.png') }}" alt="DHL Express">DHL Express
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="tracking-box"> 
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>Order Progress/Status</h4>
          </div>
          <div class="tracking-timeline"> 
            <h4>Timeline</h4>
          </div>
          <ul> 
            {{-- This part should be a loop for each order status update --}}
            <li> 
              <div> 
                <h6>Frd 12, Monday 2024</h6>
                <p>Order Placed</p>
              </div>
              <span>12:30pm</span>
            </li>
            <li> 
              <div> 
                <h6>Frd 16, Tuesday 2024</h6>
                <p>Order Confirmed, waiting for Payment</p>
              </div>
              <span>06:30pm</span>
            </li>
            <li> 
              <div> 
                <h6>Frd 16, Wednesday 2024</h6>
                <p>Payment Confirmed</p>
              </div>
              <span>12:30pm</span>
            </li>
            <li> 
              <div> 
                <h6>Frd 20, Friday 2024</h6>
                <p>Product Sent to Warehouse</p>
              </div>
              <span>12:30pm</span>
            </li>
            <li> 
              <div> 
                <h6>Frd 25, Saturday 2024</h6>
                <p>Product Picked by delivery man</p>
              </div>
              <span>12:30pm</span>
            </li>
            <li> 
              <div> 
                <h6>Frd 26, Sunday 2024</h6>
                <p>Delivering to Customer</p>
              </div>
              <span>12:30pm</span>
            </li>
             {{-- End of loop --}}
          </ul>
        </div>
      </div>
      <div class="col-lg-7"> 
        <div class="tracking-box"> 
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>Live tracking</h4>
          </div>
          <div class="tracking-map"> 
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d139425.02169529037!2d113.88005714479792!3d22.35893607996488!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3403fc15915a872d%3A0x98a6ff95fdd9031a!2sSunny%20Bay%20Station!5e0!3m2!1sen!2sin!4v1712578497898!5m2!1sen!2sin" width="100%" height="420"></iframe>
          </div>
        </div>
      </div>
      <div class="col-12">    
        <div class="order-table tracking-table">
          <div class="table-responsive theme-scrollbar"> 
            <table class="table">
              <thead>
                <tr> 
                  <th>No.</th>
                  <th>Product Name</th>
                  <th>Product Id</th>
                  <th>Color</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody> 
                {{-- This part should be a loop for each item in the order --}}
                <tr> 
                  <td>1.</td>
                  <td> 
                    <div class="cart-box">
                      <a href="#"><img src="{{ asset('client/assets/images/notification/5.jpg') }}" alt="Product Image"></a>
                      <div>
                        <a href="#"><h5>Pink T-shirt women</h5></a>
                        <p>Sold By: <span>Roger Group</span></p>
                        <p>Size: <span>Small</span></p>
                      </div>
                    </div>
                  </td>
                  <td>#ghtd45</td>
                  <td>pink</td>
                  <td>01</td>
                  <td>\$250.00</td>
                  <td>\$250.00</td>
                </tr>
                <tr> 
                  <td>2.</td>
                  <td> 
                    <div class="cart-box">
                      <a href="#"><img src="{{ asset('client/assets/images/notification/6.jpg') }}" alt="Product Image"></a>
                      <div>
                        <a href="#"><h5>Black Ladies Heel</h5></a>
                        <p>Sold By: <span>Roger Group</span></p>
                        <p>Size: <span>Small</span></p>
                      </div>
                    </div>
                  </td>
                  <td>#gft74</td>
                  <td>Black</td>
                  <td>03</td>
                  <td>\$30.00</td>
                  <td>\$90.00</td>
                </tr>
                <tr> 
                  <td>3.</td>
                  <td> 
                    <div class="cart-box">
                      <a href="#"><img src="{{ asset('client/assets/images/notification/7.jpg') }}" alt="Product Image"></a>
                      <div>
                        <a href="#"><h5>French Terrain Red T Shirt</h5></a>
                        <p>Sold By: <span>Roger Group</span></p>
                        <p>Size: <span>Small</span></p>
                      </div>
                    </div>
                  </td>
                  <td>#asfd42</td>
                  <td>Red</td>
                  <td>02</td>
                  <td>\$50.00</td>
                  <td>\$100.00</td>
                </tr>
                <tr> 
                  <td>4.</td>
                  <td> 
                    <div class="cart-box">
                      <a href="#"><img src="{{ asset('client/assets/images/notification/8.jpg') }}" alt="Product Image"></a>
                      <div>
                        <a href="#"><h5>Women Green t-shirt</h5></a>
                        <p>Sold By: <span>Roger Group</span></p>
                        <p>Size: <span>Small</span></p>
                      </div>
                    </div>
                  </td>
                  <td>#yto451</td>
                  <td>Green</td>
                  <td>01</td>
                  <td>\$45.00</td>
                  <td>\$45.00</td>
                </tr>
                {{-- End of loop --}}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection