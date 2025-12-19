@extends('client.layouts.master')

@section('title', ' Online Fashion Store')

@section('content')
@if (session('success'))
    <div class="alert alert-success position-fixed top-0 end-0 m-3">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger position-fixed top-0 end-0 m-3">
        {{ session('error') }}
    </div>
@endif

<!-- Product Detail Section -->
<section class="section-b-space pt-0 product-thumbnail-page"> 
    <div class="custom-container container">
        <div class="row gy-4">

            <!-- LEFT: Product Images -->
            <div class="col-lg-6"> 
                <div class="row sticky">
                    <!-- Thumbnails on LEFT -->
                    <div class="col-sm-2 col-3">
                        <div class="swiper product-slider product-slider-img"> 
                            <div class="swiper-wrapper"> 
                                @foreach($images as $index => $image)
                                    <div class="swiper-slide"> 
                                        <img src="{{ $image }}" 
                                            alt="Product {{ $index }}" 
                                            class="thumbnail-img"
                                            data-index="{{ $index }}"
                                            onclick="changeImage('{{ $image }}', {{ $index }})"
                                           >
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Main Image on RIGHT -->
                    <div class="col-sm-10 col-9">
                        <div class="swiper product-slider-thumb product-slider-img-1">
                            <div class="swiper-wrapper ratio_square-2">
                                @foreach($images as $index => $image)
                                    <div class="swiper-slide"> 
                                        <img class="bg-img main-image" 
                                            id="mainImage{{ $index }}"
                                            src="{{ $image }}" 
                                            alt="Product {{ $index }}"
                                            >
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Product Details -->
            <div class="col-lg-6">
                <div class="product-detail-box"> 
                    <div class="product-option"> 

                        <!-- Product Name -->
                        <h3>{{ $product->name }}</h3>

                        <!-- Price -->
                        <p>
                            
                            @if($product->discount_price)
                                <strong>{{ number_format($product->discount_price, 0, ',', '.') }} đ</strong>
                                <del>{{ number_format($product->base_price, 0, ',', '.') }} đ</del>
                                <span class="offer-btn">
                                    - {{ round((($product->base_price - $product->discount_price) / $product->base_price) * 100) }}% 
                                </span>
                            @else
                            <strong>{{ number_format($product->base_price, 0, ',', '.') }} đ</strong>
                            @endif
                        </p>
                        <ul class="rating">      
                            @for($i = 1; $i <= 5; $i++)
                            <li><i class="fa-solid fa-star {{ $i <= round($averageRating) ? 'text-warning' : 'text-muted' }}"></i></li>
                        @endfor
                        </ul>

                        <h6>
                            {{ $product->description }}
                        </h6>

                        {{-- <!-- Quick Links -->
                        <div class="buy-box border-buttom">
                            <ul> 
                                <li>
                                    <span data-bs-toggle="modal" data-bs-target="#size-chart" title="Quick View">
                                        <i class="iconsax me-2" data-icon="ruler"></i>Bảng Size
                                    </span>
                                </li>
                                <li>
                                    <span data-bs-toggle="modal" data-bs-target="#terms-conditions-modal" title="Quick View">
                                        <i class="iconsax me-2" data-icon="truck"></i>Giao hàng và hoàn hàng
                                    </span>
                                </li>
                            </ul>
                        </div> --}}

                        <!-- Attributes Selection + Cart Form -->
                        <form id="variantForm" action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="variant_id" id="variantId" value="">
                            <input type="hidden" name="attribute_value_ids" id="attributeValueIds">

                            <!-- Nếu có attributes, hiển thị -->
                            @if(count($attributes) > 0)
                                @foreach($attributes as $attribute)
                                    <div class="d-flex mb-4">
                                        <div style="flex: 1;"> 
                                            <h5>{{ $attribute['name'] }}:</h5>
                                            <div class="size-box">
                                                <ul class="selected" id="attr_{{ $attribute['id'] }}">
                                                    @foreach($attribute['values'] as $value)
                                                        <li>
                                                            <a href="#" 
                                                               class="attribute-btn"
                                                               data-attr-id="{{ $attribute['id'] }}"
                                                               data-attr-name="{{ $attribute['name'] }}"
                                                               data-value-id="{{ $value['id'] }}"
                                                               data-value="{{ $value['value'] }}"
                                                               onclick="selectAttribute(event)">
                                                                {{ $value['value'] }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <!-- Quantity & Add to Cart -->
                            <div class="quantity-box d-flex align-items-center gap-3" style="margin: 20px 0;">
                                <div class="quantity">
                                    <button class="" type="button" id="decreaseBtn">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="20" readonly>
                                    <button class="" type="button" id="increaseBtn">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                                <div class="d-flex align-items-center gap-3 w-100">   
                                    <button type="submit" 
                                            id="addToCartBtn"
                                            class="btn btn_black sm" 
                                            {{ count($attributes) > 0 ? 'disabled' : '' }}>
                                        Thêm vào giỏ hàng
                                    </button>
                                    <button type="button" 
                                            id="buyNowBtn"
                                            class="btn btn_outline sm" 
                                            {{ count($attributes) > 0 ? 'disabled' : '' }}>
                                        Mua ngay
                                    </button>
                                </div>
                            </div>

                            <!-- Info Box -->
                            <div class="dz-info"> 
                                <ul> 
                                    <li>
                                        <div class="d-flex align-items-center gap-2"> 
                                            <h6>Mã sản phẩm:</h6>
                                            <span id="skuDisplay">{{ $product->id }}</span>
                                        </div>
                                    </li>
                                    <li> 
                                        <div class="d-flex align-items-center gap-2"> 
                                            <h6>Số lượng còn:</h6>
                                            <span id="stockDisplay">{{ $product->stock }}</span>
                                        </div>
                                    </li>
                                    <li> 
                                        <div class="d-flex align-items-center gap-2"> 
                                            <h6>Thẻ:</h6>
                                            <p>{{ $product->name }}</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex align-items-center gap-2"> 
                                            <h6>Thương hiệu:</h6>
                                            <p>{{  $brand->name ??'' }}</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </form>

                        <!-- Wishlist & Compare (tách riêng, không lồng trong form) -->
                        <div class="buy-box">
                            <ul> 
                                {{-- Wishlist cho sản phẩm chính --}}
                                @auth
                                    <li>
                                        <form action="{{ route('wishlist.add', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-link p-0 text-start"
                                                    style="text-decoration: none;">
                                                <i class="fa-regular fa-heart me-2"></i>
                                                Thêm vào sản phẩm yêu thích
                                            </button>
                                        </form>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('login') }}">
                                            <i class="fa-regular fa-heart me-2"></i>
                                            Đăng nhập để thêm vào sản phẩm yêu thích
                                        </a>
                                    </li>
                                @endauth

                                
                                <li>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#social-box">
                                        <i class="fa-solid fa-share-nodes me-2"></i>
                                        Chia sẻ
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </div> {{-- .product-option --}}
                </div>
            </div>
        </div>
    </div>

</form>
        </div>
    </div>
</div>
</section>

<section class="section-b-space pt-0 product-thumbnail-page"> 

    <div class="product-section-box x-small-section pt-0"> 
        <div class="custom-container container">
            <div class="row"> 
                <div class="col-12"> 
                    <h4 class="fw-semibold mb-4" style="color: #rgb(217 184 145);">Dánh giá sản phẩm</h4>
                    <div class="product-content"> 
                        
                        <div id="Reviews-tab-pane" role="tabpanel"> 
                            <div class="row gy-4">
                                <div class="col-lg-4">
                                    <div class="review-right">
        <div class="customer-rating">
            <div class="global-rating">
                <div>
                    <h5>{{ $averageRating }}</h5>
                </div>
                <div>
                    
                    <ul class="rating p-0 mb">
                        {{-- Hiển thị sao trung bình --}}
                        @for($i = 1; $i <= 5; $i++)
                            <li><i class="fa-solid fa-star {{ $i <= round($averageRating) ? 'text-warning' : 'text-muted' }}"></i></li>
                        @endfor
                    </ul>
                </div>
            </div>

            <ul class="rating-progess">
                @foreach($ratingPercentages as $star => $data)
                <li>
                    <p>{{ str_replace('star', ' Sao', $star) }}</p>
                    <div class="progress" role="progressbar">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             style="width: {{ $data['percentage'] }}%"></div>
                    </div>
                    <p>{{ $data['percentage'] }}%</p>
                </li>
                @endforeach
            </ul>

           
            
        </div>
    </div>
