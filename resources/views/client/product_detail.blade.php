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
                    <!-- Thumbnails -->
                    <div class="col-sm-2 col-3">
                        <div class="swiper product-slider product-slider-img"> 
                            <div class="swiper-wrapper"> 
                                @foreach($images as $index => $image)
                                    <div class="swiper-slide"> 
                                        <img src="{{ $image }}" alt="Product {{ $index }}" onclick="changeImage('{{ $image }}', {{ $index }})">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Main Image -->
                    <div class="col-sm-10 col-9">
                        <div class="swiper product-slider-thumb product-slider-img-1">
                            <div class="swiper-wrapper ratio_square-2">
                                @foreach($images as $index => $image)
                                    <div class="swiper-slide"> 
                                        <img class="bg-img" 
                                             id="mainImage{{ $index }}"
                                             src="{{ $image }}" 
                                             alt="Product {{ $index }}"
                                             style="display: {{ $index === 0 ? 'block' : 'none' }};">
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
                                    {{ round((($product->base_price - $product->discount_price) / $product->base_price) * 100) }}% off
                                </span>
                            @else
                            <strong>{{ number_format($product->base_price, 0, ',', '.') }} đ</strong>
                            @endif
                        </p>
                        <ul class="rating">      
                            <li><i class="fa-solid fa-star"></i></li>
                            <li><i class="fa-solid fa-star"></i></li>
                            <li><i class="fa-solid fa-star"></i></li>
                            <li><i class="fa-solid fa-star-half-stroke"></i></li>
                            <li><i class="fa-regular fa-star"></i></li>
                            <li>4.5</li>
                            
                        </ul>

                        <h6 >
                            {{ $product->description }}
                        </h6>

                        <!-- Quick Links -->
                        <div class="buy-box border-buttom">
                            <ul> 
                                <li><span data-bs-toggle="modal" data-bs-target="#size-chart" title="Quick View"><i class="iconsax me-2" data-icon="ruler"></i>Bảng Size</span></li>
                                <li><span data-bs-toggle="modal" data-bs-target="#terms-conditions-modal" title="Quick View"><i class="iconsax me-2" data-icon="truck"></i>Giao hàng và hoàn hàng</span></li>
                                
                            </ul>
                        </div>

                        <!-- Attributes Selection -->
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
    @else
       
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

    <!-- Wishlist & Compare -->
    <div class="buy-box">
        <ul> 
            <li><a href="wishlist.html"><i class="fa-regular fa-heart me-2"></i>Thêm vào sản phẩm yêu thích</a></li>
            <li><a href="compare.html"><i class="fa-solid fa-arrows-rotate me-2"></i>Add To Compare</a></li>
            <li><a href="#" data-bs-toggle="modal" data-bs-target="#social-box"><i class="fa-solid fa-share-nodes me-2"></i>Chia sẻ</a></li>
        </ul>
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
        </ul>
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
                    <h4 class="fw-semibold mb-4" style="color: #rgb(217 184 145);">Reviews</h4>
                    <div class="product-content"> 
                        
                        <div id="Reviews-tab-pane" role="tabpanel"> 
                            <div class="row gy-4">
                                <div class="col-lg-4">
                                    <div class="review-right">
                                        <div class="customer-rating">
                                            <div class="global-rating">
                                                <div>
                                                    <h5>4.5</h5>
                                                </div>
                                                <div>
                                                    <h6>Average Ratings</h6>
                                                    <ul class="rating p-0 mb">
                                                        <li><i class="fa-solid fa-star"></i></li>
                                                        <li><i class="fa-solid fa-star"></i></li>
                                                        <li><i class="fa-solid fa-star"></i></li>
                                                        <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                                        <li><i class="fa-regular fa-star"></i></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <ul class="rating-progess">
                                                <li>
                                                    <p>5 Star</p>
                                                    <div class="progress" role="progressbar" aria-label="Animated striped example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 80%"></div>
                                                    </div>
                                                    <p>80%</p>
                                                </li>
                                                <!-- Other ratings follow a similar structure -->
                                            </ul>
                                            <button class="btn reviews-modal" data-bs-toggle="modal" data-bs-target="#Reviews-modal" title="Quick View" tabindex="0">Write a review</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8"> 
                                    <div class="comments-box"> 
                                        <h5>Comments</h5>
                                        <ul class="theme-scrollbar"> 
                                            <li> 
                                                <div class="comment-items"> 
                                                    <div class="user-img"> <img src="{{ asset('client/assets/images/user/1.jpg') }}" alt=""></div>
                                                    <div class="user-content"> 
                                                        <div class="user-info"> 
                                                            <div class="d-flex justify-content-between gap-3"> 
                                                                <h6><i class="iconsax" data-icon="user-1"></i>Michel Poe</h6>
                                                                <span><i class="iconsax" data-icon="clock"></i>Mar 29, 2022</span>
                                                            </div>
                                                            <ul class="rating p-0 mb">
                                                                <li><i class="fa-solid fa-star"></i></li>
                                                                <li><i class="fa-solid fa-star"></i></li>
                                                                <li><i class="fa-solid fa-star"></i></li>
                                                                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                                                <li><i class="fa-regular fa-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <p>Khaki cotton blend military jacket flattering fit mock horn buttons and patch pockets.</p><a href="#"> <span><i class="iconsax" data-icon="undo"></i> Replay</span></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- Additional comments follow a similar structure -->
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
            <h3>Related Products</h3>
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
                                <a class="wishlist-icon" href="javascript:void(0)" tabindex="0" onclick="toggleWishlist(event)">
                                    <i class="far fa-heart" data-bs-toggle="tooltip" data-bs-title="Thêm vào yêu thích"></i>
                                </a>
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

            {{-- Navigation buttons --}}
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>

<script>
// Toggle Wishlist
function toggleWishlist(event) {
    event.preventDefault();
    const icon = event.target.closest('i');
    icon.classList.toggle('far');
    icon.classList.toggle('fas');
}
</script>

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

    // Change main image
    function changeImage(src, index) {
        document.querySelectorAll('[id^="mainImage"]').forEach(img => {
            img.style.display = 'none';
        });
        document.getElementById('mainImage' + index).style.display = 'block';
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

    // Get exact variant
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
</script>
    @endsection