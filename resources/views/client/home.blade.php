@extends('client.layouts.master')

@section('title', 'Velora - Online Fashion Store')

@section('content')
    {{-- danh mục và banner --}}
    <section class="pt-0 home-section-3">
        <div class="container-fluid">
            <div class="row align-items-center">

                <div class="col-2 d-none d-xl-block">
                    <div class="category-sidebar">
                        <div class="category-list">
                            <ul class="category-menu flush-left">
                                @forelse($categories as $category)
                                    <li>
                                        <a href="{{ route('shop.index') }}" class="category-link">
                                            {{ $category->name }}

                                        </a>
                                    </li>
                                @empty
                                    <li>
                                        <p class="text-muted text-left flush-left-text">Chưa có danh mục</p>
                                    </li>
                                @endforelse

                                @if ($categories->count() > 8)
                                    <li class="more-categories-li">
                                        <a href="{{ route('shop.index') }}" class="category-link view-all-link">
                                            Xem thêm <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Nội dung banner chính (bên PHẢI) -->
                <div class="col pe-0">
                    <div class="home-banner p-right">
                        <img class="img-fluid" src="{{ asset('client/assets/images/layout-3/1.jpg') }}" alt="" />
                        <div class="contain-banner">
                            <div>
                                <h4>Ưu đãi hấp dẫn <span>BẮT ĐẦU NGAY HÔM NAY</span></h4>
                                <h1>Khám phá phong cách thời trang sáng tạo đích thực của bạn.</h1>
                                
                                <div class="link-hover-anim underline">
                                    <a class="btn btn_underline link-strong" href="#">
                                        Mua Ngay
                                        <svg>
                                            <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- chính sách --}}
    <section class="section-t-space">
        <div class="custom-container container service">
            <ul>
                <li>
                    <div class="service-block">
                        <img src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/1.svg" alt="" />
                        <div>
                            <h6>Miễn phí vận chuyển trên toàn thế giới</h6>
                            <p>Áp dụng cho tất cả các đơn hàng trên 1.000.000 VND</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="service-block">
                        <img src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/2.svg" alt="" />
                        <div>
                            <h6>Trả & Hoàn hàng</h6>
                            <p>Bảo hành đầy đủ</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="service-block">
                        <img src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/3.svg" alt="" />
                        <div>
                            <h6>Hỗ trọ</h6>
                            <p>Hỗ trợ dịch vụ 24/7</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="service-block border-0">
                        <img src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/4.svg" alt="" />
                        <div>
                            <h6>Phiếu quà tặng hàng ngày</h6>
                            <p>Mua sắm đầy ưu đãi</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </section>
    {{-- sản phẩm --}}
    <section class="section-t-space">
        <div class="custom-container container product-contain">
            <div class="title">
                <h3>Sản phẩm đặc biệt</h3>
                <svg>
                    <use href="{{ asset('client/assets/svg/icon-sprite.svg#main-line') }}"></use>
                </svg>
            </div>

            <div class="row trending-products">
                <div class="col-12">
                    <div class="theme-tab-1">
                        <!-- NAV TABS -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#features-products"
                                    role="tab">
                                    <h6>Sản phẩm giảm giá</h6>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" data-bs-target="#latest-products" role="tab">
                                    <h6>Sản phẩm mới nhất</h6>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" data-bs-target="#seller-products" role="tab">
                                    <h6>Bán chạy nhất</h6>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-12 ratio_square">
                            <div class="tab-content">

                                {{-- FEATURED PRODUCTS --}}
                                <div class="tab-pane fade show active" id="features-products" role="tabpanel">
                                    <div class="row g-4">
                                        @forelse($featuredProducts as $product)
                                            <div class="col-xxl-3 col-md-4 col-6">
                                                <div class="product-box">
                                                    <div class="img-wrapper">
                                                        {{-- Label Sale --}}
                                                        @if ($product->discount_price)
                                                            <div class="label-block">
                                                                <img src="{{ asset('client/assets/images/product/2.png') }}"
                                                                    alt="label">
                                                                <span>Giảm <br>giá!</span>
                                                            </div>
                                                        @endif

                                                        {{-- Product Image --}}
                                                        <div class="product-image">
                                                            <a href="{{ route('products.detail', $product->id) }}">
                                                                <img class="bg-img"
                                                                    src="{{ $product->image_main ? asset('storage/' . $product->image_main) : asset('client/assets/images/product/product-4/1.jpg') }}"
                                                                    alt="{{ $product->name }}">
                                                            </a>
                                                        </div>

                                                        {{-- Icon chỉ còn wishlist --}}
                                                        <style>
                                                            .cart-info-icon i {
                                                                font-size: 30px;
                                                                color: #777;
                                                                /* màu mặc định */
                                                                cursor: pointer;
                                                                transition: color 0.2s;
                                                            }

                                                            .cart-info-icon i:hover {
                                                                color: red;
                                                                /* di vào thì đỏ */
                                                            }

                                                            /* Toast thông báo */
                                                            .wishlist-toast {
                                                                position: fixed;
                                                                top: 50%;
                                                                left: 50%;
                                                                transform: translate(-50%, -50%);
                                                                /* căn giữa theo cả 2 trục */
                                                                background: rgba(0, 0, 0, 0.85);
                                                                color: #fff;
                                                                padding: 10px 16px;
                                                                border-radius: 6px;
                                                                font-size: 14px;
                                                                opacity: 0;
                                                                visibility: hidden;
                                                                transition: all 0.3s ease;
                                                                z-index: 9999;
                                                            }

                                                            .wishlist-toast.show {
                                                                opacity: 1;
                                                                visibility: visible;
                                                                transform: translateY(0);
                                                            }
                                                        </style>

                                                        <div class="cart-info-icon">
                                                            @auth
                                                                {{-- User đã đăng nhập: bấm là thêm vào wishlist --}}
                                                                <a href="#"
                                                                    onclick="
                                                                        event.preventDefault();
                                                                        showWishlistToast('Đã thêm vào danh sách yêu thích');
                                                                        document.getElementById('wishlist-form-{{ $product->id }}').submit();
                                                                ">
                                                                    <i class="far fa-heart" data-bs-toggle="tooltip"
                                                                        data-bs-title="Thêm vào yêu thích"></i>
                                                                </a>

                                                                <form id="wishlist-form-{{ $product->id }}"
                                                                    action="{{ route('wishlist.add', $product->id) }}"
                                                                    method="POST" class="d-none">
                                                                    @csrf
                                                                </form>
                                                            @else
                                                                {{-- Chưa đăng nhập: bấm chuyển qua trang login --}}
                                                                <a href="{{ route('login') }}">
                                                                    <i class="far fa-heart" data-bs-toggle="tooltip"
                                                                        data-bs-title="Đăng nhập để thêm vào yêu thích"></i>
                                                                </a>
                                                            @endauth
                                                        </div>

                                                        {{-- Toast hiển thị thông báo --}}
                                                        <div id="wishlist-toast" class="wishlist-toast"></div>

                                                        <script>
                                                            function showWishlistToast(message) {
                                                                const toast = document.getElementById('wishlist-toast');
                                                                if (!toast) return;

                                                                toast.textContent = message;
                                                                toast.classList.add('show');

                                                                // 1.5 giây sau thì ẩn đi
                                                                setTimeout(() => {
                                                                    toast.classList.remove('show');
                                                                }, 1500);
                                                            }
                                                        </script>
                                                    </div>
                                                    {{-- Product Detail --}}
                                                    <div class="product-detail">
                                                        {{-- Đổi thành Xem chi tiết --}}
                                                        <div class="add-button">
                                                            <a href="{{ route('products.detail', $product->id) }}">
                                                                <i class="fa-solid fa-eye"></i> Xem chi tiết
                                                            </a>
                                                        </div>

                                                        <a href="{{ route('products.detail', $product->id) }}">
                                                            <h5>{{ Str::limit($product->name, 40) }}</h5>
                                                        </a>

                                                        <p>
                                                            @if ($product->discount_price)
                                                                {{ number_format($product->discount_price, 0, ',', '.') }}₫
                                                                <del>{{ number_format($product->base_price, 0, ',', '.') }}₫</del>
                                                                @php
                                                                    $discountPercent = round(
                                                                        (($product->discount_price -
                                                                            $product->base_price) /
                                                                            $product->base_price) *
                                                                            100,
                                                                    );
                                                                @endphp
                                                                <span>{{ $discountPercent }}%</span>
                                                            @else
                                                                {{ number_format($product->base_price, 0, ',', '.') }}₫
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p class="text-center">Chưa có sản phẩm nổi bật</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- LATEST PRODUCTS --}}
                                <div class="tab-pane fade" id="latest-products" role="tabpanel">
                                    <div class="row g-4">
                                        @forelse($latestProducts as $product)
                                            <div class="col-xxl-3 col-md-4 col-6">
                                                <div class="product-box">
                                                    <div class="img-wrapper">
                                                        @if ($product->discount_price)
                                                            <div class="label-block">
                                                                <img src="{{ asset('client/assets/images/product/2.png') }}"
                                                                    alt="label">
                                                                <span>Giảm <br>giá!</span>
                                                            </div>
                                                        @endif

                                                        <div class="product-image">
                                                            <a href="{{ route('products.detail', $product->id) }}">
                                                                <img class="bg-img"
                                                                    src="{{ $product->image_main ? asset('storage/' . $product->image_main) : asset('client/assets/images/product/product-4/1.jpg') }}"
                                                                    alt="{{ $product->name }}">
                                                            </a>
                                                        </div>

                                                        <div class="cart-info-icon">
                                                            <a class="wishlist-icon" href="javascript:void(0)">
                                                                <i class="far fa-heart" data-bs-toggle="tooltip"
                                                                    data-bs-title="Thêm vào yêu thích"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <div class="product-detail">
                                                        <div class="add-button">
                                                            <a href="{{ route('products.detail', $product->id) }}">
                                                                <i class="fa-solid fa-eye"></i> Xem chi tiết
                                                            </a>
                                                        </div>

                                                        <a href="{{ route('products.detail', $product->id) }}">
                                                            <h5>{{ Str::limit($product->name, 40) }}</h5>
                                                        </a>

                                                        <p>
                                                            @if ($product->discount_price)
                                                                {{ number_format($product->discount_price, 0, ',', '.') }}₫
                                                                <del>{{ number_format($product->base_price, 0, ',', '.') }}₫</del>
                                                                @php
                                                                    $discountPercent = round(
                                                                        (($product->discount_price -
                                                                            $product->base_price) /
                                                                            $product->base_price) *
                                                                            100,
                                                                    );
                                                                @endphp
                                                                <span>{{ $discountPercent }}%</span>
                                                            @else
                                                                {{ number_format($product->base_price, 0, ',', '.') }}₫
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p class="text-center">Chưa có sản phẩm mới</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- BEST SELLER PRODUCTS --}}
                                <div class="tab-pane fade" id="seller-products" role="tabpanel">
                                    <div class="row g-4">
                                        @forelse($bestSellerProducts as $product)
                                            <div class="col-xxl-3 col-md-4 col-6">
                                                <div class="product-box">
                                                    <div class="img-wrapper">
                                                        @if ($product->discount_price)
                                                            <div class="label-block">
                                                                <img src="{{ asset('client/assets/images/product/2.png') }}"
                                                                    alt="label">
                                                                <span>Giảm <br>giá!</span>
                                                            </div>
                                                        @endif

                                                        <div class="product-image">
                                                            <a href="{{ route('products.detail', $product->id) }}">
                                                                <img class="bg-img"
                                                                    src="{{ $product->image_main ? asset('storage/' . $product->image_main) : asset('client/assets/images/product/product-4/1.jpg') }}"
                                                                    alt="{{ $product->name }}">
                                                            </a>
                                                        </div>

                                                        <div class="cart-info-icon">
                                                            <a class="wishlist-icon" href="javascript:void(0)">
                                                                <i class="far fa-heart" data-bs-toggle="tooltip"
                                                                    data-bs-title="Thêm vào yêu thích"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <div class="product-detail">
                                                        <div class="add-button">
                                                            <a href="{{ route('products.detail', $product->id) }}">
                                                                <i class="fa-solid fa-eye"></i> Xem chi tiết
                                                            </a>
                                                        </div>

                                                        <a href="{{ route('products.detail', $product->id) }}">
                                                            <h5>{{ Str::limit($product->name, 40) }}</h5>
                                                        </a>

                                                        <p>
                                                            @if ($product->discount_price)
                                                                {{ number_format($product->discount_price, 0, ',', '.') }}₫
                                                                <del>{{ number_format($product->base_price, 0, ',', '.') }}₫</del>
                                                                @php
                                                                    $discountPercent = round(
                                                                        (($product->discount_price -
                                                                            $product->base_price) /
                                                                            $product->base_price) *
                                                                            100,
                                                                    );
                                                                @endphp
                                                                <span>{{ $discountPercent }}%</span>
                                                            @else
                                                                {{ number_format($product->base_price, 0, ',', '.') }}₫
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p class="text-center">Chưa có sản phẩm bán chạy</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- bài viết --}}
    <section class="section-t-space">
        <div class="custom-container container">
            <div class="title">
                <h3>Bài viết mới nhất</h3>
                <svg>
                    <use href="{{ asset('client/assets/svg/icon-sprite.svg#main-line') }}"></use>
                </svg>
            </div>
            <div class="swiper blog-slide">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="blog-main">
                            <div class="blog-box ratio3_2">
                                <a class="blog-img" href="#">
                                    <img class="bg-img" src="{{ asset('client/assets/images/blog/layout-4/1.jpg') }}"
                                        alt="blog 1">
                                </a>
                            </div>
                            <div class="blog-txt">
                                <p>By: Admin / 26th aug 2020</p>
                                <a href="#">
                                    <h5>Many desktop publishing pack-ages abd page editor...</h5>
                                </a>
                                <div class="link-hover-anim underline">
                                    <a class="btn btn_underline link-strong link-strong-unhovered" href="#">Read
                                        More
                                        <svg>
                                            <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                        </svg>
                                    </a>
                                    <a class="btn btn_underline link-strong link-strong-hovered" href="#">Read More
                                        <svg>
                                            <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide blog-main">
                        <div class="blog-box ratio_55">
                            <a class="blog-img" href="#">
                                <img class="bg-img" src="{{ asset('client/assets/images/blog/layout-4/2.jpg') }}"
                                    alt="blog 2">
                            </a>
                        </div>
                        <div class="blog-txt">
                            <p>By: Admin / 26th aug 2020</p>
                            <a href="#">
                                <h5>Many desktop publishing pack-ages abd page editor...</h5>
                            </a>
                            <div class="link-hover-anim underline">
                                <a class="btn btn_underline link-strong link-strong-unhovered" href="#">Read More
                                    <svg>
                                        <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                    </svg>
                                </a>
                                <a class="btn btn_underline link-strong link-strong-hovered" href="#">Read More
                                    <svg>
                                        <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide blog-main">
                        <div class="blog-box ratio3_2">
                            <a class="blog-img" href="#">
                                <img class="bg-img" src="{{ asset('client/assets/images/blog/layout-4/3.jpg') }}"
                                    alt="blog 3">
                            </a>
                        </div>
                        <div class="blog-txt">
                            <p>By: Admin / 26th aug 2020</p>
                            <a href="#">
                                <h5>Many desktop publishing pack-ages abd page editor...</h5>
                            </a>
                            <div class="link-hover-anim underline">
                                <a class="btn btn_underline link-strong link-strong-unhovered" href="#">Read More
                                    <svg>
                                        <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                    </svg>
                                </a>
                                <a class="btn btn_underline link-strong link-strong-hovered" href="#">Read More
                                    <svg>
                                        <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide blog-main">
                        <div class="blog-box ratio_55">
                            <a class="blog-img" href="#">
                                <img class="bg-img" src="{{ asset('client/assets/images/blog/layout-4/4.jpg') }}"
                                    alt="blog 4">
                            </a>
                        </div>
                        <div class="blog-txt">
                            <p>By: Admin / 26th aug 2020</p>
                            <a href="#">
                                <h5>Many desktop publishing pack-ages abd page editor...</h5>
                            </a>
                            <div class="link-hover-anim underline">
                                <a class="btn btn_underline link-strong link-strong-unhovered" href="#">Read More
                                    <svg>
                                        <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                    </svg>
                                </a>
                                <a class="btn btn_underline link-strong link-strong-hovered" href="#">Read More
                                    <svg>
                                        <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    {{-- form nhân thông tin --}}
    <section class="section-t-space ratio3_3">
        <div class="container-fluid subscribe-banner">
            <div class="row align-items-center">
                <div class="col-xl-8 col-md-7 col-12 px-0">
                    <a href="{{ route('home') }}">
                        <img class="bg-img" src="{{ asset('client/assets/images/banner/banner-6.png') }}"
                            alt="" />
                    </a>
                </div>
                <div class="col-xl-4 col-5">
                    <div class="subscribe-content">
                        <h6>Giảm giá tới 30%</h6>
                        <h4>Đăng ký nhận thông tin của chúng tôi!</h4>
                        <p>
                            Tham gia danh sách - bạn sẽ là người đầu tiên biết về các sản phẩm mới, các chương trình dành riêng và được giảm giá 30.000 VND cho đơn đầu tiên.
                        </p>
                        <input type="text" name="text" placeholder="Your email address..." />
                        <div class="link-hover-anim underline">
                            <a class="btn btn_underline link-strong link-strong-unhovered"
                                href="{{ route('home') }}">Đăng ký ngay
                                <svg>
                                    <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                </svg>
                            </a>
                            <a class="btn btn_underline link-strong link-strong-hovered"
                                href="{{ route('home') }}">Đăng ký ngay
                                <svg>
                                    <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- sản phẩm --}}
    <section class="section-t-space">
        <div class="custom-container container product-contain">
            <div class="title">
                <h3>Sản phẩm đa dạng nhất</h3>
                <svg>
                    <use href="{{ asset('client/assets/svg/icon-sprite.svg#main-line') }}"></use>
                </svg>
            </div>

            <div class="swiper fashikart-slide">
                <div class="swiper-wrapper trending-products ratio_square">

                    @forelse($diverseProducts as $product)
                        <div class="swiper-slide product-box">
                            <div class="img-wrapper">
                                @if ($product->discount_price)
                                    <div class="label-block">
                                        <img src="{{ asset('client/assets/images/product/2.png') }}" alt="label">
                                        <span>Giảm <br>giá!</span>
                                    </div>
                                @endif
                                {{-- Ảnh sản phẩm --}}
                                <div class="product-image">
                                    <a href="{{ route('products.detail', $product->id) }}">
                                        <img class="bg-img"
                                            src="{{ $product->image_main ? asset('storage/' . $product->image_main) : asset('client/assets/images/product/product-4/1.jpg') }}"
                                            alt="{{ $product->name }}">
                                    </a>
                                </div>

                                {{-- Icons --}}
                                <div class="cart-info-icon">
                                    <a class="wishlist-icon" href="javascript:void(0)" tabindex="0">
                                        <i class="far fa-heart" data-bs-toggle="tooltip"
                                            data-bs-title="Thêm vào yêu thích"></i>
                                    </a>
                                </div>
                            </div>

                            {{-- Chi tiết sản phẩm --}}
                            <div class="product-detail">
                                <div class="add-button">
                                    <a href="{{ route('products.detail', $product->id) }}" title="Xem chi tiết"
                                        tabindex="0">
                                        <i class="fa-solid fa-eye"></i> Xem chi tiết
                                    </a>
                                </div>


                                <a href="{{ route('products.detail', $product->id) }}">
                                    <h5>{{ Str::limit($product->name, 40) }}</h5>
                                </a>

                                {{-- Giá sản phẩm --}}
                                <p>
                                    @if ($product->discount_price)
                                        {{ number_format($product->discount_price, 0, ',', '.') }}₫
                                        <del>{{ number_format($product->base_price, 0, ',', '.') }}₫</del>
                                        @php
                                            $discountPercent = round(
                                                (($product->discount_price - $product->base_price) /
                                                    $product->base_price) *
                                                    100,
                                            );
                                        @endphp
                                        <span>{{ $discountPercent }}%</span>
                                    @else
                                        {{ number_format($product->base_price, 0, ',', '.') }}₫
                                    @endif
                                </p>


                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <p class="text-center">Chưa có sản phẩm</p>
                        </div>
                    @endforelse

                </div>

                {{-- Navigation buttons --}}
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </section>
    {{-- insta KOL --}}
    <section class="section-t-space instashop-section">
        <div class="container-fluid">
            <div class="row row-cols-xl-5 row-cols-md-4 row-cols-2 ratio_square-1">
                <div class="col">
                    <div class="instagram-box">
                        <div class="instashop-effect">
                            <img class="bg-img" src="{{ asset('client/assets/images/instagram/17.jpg') }}"
                                alt="">
                            <div class="insta-txt">
                                <div>
                                    <svg class="insta-icon">
                                        <use href="{{ asset('client/assets/svg/icon-sprite.svg#instagram') }}"></use>
                                    </svg>
                                    <p>KOL Insta</p>
                                    <div class="link-hover-anim underline">
                                        <a class="btn btn_underline link-strong link-strong-unhovered"
                                            href="product.html">Đến ngay
                                            <svg>
                                                <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                            </svg>
                                        </a>
                                        <a class="btn btn_underline link-strong link-strong-hovered"
                                            href="product.html">Đến ngay
                                            <svg>
                                                <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="instagram-box">
                        <div class="instashop-effect">
                            <img class="bg-img" src="{{ asset('client/assets/images/instagram/18.jpg') }}"
                                alt="">
                            <div class="insta-txt">
                                <div>
                                    <svg class="insta-icon">
                                        <use href="{{ asset('client/assets/svg/icon-sprite.svg#instagram') }}"></use>
                                    </svg>
                                    <p>KOL Insta</p>
                                    <div class="link-hover-anim underline">
                                        <a class="btn btn_underline link-strong link-strong-unhovered"
                                            href="product.html">Đến ngay
                                            <svg>
                                                <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                            </svg>
                                        </a>
                                        <a class="btn btn_underline link-strong link-strong-hovered"
                                            href="product.html">Đến ngay
                                            <svg>
                                                <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-12">
                    <div class="instagram-txt-box">
                        <div>
                            <div>
                                <div class="instashop-icon">
                                    <svg>
                                        <use href="{{ asset('client/assets/svg/icon-sprite.svg#instagram') }}"></use>
                                    </svg>
                                    <h3>KOL Instagram</h3>
                                </div>
                                <span></span>
                                <p>A conscious collection made entirely from food crop waste, recycled cotton, other more
                                    sustainable materials.</p>
                            </div>
                            <div>
                                <div class="link-hover-anim underline">
                                    <a class="btn btn_underline link-strong link-strong-unhovered"
                                        href="https://www.instagram.com/" target="_blank">Đến ngay Instagram</a>
                                    <a class="btn btn_underline link-strong link-strong-hovered"
                                        href="https://www.instagram.com/" target="_blank">Đến ngay Instagram</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="instagram-box">
                        <div class="instashop-effect">
                            <img class="bg-img" src="{{ asset('client/assets/images/instagram/19.jpg') }}"
                                alt="">
                            <div class="insta-txt">
                                <div>
                                    <svg class="insta-icon">
                                        <use href="{{ asset('client/assets/svg/icon-sprite.svg#instagram') }}"></use>
                                    </svg>
                                    <p>Instashop</p>
                                    <div class="link-hover-anim underline">
                                        <a class="btn btn_underline link-strong link-strong-unhovered"
                                            href="product.html">Discover
                                            <svg>
                                                <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                            </svg>
                                        </a>
                                        <a class="btn btn_underline link-strong link-strong-hovered"
                                            href="product.html">Discover
                                            <svg>
                                                <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="instagram-box">
                        <div class="instashop-effect">
                            <img class="bg-img" src="{{ asset('client/assets/images/instagram/20.jpg') }}"
                                alt="">
                            <div class="insta-txt">
                                <div>
                                    <svg class="insta-icon">
                                        <use href="{{ asset('client/assets/svg/icon-sprite.svg#instagram') }}"></use>
                                    </svg>
                                    <p>Instashop</p>
                                    <div class="link-hover-anim underline">
                                        <a class="btn btn_underline link-strong link-strong-unhovered"
                                            href="product.html">Discover
                                            <svg>
                                                <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                            </svg>
                                        </a>
                                        <a class="btn btn_underline link-strong link-strong-hovered"
                                            href="product.html">Discover
                                            <svg>
                                                <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- thương hiệu --}}

    {{-- thương hiệu --}}
    <section class="section-b-space">
        <div class="custom-container container">
            <div class="swiper logo-slider">
                <div class="swiper-wrapper">

                    @foreach ($brands as $brand)
                        <div class="swiper-slide">
                            <a href="#">
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}">
                            </a>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
    {{-- ========================================================= --}}
    {{-- PHẦN CHATBOT (ĐÃ CẬP NHẬT GIAO DIỆN & SỬA LỖI)            --}}
    {{-- ========================================================= --}}

    <style>
    /* Nút tròn chat */
    #chat-circle {
        position: fixed;
        bottom: 30px; right: 30px;
        width: 60px; height: 60px;
        background: #007bff;
        border-radius: 50%;
        color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 28px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        cursor: pointer; z-index: 9999;
        transition: transform 0.3s;
    }
    #chat-circle:hover { transform: scale(1.1); }

    /* Khung chat */
    .chat-box {
        display: none; /* Mặc định ẩn */
        position: fixed;
        bottom: 100px; right: 30px;
        width: 400px; max-width: 90%;
        height: 65vh; max-height: 600px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 30px rgba(0,0,0,0.2);
        z-index: 9999;
        flex-direction: column;
        border: 1px solid #ddd;
        font-family: Arial, sans-serif;
    }

    /* Header */
    .chat-header {
        background: #007bff; color: white;
        padding: 15px;
        font-weight: bold;
        display: flex; justify-content: space-between; align-items: center;
        border-radius: 12px 12px 0 0;
    }
    .chat-close { cursor: pointer; font-size: 20px; }

    /* Vùng tin nhắn */
    #messages {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
        background: #f4f6f9;
        display: flex; flex-direction: column; gap: 10px;
    }

    /* Bong bóng tin nhắn */
    .message {
        padding: 10px 14px;
        border-radius: 10px;
        max-width: 85%;
        word-wrap: break-word;
        font-size: 14px;
        line-height: 1.5;
    }
    .user {
        background: #007bff; color: white;
        align-self: flex-end; /* Căn phải */
    }
    .ai {
        background: #fff; color: #333;
        border: 1px solid #e1e1e1;
        align-self: flex-start; /* Căn trái */
    }

    /* --- HIỆU ỨNG LOADING (3 chấm nhảy) --- */
    .typing-indicator {
        display: none; /* Ẩn mặc định, JS sẽ bật */
        align-self: flex-start;
        background: #e6e6e6;
        padding: 10px 15px;
        border-radius: 20px;
        margin-bottom: 10px;
    }
    .dot {
        height: 8px; width: 8px;
        background-color: #666;
        border-radius: 50%;
        display: inline-block;
        margin: 0 2px;
        animation: chatBounce 1.4s infinite ease-in-out both;
    }
    .dot:nth-child(1) { animation-delay: -0.32s; }
    .dot:nth-child(2) { animation-delay: -0.16s; }
    @keyframes chatBounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    /* --- THẺ SẢN PHẨM INLINE (Hiện ngay trong dòng) --- */
    .inline-product-card {
        display: block;
        margin: 8px 0;
        border: 1px solid #eee;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        max-width: 250px; /* Giới hạn chiều rộng ảnh cho gọn */
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .inline-product-card img {
        width: 100%; height: 150px; object-fit: cover;
    }
    .inline-product-card .btn-view {
        display: block;
        text-align: center;
        padding: 6px;
        background: #f8f9fa;
        color: #007bff;
        text-decoration: none;
        font-size: 12px;
        font-weight: bold;
    }
    .inline-product-card .btn-view:hover { background: #e9ecef; }

    /* Input */
    .input-area {
        padding: 10px; border-top: 1px solid #eee; background: white;
        display: flex;
    }
    #msg-input {
        flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 20px; outline: none;
    }
    #btn-send {
        margin-left: 10px; padding: 0 15px;
        background: #007bff; color: white; border: none; border-radius: 20px; cursor: pointer;
    }
</style>

<div id="chat-circle" onclick="toggleChat()">
    <i class="fa-solid fa-robot"></i> </div>

<div class="chat-box" id="chat-box">
    <div class="chat-header">
        <span>AI Trợ Lý</span>
        <span class="chat-close" onclick="toggleChat()">×</span>
    </div>
    
    <div id="messages">
        <div class="message ai">Xin chào! Tôi có thể giúp gì cho bạn?</div>
        <div class="typing-indicator" id="loading-dots">
            <span class="dot"></span><span class="dot"></span><span class="dot"></span>
        </div>
    </div>

    <div class="input-area">
        <input type="text" id="msg-input" placeholder="Nhập tin nhắn..." onkeydown="if(event.keyCode===13) sendMessage()">
        <button id="btn-send" onclick="sendMessage()">Gửi</button>
    </div>
</div>

<script>
    // --- KHAI BÁO BIẾN TOÀN CỤC ---
    const chatBox = document.getElementById('chat-box');
    const messagesArea = document.getElementById('messages');
    const inputField = document.getElementById('msg-input');
    const loadingDots = document.getElementById('loading-dots');
    let isChatOpen = false;
    let isProcessing = false;

    // 1. HÀM BẬT TẮT CHAT (Đảm bảo hoạt động)
    function toggleChat() {
        isChatOpen = !isChatOpen;
        chatBox.style.display = isChatOpen ? 'flex' : 'none';
        if (isChatOpen) inputField.focus();
    }

    // 2. HÀM GỬI TIN NHẮN
    function sendMessage() {
        if (isProcessing) return; // Chặn spam

        const text = inputField.value.trim();
        if (!text) return;

        // Hiện tin nhắn User
        appendMessage(text, 'user');
        inputField.value = '';

        // Bật Loading
        showLoading(true);

        // Gọi API
        fetch("/chatbot/ask", {
            method: "POST",
            headers: { 
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}" // Blade token
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => res.json())
        .then(data => {
            const rawMsg = data.message;
            const links = data.product_links || {};
            
            // XỬ LÝ CHÈN ẢNH VÀO GIỮA VĂN BẢN
            const finalHtml = processMessageWithImages(rawMsg, links);
            
            appendMessage(finalHtml, 'ai', true); // true = render HTML
        })
        .catch(err => {
            console.error(err);
            appendMessage("Lỗi kết nối server. Vui lòng thử lại!", 'ai');
        })
        .finally(() => {
            showLoading(false);
        });
    }

    // 3. LOGIC CHÈN ẢNH VÀO SAU TÊN SẢN PHẨM (CORE)
    function processMessageWithImages(text, productLinks) {
        let processedText = text.replace(/\n/g, '<br>'); // Xuống dòng
        
        // Sắp xếp tên dài trước để replace chính xác
        const sortedNames = Object.keys(productLinks).sort((a, b) => b.length - a.length);

        sortedNames.forEach(name => {
            // Lấy data sản phẩm
            const prod = productLinks[name];
            
            // Tạo thẻ HTML cho ảnh
            const cardHtml = `
                <div class="inline-product-card">
                    <a href="${prod.product_url}" target="_blank">
                        <img src="${prod.image_url}" onerror="this.src='https://placehold.co/300?text=No+Image'">
                        <div class="btn-view">Xem chi tiết ${prod.name}</div>
                    </a>
                </div>
            `;

            // Regex tìm tên (không phân biệt hoa thường)
            // Replace tên sản phẩm = Tên sản phẩm + Thẻ ảnh
            const safeName = name.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
            const regex = new RegExp(`(${safeName})`, 'gi');
            
            // Chỉ replace lần xuất hiện đầu tiên hoặc tất cả tuỳ ý. 
            // Ở đây dùng replace (thay thế lần đầu) để tránh spam ảnh nếu tên lặp lại nhiều lần.
            // Nếu muốn tất cả thì dùng replaceAll hoặc regex g
            processedText = processedText.replace(regex, `<b>$1</b><br>${cardHtml}`);
        });

        return processedText;
    }

    // Hỗ trợ UI
    function appendMessage(content, role, isHtml = false) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `message ${role}`;
        
        if (isHtml) msgDiv.innerHTML = content;
        else msgDiv.textContent = content;

        // Chèn tin nhắn mới LÊN TRÊN cái loading (để loading luôn ở cuối)
        messagesArea.insertBefore(msgDiv, loadingDots);
        scrollToBottom();
    }

    function showLoading(show) {
        isProcessing = show;
        loadingDots.style.display = show ? 'block' : 'none';
        scrollToBottom();
    }

    function scrollToBottom() {
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }
</script>



@endsection