</div>
                                <div class="col-lg-8">
    <div class="comments-box">
        <h5>Đánh giá từ khách hàng ({{ $totalReviews }})</h5>
        <ul class="theme-scrollbar">
            
            @forelse($reviews as $review)
    <li>
        <div class="comment-items">
            {{-- === 1. HIỂN THỊ ẢNH USER (AVATAR) === --}}
            <div class="user-img">
                @php
                    // Thiết lập URL mặc định nếu không có ảnh
                    $avatarUrl = asset('client/assets/images/user/1.jpg'); 

                    if (!empty($review->user->image)) {
                        // Kiểm tra nếu ảnh user có tồn tại trong storage
                        if (Storage::disk('public')->exists($review->user->image)) {
                            $avatarUrl = Storage::url($review->user->image);
                        } 
                        // Bạn có thể thêm logic nếu ảnh nằm ở thư mục public/uploads/users
                        // else {
                        //     $avatarUrl = asset($review->user->image);
                        // }
                    }
                @endphp
                <img src="{{ $avatarUrl }}" alt="{{ $review->user->name ?? 'User' }} Avatar"> 
            </div>
            
            <div class="user-content">
                <div class="user-info">
                    <div class="d-flex justify-content-between gap-3 w-100 align-items-center">
                        {{-- Tên người dùng --}}
                        <h6><i class="iconsax" data-icon="user-1"></i> {{ $review->user->name ?? 'Người dùng ẩn danh' }}</h6>
                        
                       
                    
                    {{-- Thời gian đánh giá --}}
                    <span><i class="iconsax" data-icon="clock"></i> {{ $review->created_at->format('d/m/Y') }}</span>

                    {{-- Hiển thị số sao --}}
                    <ul class="rating p-0 mb">
                        @for($i = 1; $i <= 5; $i++)
                            <li><i class="fa-solid fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i></li>
                        @endfor
                    </ul>
                </div>

                {{-- === HIỂN THỊ BIẾN THỂ (SIZE/MÀU) === --}}
                @if($review->productVariant)
                    <div class="variant-info mt-1" style="font-size: 0.85rem; color: #777; background: #f9f9f9; padding: 5px; border-radius: 4px; display: inline-block;">
                        <strong>Phân loại:</strong>
                        @foreach($review->productVariant->attributeValues as $attrValue)
                            <span>{{ $attrValue->attribute->name }}: {{ $attrValue->value }}</span>
                            @if(!$loop->last) | @endif
                        @endforeach
                    </div>
                @endif

                @if(trim($review->content)) 
    <p class="mt-2">{{ $review->content }}</p>
@endif

               
                
                {{-- === PHẢN HỒI TỪ ADMIN/SHOP (LỒNG VÀO) === --}}
                @if($review->replies->count() > 0)
                    @foreach($review->replies as $reply)
                        <div class="admin-reply mt-3 p-3 border-start border-3 border-info bg-light ms-3">
                            <div class="d-flex align-items-center mb-2">
                                {{-- Avatar của người phản hồi (Admin/Shop) --}}
                                @php
                                    $replyAvatarUrl = $reply->user->image ? Storage::url($reply->user->image) : asset('client/assets/images/user/admin-icon.png');
                                @endphp
                                <img src="{{ $replyAvatarUrl }}" 
                                     alt="Admin Avatar" 
                                     style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%; margin-right: 10px;">
                                
                                {{-- Tên người phản hồi (Admin/Shop) --}}
                                <h6 class="mb-0 text-info">
                                    {{ $reply->user->name ?? 'Shop' }} 
                                    @if($reply->user->role == 'admin') 
                                        <span class="badge bg-primary ms-1">Admin</span>
                                    @endif
                                </h6>
                            </div>
                            <p class="mb-0" style="font-size: 0.9rem; color: #333;">{{ $reply->content }}</p>
                            <span class="text-muted" style="font-size: 0.75rem;">Phản hồi vào: {{ $reply->created_at->format('H:i d/m/Y') }}</span>
                        </div>
                    @endforeach
                @endif
                {{-- END: PHẢN HỒI TỪ ADMIN/SHOP --}}

            </div>
        </div>
    </li>
@empty
    <li>
        <p>Chưa có đánh giá nào cho sản phẩm này.</p>
    </li>
@endforelse

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
</section>

