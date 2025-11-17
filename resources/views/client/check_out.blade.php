@extends('client.layouts.master')

@section('title', 'Katie - Check Out')

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
    @if($cartItems->count() > 0)
      <form action="{{ route('place-order') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row">
          <div class="col-xxl-9 col-lg-8"> 
            <div class="left-sidebar-checkout sticky">
              
              {{-- Shipping Address --}}
              <div class="address-option">
                <div class="address-title"> 
                  <h4>Địa chỉ giao hàng</h4>
                  <a href="#" data-bs-toggle="modal" data-bs-target="#address-modal" title="Thêm địa chỉ">+ Thêm địa chỉ</a>
                </div>
                <div class="row">
                  @forelse($shippingAddresses as $address)
                    <div class="col-xxl-4 col-md-6">
                      <label for="shipping-address-{{ $address->id }}">
                        <div class="delivery-address-box">
                          <div class="form-check"> 
                            <input class="custom-radio" id="shipping-address-{{ $address->id }}" type="radio" 
                              @if($address->is_default == 1 || $address->is_default == true)
                                checked
                              @endif
                              name="shipping_address_id" value="{{ $address->id }}" required>
                          </div>
                          <div class="address-detail">
                            <span class="address"><span class="address-title">{{ $address->name }}</span></span>
                            <span class="address"><span class="address-home"><span class="address-tag">Địa chỉ:</span> {{ $address->address }}</span></span>
                            <span class="address"><span class="address-home"><span class="address-tag">Điện thoại:</span> {{ $address->phone }}</span></span>
                          </div>
                        </div>
                      </label>
                    </div>
                  @empty
                    <div class="col-12">
                      <p class="text-danger">Vui lòng thêm địa chỉ giao hàng</p>
                    </div>
                  @endforelse
                </div>
              </div>

              {{-- Payment Options from Database --}}
              <div class="payment-options">
                <h4 class="mb-3">Phương thức thanh toán</h4>
                <div class="row gy-3">
                  @if($paymentMethods && count($paymentMethods) > 0)
                    @foreach($paymentMethods as $method)
                      <div class="col-sm-6"> 
                        <div class="payment-box">
                          <input class="custom-radio me-2" 
                            id="payment-{{ $method->id }}" 
                            type="radio" 
                            @if($method->is_default == 1 || $method->is_default == true)
                              checked
                            @endif
                            name="payment_method_id" 
                            value="{{ $method->id }}" 
                            required>
                          <label for="payment-{{ $method->id }}">{{ $method->name }}</label>
                        </div>
                      </div>
                    @endforeach
                  @else
                    <div class="col-12">
                      <p class="text-danger">Không có phương thức thanh toán khả dụng</p>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>

          {{-- Right Sidebar --}}
          <div class="col-xxl-3 col-lg-4">
            <div class="right-sidebar-checkout">
              <h4>Tóm tắt đơn hàng</h4>
              <div class="cart-listing">
                <ul>
                  @foreach($cartItems as $item)
                    <li>
                      <img src="{{ asset('storage/' . $item['product']['image_main']) }}" alt="{{ $item['product']['name'] }}" style="width: 60px; height: 60px; object-fit: cover;">
                      <div> 
                        <h6>{{ $item['product']['name'] }}</h6>
                        <span>
                          @if(!empty($item['attribute_values']))
                            {{ implode(', ', array_column($item['attribute_values'], 'value')) }}
                          @endif
                        </span>
                      </div>
                      <p>{{ number_format($item['total'], 0, ',', '.') }} đ</p>
                    </li>
                  @endforeach
                </ul>
                <div class="summary-total"> 
                  <ul> 
                    <li><p>Tạm tính</p><span id="subtotal">{{ number_format($subtotal, 0, ',', '.') }} đ</span></li>
                    <li id="discount-row" style="display: none;"><p>Giảm giá</p><span id="discount">- 0 đ</span></li>
                    <li><p>Phí vận chuyển</p><span id="shipping">{{ number_format($shipping, 0, ',', '.') }} đ</span></li>
                  </ul>
                  <div class="coupon-code"> 
                    <input type="text" id="coupon-input" name="coupon_code" placeholder="Nhập mã giảm giá">
                    <button class="btn" type="button" id="apply-coupon">Áp dụng</button>
                  </div>
                  
                  {{-- Available Vouchers List --}}
                  @if($availableVouchers && count($availableVouchers) > 0)
                    <div class="mt-3">
                      <p class="text-muted mb-2"><small><strong>Các voucher có sẵn:</strong></small></p>
                      @foreach($availableVouchers as $voucher)
                        <div class="voucher-item p-2 mb-2 border rounded" style="cursor: pointer; background: #f9f9f9; transition: all 0.3s;">
                          <div class="d-flex justify-content-between align-items-start">
                            <div style="flex: 1;">
                              <strong style="color: #0066cc;">{{ $voucher['code'] }}</strong>
                              <br>
                              <small class="text-success">
                                @if($voucher['type'] === 'percentage')
                                  Giảm {{ $voucher['value'] }}% ({{ number_format($voucher['discount'], 0, ',', '.') }} đ)
                                @else
                                  Giảm {{ number_format($voucher['value'], 0, ',', '.') }} đ
                                @endif
                              </small>
                              @if($voucher['is_for_user'])
                                <br>
                                <span class="badge bg-info" style="font-size: 0.7rem;">Dành riêng cho bạn</span>
                              @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary apply-voucher ms-2" 
                              data-code="{{ $voucher['code'] }}" data-id="{{ $voucher['id'] }}" style="white-space: nowrap;">
                              Chọn
                            </button>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  @endif
                </div>
                <div class="total">
                  <h6>Tổng cộng :</h6>
                  <h6 id="total-amount">{{ number_format($total, 0, ',', '.') }} đ</h6>
                </div>
                <div class="order-button">
                  <button type="submit" class="btn btn_black sm w-100 rounded" id="placeOrderBtn">Đặt hàng</button>
                </div>

                <!-- Hidden inputs for form data -->
                <input type="hidden" name="promotion_id" id="promotion-id" value="">
                <input type="hidden" name="shipping_fee" id="shipping-fee" value="30000">
              </div>
            </div>
          </div>
        </div>
      </form>
    @else
      <div class="alert alert-info" role="alert">
        <h4 class="alert-heading">Giỏ hàng trống!</h4>
        <p>Vui lòng thêm sản phẩm vào giỏ hàng trước khi tiến hành thanh toán.</p>
        <hr>
        <a href="{{ route('home') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
      </div>
    @endif
  </div>
