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
            <li class="breadcrumb-item"><a href="">Home</a></li>
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
      <div class="col-xxl-9 col-xl-8">
        <div class="cart-table">
          <div class="table-title"> 
            <h5>Cart<span id="cartTitle">({{ $itemCount }})</span></h5>
            <button class="btn btn-outline-danger btn-sm" id="clearAllButton" {{ $itemCount == 0 ? 'disabled' : '' }}>Xóa tất cả</button>
          </div>
          <div class="table-responsive theme-scrollbar">
            @if($itemCount > 0)
              <table class="table" id="cart-table">
                <thead>
                  <tr> 
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="cart-tbody">
                  @foreach($cartItems as $item)
                    <tr data-cart-item-id="{{ $item['id'] }}"> 
                      <td> 
                        <div class="cart-box">
                          <a href="{{ route('products.detail', $item['product_id']) }}">
                            <img src="{{ asset('storage/' . $item['product']['image_main']) }}" alt="{{ $item['product']['name'] }}">
                          </a>
                          <div>
                            <a href="{{ route('products.detail', $item['product_id']) }}">
                              <h5>{{ $item['product']['name'] }}</h5>
                            </a>
                            <p>Thương hiệu: <span>{{ $item['product']['brand_id'] ?? 'Shop' }}</span></p>
                            @if($item['variant'])
                              @if(!empty($item['attribute_values']))
                                <p>Loại: 
                                  <span>
                                    @foreach($item['attribute_values'] as $attr)
                                      {{ $attr['value'] }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                  </span>
                                </p>
                              @endif
                            @endif
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="item-price" data-price="{{ $item['price'] }}">
                          @if($item['variant'] && isset($item['variant']['price']))
                            {{ number_format($item['variant']['price'], 0, ',', '.') }} đ 
                          @else
                            {{ number_format($item['product']['base_price'], 0, ',', '.') }} đ 
                          @endif
                        </span>
                      </td>
                      <td>
                        <div class="qty-box" style="display: flex; align-items: center; gap: 8px; border: 1px solid #ddd; border-radius: 6px; padding: 8px 8px; width: fit-content;">
                          <button type="button" class="qty-minus" style="background: none; border: none; cursor: pointer; font-size: 14px; color: #666; padding: 4px 8px; display: flex; align-items: center; justify-content: center; transition: color 0.3s;">
                            <i class="fa-solid fa-minus"></i>
                          </button>
                          <input type="number" class="item-quantity" value="{{ $item['quantity'] }}" min="1" max="20" data-max="{{ $item['variant'] ? $item['variant']['stock'] : $item['product']['stock'] }}" style="width: 50px; text-align: center; border: none; font-size: 14px; font-weight: 600;">
                          <button type="button" class="qty-plus" style="background: none; border: none; cursor: pointer; font-size: 14px; color: #666; padding: 4px 8px; display: flex; align-items: center; justify-content: center; transition: color 0.3s;">
                            <i class="fa-solid fa-plus"></i>
                          </button>
                        </div>
                      </td>
                      <td>
                        <span class="item-total">
                          {{ number_format($item['total'], 0, ',', '.') }} đ
                        </span>
                      </td>
                      <td>
                        <a class="deleteButton" href="javascript:void(0)">
                          <i class="iconsax" data-icon="trash"></i>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              <div class="no-data">
                <img src="{{ asset('client/assets/images/cart/1.gif') }}" alt="Empty Cart">
                <h4>You have nothing in your shopping cart!</h4>
                <p>Today is a great day to purchase the things you have been holding onto! or <a href="{{ url('/shop') }}"><span>Carry on Buying</span></a></p>
              </div>
            @endif
          </div>
        </div>
      </div>

      @if($itemCount > 0)
        <div class="col-xxl-3 col-xl-4">
          <div class="cart-items">      

            
            <div class="cart-body"> 
              <h6>Chi tiết giá  ({{ $itemCount }} sản phẩm)</h6>
              <ul> 
                <li><p>Tông tiền</p><span id="subtotal">{{ number_format($subtotal, 0, ',', '.') }} đ</span></li>
                <li><p>Phí vẫn chuyển</p><span id="shipping">{{ number_format($shipping, 0, ',', '.') }} đ</span></li>
              </ul>
            </div>
            
            <div class="cart-bottom"> 
              <h6>Thành tiền <span id="total">{{ number_format($total, 0, ',', '.') }} đ</span></h6>
              
            </div>
            
            <a class="btn btn_black w-100 rounded sm" href="{{ url('/checkout') }}">Check Out</a>
          </div>
        </div>
      @endif
    </div>
  </div>
  @if($itemCount > 0)
  <div class="col-12"> 
    <div class="cart-slider"> 
      <div class="d-flex align-items-start justify-content-between">
        <div> 
          <h6>Sản phẩm đang giảm giá</h6>
          <p>
            <img class="me-2" src="{{ asset('client/assets/images/gif/discount.gif') }}" alt="">
            Nhận thêm ưu đãi khi mua thêm sản phẩm khác
          </p>
        </div>
        <a class="btn btn_outline sm rounded" href="{{ url('/shop') }}">
          Xem tất cả
          <svg>
            <use href="https://themes.pixelstrap.net/katie/assets/svg/icon-sprite.svg#arrow"></use>
          </svg>
        </a>
      </div>
      
      <div class="swiper cart-slider-box">
        <div class="swiper-wrapper"> 
          @forelse($discountProducts ?? collect() as $product)
            <div class="swiper-slide">
              <div class="cart-box">
                <a href="{{ route('products.detail', $product['id']) }}">
                  <img src="{{ asset('storage/' . $product['image_main']) }}" alt="{{ $product['name'] }}">
                </a>
                <div>
                  <a href="{{ route('products.detail', $product['id']) }}">
                    <h5>{{ $product['name'] }}</h5>
                  </a>
                  <h6>Thương hiệu: <span>{{ $product['brand'] ?? 'Shop' }}</span></h6>
                  
                  <p>
                    <strong>{{ number_format($product['base_price'], 0, ',', '.') }} đ</strong>
                    @if($product['discount_price'])
                      <span>
                        <del>{{ number_format($product['discount_price'], 0, ',', '.') }} đ</del>
                      </span>
                      <span style="color: #e74c3c; font-weight: bold; margin-left: 8px;">
                        -{{ round((($product['base_price'] - $product['discount_price']) / $product['base_price']) * 100) }}%
                      </span>
                    @endif
                  </p>
                  
                  <a class="btn " href="{{ route('products.detail', $product['id']) }}">
                    <i class="fa-solid fa-plus me-1"></i>Thêm
                  </a>
                </div>
              </div>
            </div>
          @empty
            <div class="swiper-slide" style="text-align: center; padding: 40px;">
              <p>Không có sản phẩm giảm giá</p>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endif
</section>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const clearAllButton = document.getElementById('clearAllButton');
    const cartTable = document.getElementById('cart-tbody');
    
    // Lấy CSRF token từ meta tag hoặc form
    let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
      csrfToken = document.querySelector('input[name="_token"]')?.value;
    }
    
    if (!csrfToken) {
      console.error('CSRF Token không tìm thấy!');
      alert('Lỗi bảo mật: không tìm thấy CSRF token');
    }

    console.log('CSRF Token:', csrfToken);
    console.log('Cart Items:', @json($cartItems));

    // ===== Clear All =====
    clearAllButton?.addEventListener('click', function() {
      if (confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm?')) {
        fetch('{{ route("cart.clear") }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
          }
        })
        .then(r => r.json())
        .then(data => {
          console.log('Clear response:', data);
          if (data.success) {
            alert('Đã xóa tất cả sản phẩm');
            location.reload();
          } else {
            alert('Lỗi: ' + data.error);
          }
        })
        .catch(err => {
          console.error('Error:', err);
          alert('Lỗi khi xóa sản phẩm');
        });
      }
    });

    // ===== Delete Item =====
    console.log('Setting up delete buttons...');
    const deleteButtons = document.querySelectorAll('.deleteButton');
    console.log('Found delete buttons:', deleteButtons.length);
    
    deleteButtons.forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const row = this.closest('tr');
        const cartItemId = row.dataset.cartItemId;

        console.log('Delete button clicked!');
        console.log('Cart Item ID:', cartItemId);
        console.log('CSRF Token:', csrfToken);
        console.log('Row element:', row);

        if (confirm('Xóa sản phẩm này?')) {
          const url = `/cart/remove/${cartItemId}`;
          console.log('Fetching URL:', url);
          
          fetch(url, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Content-Type': 'application/json'
            }
          })
          .then(r => {
            console.log('Response status:', r.status);
            console.log('Response headers:', r.headers);
            return r.json();
          })
          .then(data => {
            console.log('Delete response:', data);
            if (data.success) {
              alert(data.message || 'Xóa sản phẩm thành công');
              row.remove();
              updateCartTotal();
              
              if (document.querySelectorAll('#cart-tbody tr').length === 0) {
                setTimeout(() => location.reload(), 500);
              }
            } else {
              alert('Lỗi: ' + (data.error || 'Không thể xóa sản phẩm'));
            }
          })
          .catch(err => {
            console.error('Fetch Error:', err);
            alert('Lỗi khi xóa sản phẩm: ' + err.message);
          });
        }
      });
    });

    // ===== Update Quantity =====
    document.querySelectorAll('.item-quantity').forEach(input => {
      input.addEventListener('change', function() {
        const row = this.closest('tr');
        const cartItemId = row.dataset.cartItemId;
        const quantity = parseInt(this.value) || 1;
        const maxStock = parseInt(this.dataset.max) || 20;

        console.log('Quantity changed:', {
          cartItemId: cartItemId,
          quantity: quantity,
          maxStock: maxStock
        });

        if (quantity > maxStock) {
          alert(`Chỉ còn ${maxStock} sản phẩm trong kho`);
          this.value = maxStock;
          return;
        }

        if (quantity < 1) {
          this.value = 1;
          return;
        }

        // Lấy giá từ cell price
        const priceCell = row.querySelector('.item-price');
        const priceText = priceCell.textContent.trim();
        const price = parseInt(priceText.replace(/[^\d]/g, ''));

        console.log('Price info:', {
          priceText: priceText,
          price: price
        });

        fetch(`/cart/update-quantity/${cartItemId}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({ quantity: quantity })
        })
        .then(r => r.json())
        .then(data => {
          console.log('Update quantity response:', data);
          if (data.success) {
            const formatter = new Intl.NumberFormat('vi-VN', {
              minimumFractionDigits: 0,
              maximumFractionDigits: 0
            });
            
            // Tính tổng tiền item = giá × số lượng
            const itemTotal = data.itemTotal;
            row.querySelector('.item-total').textContent = formatter.format(itemTotal) + ' đ';
            
            console.log('Updated item total to:', itemTotal);
            updateCartTotal();
          } else {
            alert(data.error);
            location.reload();
          }
        })
        .catch(err => {
          console.error('Error updating quantity:', err);
          alert('Lỗi khi cập nhật số lượng');
        });
      });
    });

    // ===== Quantity Buttons =====
    document.querySelectorAll('.qty-minus').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const input = this.closest('.qty-box').querySelector('input');
        const currentVal = parseInt(input.value) || 1;
        if (currentVal > 1) {
          input.value = currentVal - 1;
          input.dispatchEvent(new Event('change'));
        }
      });
    });

    document.querySelectorAll('.qty-plus').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const input = this.closest('.qty-box').querySelector('input');
        const currentVal = parseInt(input.value) || 1;
        const maxStock = parseInt(input.dataset.max) || 20;
        if (currentVal < maxStock && currentVal < 20) {
          input.value = currentVal + 1;
          input.dispatchEvent(new Event('change'));
        }
      });
    });

    // ===== Update Cart Total =====
    function updateCartTotal() {
      fetch('{{ route("cart.total") }}', {
        headers: {
          'X-CSRF-TOKEN': csrfToken
        }
      })
      .then(r => r.json())
      .then(data => {
        console.log('Cart total:', data);
        if (data.itemCount === 0) {
          setTimeout(() => location.reload(), 500);
        } else {
          const formatter = new Intl.NumberFormat('vi-VN', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
          });

          document.getElementById('cartTitle').textContent = `(${data.itemCount})`;
          document.getElementById('subtotal').textContent = formatter.format(data.subtotal) + ' đ';
          document.getElementById('shipping').textContent = formatter.format(data.shipping) + ' đ';
          document.getElementById('total').textContent = formatter.format(data.total) + ' đ';
        }
      });
    }

    // ===== Countdown Timer =====
    let timeLeft = 5;
    setInterval(function() {
      timeLeft--;
      if (timeLeft < 0) timeLeft = 5;
      const countdown = document.getElementById('countdown');
      if (countdown) {
        countdown.textContent = ` ${timeLeft}`;
      }
    }, 60000);
  });
</script>
@endpush
@endsection