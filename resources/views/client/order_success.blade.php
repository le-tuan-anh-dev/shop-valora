@extends('client.layouts.master')

@section('title', 'Velora - Order Success')

@section('content')

<!-- Flash Messages -->
@if (session('success'))
    <div class="alert alert-success position-fixed top-0 end-0 m-3" style="z-index: 9999;">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger position-fixed top-0 end-0 m-3" style="z-index: 9999;">
        {{ session('error') }}
    </div>
@endif
<section class="section-b-space py-0">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 px-0"> 
        <div class="order-box-1">
          <img src="{{ asset('client/assets/images/gif/success.gif') }}" alt="Success">
          <h4>Đơn Hàng Thành Công</h4>
          <p>Thanh toán đã được xử lý thành công và đơn hàng của bạn đang được vận chuyển</p>
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
          <h4>Thông Tin Đơn Hàng</h4>
          <p>Hóa đơn đã được gửi đến email <strong>{{ $order->customer_email }}</strong> của bạn. Vui lòng kiểm tra chi tiết đơn hàng.</p>
          
          <div class="order-summary mb-4">
            <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Trạng thái:</strong> 
              <span class="badge bg-warning">{{ ucfirst($order->status)? "Chờ xác nhận":"" }}</span>
            </p>
          </div>

          <div class="order-table"> 
            <div class="table-responsive theme-scrollbar">  
              <table class="table">
                <thead>
                  <tr> 
                    <th>Sản Phẩm</th>
                    <th>Giá</th>
                    <th>Số Lượng</th>
                    <th>Tổng Cộng</th>
                  </tr>
                </thead>
                <tbody> 
                  @forelse($orderItems as $item)
                    <tr> 
                      <td> 
                        <div class="cart-box">
                          <a href="#">
                            <img src="{{ asset('storage/' . $item->product_image) }}" alt="{{ $item->product_name }}" style="width: 60px; height: 60px; object-fit: cover;">
                          </a>
                          <div>
                            <a href="#"> 
                              <h5>{{ $item->product_name }}</h5>
                            </a>
                            @if($item->product_options)
                              <p>
                                @php
                                  $options = json_decode($item->product_options, true);
                                @endphp
                                @foreach($options as $option)
                                  <span>{{ $option['attribute'] }}: {{ $option['value'] }}</span><br>
                                @endforeach
                              </p>
                            @endif
                          </div>
                        </div>
                      </td>
                      <td>{{ number_format($item->unit_price, 0, ',', '.') }} đ</td>
                      <td>{{ $item->quantity }}</td>
                      <td>{{ number_format($item->total_price, 0, ',', '.') }} đ</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="text-center">Không có sản phẩm trong đơn hàng</td>
                    </tr>
                  @endforelse
                  
                  <tr> 
                    <td></td>
                    <td></td>
                    <td class="total fw-bold">Tạm tính:</td>
                    <td class="total fw-bold">{{ number_format($order->subtotal, 0, ',', '.') }} đ</td>
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
            <h4>Chi Tiết Đơn Hàng</h4>
          </div>
          <div class="summery-content"> 
            <ul> 
              <li> 
                <p class="fw-semibold">Tổng sản phẩm ({{ $orderItems->count() }})</p>
                <h6>{{ number_format($order->subtotal, 0, ',', '.') }} đ</h6>
              </li>
              <li> 
                <p>Giao đến</p>
                <span>{{ $order->receiver_name }}, {{ $order->shipping_address }}</span>
              </li>
            </ul>
            <ul> 
              <li> 
                <p>Phí vận chuyển</p>
                <span>{{ number_format($order->shipping_fee, 0, ',', '.') }} đ</span>
              </li>
              @if($order->promotion_amount > 0)
                <li> 
                  <p>Giảm giá</p>
                  <span style="color: #28a745;">-{{ number_format($order->promotion_amount, 0, ',', '.') }} đ</span>
                </li>
              @endif
            </ul>
            <div class="d-flex align-items-center justify-content-between border-top pt-3">
              <h6>Tổng Cộng (VND)</h6>
              <h5 style="color: #e91e63;">{{ number_format($order->total_amount, 0, ',', '.') }} đ</h5>
            </div>
            
            @if($order->note)
              <div class="note-box mt-3"> 
                <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
              </div>
            @endif
          </div>
        </div>

        <div class="summery-footer mt-3">
          <div class="sidebar-title">
            <div class="loader-line"></div>
            <h4>Địa Chỉ Giao Hàng</h4>
          </div>
          <ul> 
            <li> 
              <h6><strong>{{ $order->receiver_name }}</strong></h6>
              <h6>{{ $order->shipping_address }}</h6>
            </li>
            <li> 
              <h6>Điện thoại: <span>{{ $order->receiver_phone }}</span></h6>
            </li>
            <li> 
              <h6>Email: <span>{{ $order->receiver_email }}</span></h6>
            </li>
          </ul>
        </div>

        <div class="order-button mt-3">
          <a href="{{ route('home') }}" class="btn btn_black sm w-100 rounded">Tiếp Tục Mua Sắm</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection