@extends('client.layouts.master')

@section('title', 'Katie - Order Success')

@section('content')
<section class="section-b-space py-0">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 px-0"> 
        <div class="order-box-1">
          <img src="{{ asset('client/assets/images/gif/success.gif') }}" alt="Success">
          <h4>Order Success</h4>
          <p>Payment Is Successfully Processed And Your Order Is On The Way</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-b-space">
  <div class="custom-container container order-success">
    <div class="row gy-4">
      <div class="col-xl-8"> 
        <div class="order-items sticky"> 
          <h4>Order Information</h4>
          <p>Order invoice has been sent to your registered email account. Double check your order details.</p>
          <div class="order-table"> 
            <div class="table-responsive theme-scrollbar">  
              <table class="table">
                <thead>
                  <tr> 
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody> 
                  <tr> 
                    <td> 
                      <div class="cart-box">
                        <a href="#">
                          <img src="{{ asset('client/assets/images/cart/category/1.jpg') }}" alt="Product Image">
                        </a>
                        <div>
                          <a href="#"> 
                            <h5>Concrete Jungle Pack</h5>
                          </a>
                          <p>Sold By: <span>Roger Group</span></p>
                          <p>Size: <span>Small</span></p>
                        </div>
                      </div>
                    </td>
                    <td>\$20.00</td>
                    <td>01</td>
                    <td>\$195.00</td>
                  </tr>
                  <tr> 
                    <td> 
                      <div class="cart-box">
                        <a href="#">
                          <img src="{{ asset('client/assets/images/cart/category/2.jpg') }}" alt="Product Image">
                        </a>
                        <div>
                          <a href="#"> 
                            <h5>Mini dress with ruffled straps</h5>
                          </a>
                          <p>Sold By: <span>luisa Shop</span></p>
                          <p>Size: <span>Medium</span></p>
                        </div>
                      </div>
                    </td>
                    <td>\$20.00</td>
                    <td>02</td>
                    <td>\$150.00</td>
                  </tr>
                  <tr> 
                    <td> 
                      <div class="cart-box">
                        <a href="#">
                          <img src="{{ asset('client/assets/images/cart/category/3.jpg') }}" alt="Product Image">
                        </a>
                        <div>
                          <a href="#"> 
                            <h5>Long Sleeve Asymmetric</h5>
                          </a>
                          <p>Sold By: <span>Brown Shop</span></p>
                          <p>Size: <span>Large</span></p>
                        </div>
                      </div>
                    </td>
                    <td>\$25.00</td>
                    <td>05</td>
                    <td>\$120.00</td>
                  </tr>
                  <tr> 
                    <td></td>
                    <td></td>
                    <td class="total fw-bold">Total :</td>
                    <td class="total fw-bold">\$465.00</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="summery-box">
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>Order Details</h4>
          </div>
          <div class="summery-content"> 
            <ul> 
              <li> 
                <p class="fw-semibold">Product total (3)</p>
                <h6>\$230.00</h6>
              </li>
              <li> 
                <p>Shipping to</p>
                <span>United Kingdom</span>
              </li>
            </ul>
            <ul> 
              <li> 
                <p>Shipping Costs</p>
                <span>\$0.00</span>
              </li>
              <li> 
                <p>Total without VAT</p>
                <span>\$250.00</span>
              </li>
              <li> 
                <p>Including 10% VAT</p>
                <span>\$25.00</span>
              </li>
              <li> 
                <p>Discount Code</p>
                <span>$-10.00</span>
              </li>
            </ul>
            <div class="d-flex align-items-center justify-content-between">
              <h6>Total (USD)</h6>
              <h5>\$265.00</h5>
            </div>
            <div class="note-box"> 
              <p>I'm hoping the store can work with me to get it delivered as soon as possible because I really need it to gift to my friend for her party next week. Many thanks for it.</p>
            </div>
          </div>
        </div>
        <div class="summery-footer"> 
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>Shipping Address</h4>
          </div>
          <ul> 
            <li> 
              <h6>8424 James Lane South</h6>
              <h6>San Francisco, CA 94080</h6>
            </li>
            <li> 
              <h6>Expected Date Of Delivery: <span>Track Order</span></h6>
            </li>
            <li> 
              <h5>Oct 21, 2021</h5>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection