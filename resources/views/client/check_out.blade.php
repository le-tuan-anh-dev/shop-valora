@extends('client.layouts.master')

@section('title', 'Velora - Check Out')

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
                            <input class="custom-radio shipping-address-radio" 
                              id="shipping-address-{{ $address->id }}" 
                              type="radio" 
                              @if($address->is_default == 1 || $address->is_default == true)
                                checked
                              @endif
                              name="shipping_address_id" 
                              value="{{ $address->id }}"
                              data-district-id="{{ $address->district_id }}"
                              data-ward-code="{{ $address->ward_code }}">
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
                        <div style="margin-top: 5px; font-size: 0.9rem; color: #666;">
                          <span>x<strong>{{ $item['quantity'] }}</strong></span>
                        </div>
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
                    <button type="button" id="remove-voucher-btn" class="btn btn-outline-danger mt-2 w-100" style="display:none;">
                      Hủy mã giảm giá
                    </button>
                  </div>
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
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addressModalLabel">Thêm địa chỉ mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addressForm">
          @csrf
          <div class="row">
            <div class="col-md-6">
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
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                    <label for="province" class="form-label">Tỉnh / Thành</label>
                    <select name="province_id" id="province" class="form-control" required>
                        <option value="">-- Chọn tỉnh --</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province['ProvinceID'] }}">{{ $province['ProvinceName'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="district" class="form-label">Quận / Huyện</label>
                    <select id="district" name="district_id" class="form-control" required disabled>
                        <option value="">-- Chọn quận / huyện --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="ward" class="form-label">Phường / Xã</label>
                    <select id="ward" name="ward_code" class="form-control" required disabled>
                        <option value="">-- Chọn phường / xã --</option>
                    </select>
                </div>
              </div>
              
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

  function formatCurrency(value) {
    return new Intl.NumberFormat('vi-VN', {
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    }).format(value) + ' đ';
  }

  // Hàm gọi API lấy phí vận chuyển
  function fetchShippingFee(districtId, wardCode) {
    if (!districtId || !wardCode) {
      return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch('{{ route("checkout.get-shipping-fee") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        to_district_id: parseInt(districtId),
        to_ward_code: wardCode
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const shippingFee = data.total;
        
        // Cập nhật UI
        document.getElementById('shipping').textContent = formatCurrency(shippingFee);
        document.getElementById('shipping-fee').value = shippingFee;

        // Tính lại tổng tiền
        const subtotalText = document.getElementById('subtotal').textContent;
        const subtotal = parseFloat(subtotalText.replace(/\D/g, '')) || 0;
        
        const discountText = document.getElementById('discount').textContent;
        const discount = parseFloat(discountText.replace(/\D/g, '')) || 0;
        
        const newTotal = subtotal - discount + shippingFee;
        document.getElementById('total-amount').textContent = formatCurrency(newTotal);
      } else {
        showNotification(data.message || 'Không thể tính phí vận chuyển', 'error');
      }
    })
    .catch(err => {
      console.error('Error:', err);
      showNotification('Lỗi khi lấy phí vận chuyển', 'error');
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    const addressModal = document.getElementById('address-modal');
    const addressForm = document.getElementById('addressForm');
    const saveAddressBtn = document.getElementById('saveAddressBtn');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // ========== ADDRESS SECTION ==========

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

    // ========== COUPON SECTION ==========

    function applyCoupon(code) {
      const subtotalText = document.getElementById('subtotal').textContent;
      const subtotal = parseFloat(subtotalText.replace(/\D/g, '')) || 0;

      fetch('{{ route("checkout.apply-voucher") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ 
          code: code,
          subtotal: subtotal
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const discountAmount = parseFloat(data.discount_amount) || 0;
          const shippingFeeText = document.getElementById('shipping').textContent;
          const shippingFee = parseFloat(shippingFeeText.replace(/\D/g, '')) || 30000;
          const newTotal = subtotal - discountAmount + shippingFee;

          document.getElementById('discount-row').style.display = 'flex';
          document.getElementById('discount').textContent = '- ' + formatCurrency(discountAmount);
          document.getElementById('total-amount').textContent = formatCurrency(newTotal);
          document.getElementById('promotion-id').value = data.voucher_id || '';
          
          document.getElementById('coupon-input').disabled = true;
          document.getElementById('apply-coupon').disabled = true;

          const removeBtn = document.getElementById('remove-voucher-btn');
          removeBtn.style.display = 'block';
          removeBtn.addEventListener('click', removeVoucher);

          showNotification('Mã giảm giá áp dụng thành công!', 'success');
        } else {
          showNotification(data.message || 'Mã giảm giá không hợp lệ', 'error');
        }
      })
      .catch(err => {
        showNotification('Lỗi: ' + err.message, 'error');
      });
    }

    function removeVoucher() {
      fetch('{{ route("checkout.remove-voucher") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const subtotalText = document.getElementById('subtotal').textContent;
          const subtotal = parseFloat(subtotalText.replace(/\D/g, '')) || 0;
          const shippingFeeText = document.getElementById('shipping').textContent;
          const shippingFee = parseFloat(shippingFeeText.replace(/\D/g, '')) || 30000;
          const newTotal = subtotal + shippingFee;

          document.getElementById('discount-row').style.display = 'none';
          document.getElementById('discount').textContent = '- 0 đ';
          document.getElementById('total-amount').textContent = formatCurrency(newTotal);
          
          document.getElementById('coupon-input').value = '';
          document.getElementById('coupon-input').disabled = false;
          document.getElementById('apply-coupon').disabled = false;
          
          document.getElementById('promotion-id').value = '';
          document.getElementById('remove-voucher-btn').style.display = 'none';
          showNotification('Đã hủy mã giảm giá', 'success');
        }
      })
      .catch(err => {
        console.error('Remove Error:', err);
        showNotification('Lỗi: ' + err.message, 'error');
      });
    }

    document.getElementById('apply-coupon')?.addEventListener('click', function() {
      const couponCode = document.getElementById('coupon-input').value.trim();
      
      if (!couponCode) {
        showNotification('Vui lòng nhập mã giảm giá', 'error');
        return;
      }

      applyCoupon(couponCode);
    });

    // ========== SHIPPING ADDRESS SELECTION ==========
    // KHI CHỌN ĐỊA CHỈ GIAO HÀNG - GỌI API LẤY PHÍ SHIP
    document.querySelectorAll('input[name="shipping_address_id"]').forEach(radio => {
      radio.addEventListener('change', function() {
        if (this.checked) {
          const districtId = this.getAttribute('data-district-id');
          const wardCode = this.getAttribute('data-ward-code');
          
          // Gọi API lấy phí vận chuyển
          fetchShippingFee(districtId, wardCode);
        }
      });
    });

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

  // ========== PROVINCES/DISTRICTS/WARDS SECTION ==========
  const provinceSelect = document.getElementById('province');
  const districtSelect = document.getElementById('district');
  const wardSelect = document.getElementById('ward');

  provinceSelect?.addEventListener('change', async function() {
    const provinceId = this.value;
    
    districtSelect.innerHTML = '<option value="">-- Chọn quận / huyện --</option>';
    wardSelect.innerHTML = '<option value="">-- Chọn phường / xã --</option>';
    districtSelect.disabled = true;
    wardSelect.disabled = true;

    if (!provinceId) return;

    try {
      const response = await fetch(`{{ route('checkout.get-districts') }}?province_id=${provinceId}`);
      const data = await response.json();

      if (data.success && data.data.length > 0) {
        districtSelect.innerHTML = '<option value="">-- Chọn quận / huyện --</option>';
        
        data.data.forEach(district => {
          const option = document.createElement('option');
          option.value = district.DistrictID;
          option.textContent = district.DistrictName;
          districtSelect.appendChild(option);
        });

        districtSelect.disabled = false;
      } else {
        showNotification('Không tìm thấy quận/huyện', 'error');
      }
    } catch (error) {
      console.error('Error:', error);
      showNotification('Lỗi khi lấy danh sách quận/huyện', 'error');
    }
  });

  districtSelect?.addEventListener('change', async function() {
    const districtId = this.value;
    
    wardSelect.innerHTML = '<option value="">-- Chọn phường / xã --</option>';
    wardSelect.disabled = true;

    if (!districtId) return;

    try {
      const response = await fetch(`{{ route('checkout.get-wards') }}?district_id=${districtId}`);
      const data = await response.json();

      if (data.success && data.data.length > 0) {
        wardSelect.innerHTML = '<option value="">-- Chọn phường / xã --</option>';
        
        data.data.forEach(ward => {
          const option = document.createElement('option');
          option.value = ward.WardCode;
          option.textContent = ward.WardName;
          wardSelect.appendChild(option);
        });

        wardSelect.disabled = false;
      } else {
        showNotification('Không tìm thấy phường/xã', 'error');
      }
    } catch (error) {
      console.error('Error:', error);
      showNotification('Lỗi khi lấy danh sách phường/xã', 'error');
    }
  });

  // Không gọi fetchShippingFee khi thêm địa chỉ mới
  // Chỉ gọi khi người dùng chọn địa chỉ ở Shipping Address section
</script>
@endpush

@endsection