</section>

<!-- Modal thêm địa chỉ -->
<div class="modal fade" id="address-modal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addressModalLabel">Thêm địa chỉ mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addressForm">
          @csrf

          <div class="mb-3">
            <label for="addressName" class="form-label">Tên địa chỉ (VD: Nhà riêng, Văn phòng)</label>
            <input type="text" class="form-control" id="addressName" name="name" placeholder="VD: Nhà riêng" required>
          </div>

          <div class="mb-3">
            <label for="addressPhone" class="form-label">Số điện thoại</label>
            <input type="tel" class="form-control" id="addressPhone" name="phone" placeholder="Nhập số điện thoại" required>
          </div>

          <div class="mb-3">
            <label for="addressText" class="form-label">Địa chỉ</label>
            <textarea class="form-control" id="addressText" name="address" rows="3" placeholder="Nhập địa chỉ đầy đủ" required></textarea>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary" id="saveAddressBtn">Lưu địa chỉ</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function showNotification(message, type = 'success') {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertClass} position-fixed top-0 end-0 m-3`;
    alertDiv.style.zIndex = '9999';
    alertDiv.textContent = message;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
      alertDiv.remove();
    }, 4000);
  }

  document.addEventListener('DOMContentLoaded', function() {
    const addressModal = document.getElementById('address-modal');
    const addressForm = document.getElementById('addressForm');
    const saveAddressBtn = document.getElementById('saveAddressBtn');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Reset form khi mở modal mới
    addressModal?.addEventListener('show.bs.modal', function(e) {
      addressForm.reset();
      document.getElementById('addressModalLabel').textContent = 'Thêm địa chỉ mới';
    });

    // Save address
    saveAddressBtn?.addEventListener('click', async function() {
      if (!addressForm.checkValidity()) {
        addressForm.reportValidity();
        return;
      }

      const formData = new FormData(addressForm);
      
      const url = '{{ route("checkout.store-address") }}';

      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: formData
        });

        const data = await response.json();

        if (data.success) {
          showNotification(data.message, 'success');
          const modal = bootstrap.Modal.getInstance(addressModal);
          modal.hide();
          
          setTimeout(() => location.reload(), 1000);
        } else {
          showNotification(data.message, 'error');
        }
      } catch (error) {
        console.error('Error:', error);
        showNotification('Lỗi khi lưu địa chỉ', 'error');
      }
    });

    // Apply Coupon - Manual input
    document.getElementById('apply-coupon')?.addEventListener('click', function() {
      const couponCode = document.getElementById('coupon-input').value.trim();
      
      if (!couponCode) {
        showNotification('Vui lòng nhập mã giảm giá', 'error');
        return;
      }

      applyCoupon(couponCode);
    });

    // Apply voucher from list
    document.querySelectorAll('.apply-voucher').forEach(btn => {
      btn.addEventListener('click', function() {
        const code = this.dataset.code;
        const id = this.dataset.id;
        document.getElementById('coupon-input').value = code;
        document.getElementById('promotion-id').value = id;
        applyCoupon(code);
      });
    });

    function applyCoupon(code) {
      fetch('{{ route("apply-coupon") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ coupon_code: code })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          showNotification('Mã giảm giá áp dụng thành công!', 'success');
          
          const formatter = new Intl.NumberFormat('vi-VN', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
          });

          document.getElementById('subtotal').textContent = formatter.format(data.subtotal) + ' đ';
          document.getElementById('discount').textContent = '- ' + formatter.format(data.discount) + ' đ';
          document.getElementById('discount-row').style.display = 'flex';
          document.getElementById('shipping').textContent = formatter.format(data.shipping) + ' đ';
          document.getElementById('total-amount').textContent = formatter.format(data.total) + ' đ';
          
          // Lưu promotion_id vào hidden input
          document.getElementById('promotion-id').value = data.voucher_id || '';
          
          // Disable coupon input after successful apply
          document.getElementById('coupon-input').disabled = true;
          document.getElementById('apply-coupon').disabled = true;
          document.querySelectorAll('.apply-voucher').forEach(btn => btn.disabled = true);
        } else {
          showNotification(data.message || 'Mã giảm giá không hợp lệ', 'error');
        }
      })
      .catch(err => {
        console.error('Error:', err);
        showNotification('Lỗi khi áp dụng mã giảm giá', 'error');
      });
    }

    // Handle form submission
    document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
      e.preventDefault();

      const shippingAddressId = document.querySelector('input[name="shipping_address_id"]:checked')?.value;
      const paymentMethodId = document.querySelector('input[name="payment_method_id"]:checked')?.value;

      if (!shippingAddressId) {
        showNotification('Vui lòng chọn địa chỉ giao hàng', 'error');
        return;
      }

      if (!paymentMethodId) {
        showNotification('Vui lòng chọn phương thức thanh toán', 'error');
        return;
      }

      this.submit();
    });
  });
</script>
@endpush

@endsection