<!-- sản phẩm liên quan (danh mục) -->
<section class="section-b-space pt-0">
    <div class="custom-container container product-contain">
        <div class="title text-start"> 
            <h3>Sản phẩm liên quan</h3>
            <svg>
                <use href="{{ asset('client/assets/svg/icon-sprite.svg#main-line') }}"></use>
            </svg>
        </div>
        <div class="swiper special-offer-slide-2">
            <div class="swiper-wrapper trending-products ratio_square">
                
                @forelse($relatedProducts as $relatedProduct)
                    <div class="swiper-slide product-box">
                        <div class="img-wrapper">
                            <!-- Product Image -->
                            <div class="product-image">
                                <a href="{{ route('products.detail', $relatedProduct['id']) }}">
                                    <img class="bg-img" 
                                         src="{{ $relatedProduct['image_main']}}" 
                                         alt="{{ $relatedProduct['name'] }}">
                                </a>
                            </div>

                            <!-- Icons -->
                            <div class="cart-info-icon">
                                @auth
                                    <form action="{{ route('wishlist.add', $relatedProduct['id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="wishlist-icon"
                                                style="background: none; border: none; padding: 0;"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="Thêm vào yêu thích">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </form>
                                @else
                                    <a class="wishlist-icon" href="{{ route('login') }}" data-bs-toggle="tooltip"
                                       data-bs-title="Đăng nhập để thêm vào yêu thích">
                                        <i class="far fa-heart"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>

                        <!-- Product Detail -->
                        <div class="product-detail">
                            <div class="add-button">
                                <a href="{{ route('products.detail', $relatedProduct['id']) }}"
                                    title="Xem chi tiết" tabindex="0">
                                    <i class="fa-solid fa-eye"></i> Xem chi tiết
                                </a>
                            </div>

                            <a href="{{ route('products.detail', $relatedProduct['id']) }}">
                                <h5>{{ Str::limit($relatedProduct['name'], 40) }}</h5>
                            </a>

                            <!-- Price -->
                            <p>
                                @if($relatedProduct['discount_price'])
                                    {{ number_format($relatedProduct['discount_price'], 0, ',', '.') }}₫ 
                                    <del>{{ number_format($relatedProduct['base_price'], 0, ',', '.') }}₫</del>
                                @else
                                    {{ number_format($relatedProduct['base_price'], 0, ',', '.') }}₫
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <p class="text-center"></p>
                    </div>
                @endforelse
                
            </div>

        </div>
    </div>
</section>

<script>
    let selectedAttributes = {};
    let allVariants = @json($variants);
    const hasAttributes = @json(count($attributes) > 0);

    document.addEventListener('DOMContentLoaded', function() {
        const increaseBtn = document.getElementById('increaseBtn');
        const decreaseBtn = document.getElementById('decreaseBtn');
        const qtyInput = document.getElementById('quantity');
        const addToCartBtn = document.getElementById('addToCartBtn');
        const buyNowBtn = document.getElementById('buyNowBtn');
        const variantForm = document.getElementById('variantForm');
        
        // Init Swiper cho main image
        const mainImageSwiper = new Swiper('.product-slider-thumb', {
            loop: false,
            spaceBetween: 10,
            slidesPerView: 1,
        });

        
        

        // Increase quantity
        increaseBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            const currentVal = parseInt(qtyInput.value) || 1;
            const newVal = Math.min(currentVal + 1, 20);
            qtyInput.value = newVal;
        });

        // Decrease quantity
        decreaseBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            const currentVal = parseInt(qtyInput.value) || 1;
            const newVal = Math.max(currentVal - 1, 1);
            qtyInput.value = newVal;
        });

        // Nếu không có biến thể, enable button ngay
        if (!hasAttributes) {
            addToCartBtn.disabled = false;
            buyNowBtn.disabled = false;
        }

        // Xử lý nút "Mua ngay"
        buyNowBtn?.addEventListener('click', function(e) {
            e.preventDefault();

            // Kiểm tra xem đã chọn đủ attributes chưa (nếu có attributes)
            if (hasAttributes) {
                const totalAttributes = @json(count($attributes));
                const selectedCount = Object.keys(selectedAttributes).length;

                if (selectedCount < totalAttributes) {
                    alert('Vui lòng chọn đủ phân loại sản phẩm');
                    return;
                }
            }

            // Kiểm tra variant_id nếu có attributes
            const variantId = document.getElementById('variantId').value;
            if (hasAttributes && !variantId) {
                alert('Vui lòng chọn phân loại sản phẩm');
                return;
            }

            // Thêm hidden input để xác định là "mua ngay"
            const buyNowInput = document.createElement('input');
            buyNowInput.type = 'hidden';
            buyNowInput.name = 'buy_now';
            buyNowInput.value = '1';
            variantForm.appendChild(buyNowInput);

            // Submit form
            variantForm.submit();
        });
    });

    // Change main image - FIX VERSION
    function changeImage(src, index) {
        // Tìm swiper instance của main image
        const mainImageSwiper = document.querySelector('.product-slider-thumb').swiper;
        
        if (mainImageSwiper) {
            // Slide to the selected index
            mainImageSwiper.slideTo(index);
        } else {
            // Fallback nếu swiper chưa được init
            document.querySelectorAll('.product-slider-img-1 .swiper-slide').forEach((slide, i) => {
                if (i === index) {
                    slide.style.display = 'block';
                } else {
                    slide.style.display = 'none';
                }
            });
        }
        
        
        
    }

    // Select attribute
    function selectAttribute(e) {
        e.preventDefault();
        const btn = e.target;
        const attrId = btn.dataset.attrId;
        const attrName = btn.dataset.attrName;
        const valueId = btn.dataset.valueId;
        const value = btn.dataset.value;

        selectedAttributes[attrId] = { 
            attrName,
            valueId, 
            value 
        };

        document.querySelectorAll(`[data-attr-id="${attrId}"]`).forEach(b => {
            b.parentElement.classList.remove('active');
        });
        btn.parentElement.classList.add('active');

        const totalAttributes = @json(count($attributes));
        const selectedCount = Object.keys(selectedAttributes).length;

        if (selectedCount === totalAttributes) {
            getVariant();
        } else {
            getAvailableAttributes();
        }
    }

    // Get available attributes
    function getAvailableAttributes() {
        const selectedIds = Object.values(selectedAttributes).map(a => a.valueId);
        const productId = {{ $product->id }};
        
        fetch(`/products/${productId}/get-available-attributes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ 
                selected_attribute_value_ids: selectedIds 
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                console.log('Available attributes:', data.data);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Get exact variant - UPDATED WITH PRICE
    function getVariant() {
        const selectedIds = Object.values(selectedAttributes).map(a => a.valueId);
        const productId = {{ $product->id }};
        
        fetch(`/products/${productId}/get-variant`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ 
                attribute_value_ids: selectedIds 
            })
        })
        .then(r => r.json())
        .then(data => {
            
            if (data.success) {
                const variant = data.data.variant;
                const stock = data.data.stock_info;
                
               
                
                document.getElementById('variantId').value = variant.id;
                
                document.querySelectorAll('input[name="attribute_value_ids[]"]').forEach(el => el.remove());
                selectedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'attribute_value_ids[]';
                    input.value = id;
                    document.getElementById('variantForm').appendChild(input);
                });
                
                // CẬP NHẬT GIÁ TIỀN
                updatePrice(variant);
                
                document.getElementById('skuDisplay').textContent = variant.sku || '-';
                document.getElementById('stockDisplay').textContent = stock.stock_status;
                
                if (variant.image_url) {
                    document.getElementById('mainImage0').src = variant.image_url;
                }
                
                const addToCartBtn = document.getElementById('addToCartBtn');
                const buyNowBtn = document.getElementById('buyNowBtn');
                
                if (stock.is_in_stock) {
                    addToCartBtn.disabled = false;
                    buyNowBtn.disabled = false;
                } else {
                    addToCartBtn.disabled = true;
                    buyNowBtn.disabled = true;
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // HÀM CẬP NHẬT GIÁ
    function updatePrice(variant) {
        const priceContainer = document.querySelector('.product-option p');
        
        if (!priceContainer) return;
        
        // Định dạng số tiền kiểu Việt Nam
        const price = parseInt(variant.price || 0);
        
        const formatPrice = (price) => {
            return new Intl.NumberFormat('vi-VN').format(price).replace(/\./g, '.');
        };
        
        let priceHTML = `<strong>${formatPrice(price)} đ</strong>`;
        
        priceContainer.innerHTML = priceHTML;
    }
        
</script>
@endsection