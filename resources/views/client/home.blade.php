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

                            @if($categories->count() > 8)
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
                            <h4>Hot Offer <span>START TODAY</span></h4>
                            <h1>Explore Your True Creative Fashion.</h1>
                            <p>Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.</p>
                            <div class="link-hover-anim underline">
                                <a class="btn btn_underline link-strong" href="#">
                                    Show Now
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
                            <h6>Free Shipping Worldwide</h6>
                            <p>Apply to all orders over $800</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="service-block">
                        <img src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/2.svg" alt="" />
                        <div>
                            <h6>Return & Exchanges</h6>
                            <p>Complete warranty</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="service-block">
                        <img src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/3.svg" alt="" />
                        <div>
                            <h6>Technical Support</h6>
                            <p>Service support 24/7</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="service-block border-0">
                        <img src="https://themes.pixelstrap.net/katie/assets/images/svg-icon/4.svg" alt="" />
                        <div>
                            <h6>Daily Gift Vouchers</h6>
                            <p>Shopping now is more fun</p>
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
                            <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#features-products" role="tab">
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
                                                @if($product->discount_price)
                                                <div class="label-block">
                                                    <img src="{{ asset('client/assets/images/product/2.png') }}" alt="label">
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
                                                <div class="cart-info-icon">
                                                    <a class="wishlist-icon" href="javascript:void(0)">
                                                       <i class="far fa-heart" data-bs-toggle="tooltip" data-bs-title="Thêm vào yêu thích"></i>
                                                    </a>
                                                </div>
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
                                                    @if($product->discount_price)
                                                        {{ number_format($product->discount_price, 0, ',', '.') }}₫
                                                        <del>{{ number_format($product->base_price, 0, ',', '.') }}₫</del>
                                                        @php
                                                            $discountPercent = round((($product->discount_price -$product->base_price) / $product->base_price) * 100);
                                                        @endphp
                                                        <span>-{{ $discountPercent }}%</span>
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
                                                @if($product->discount_price)
                                                <div class="label-block">
                                                    <img src="{{ asset('client/assets/images/product/2.png') }}" alt="label">
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
                                                        <i class="far fa-heart" data-bs-toggle="tooltip" data-bs-title="Thêm vào yêu thích"></i>
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
                                                    @if($product->discount_price)
                                                        {{ number_format($product->discount_price, 0, ',', '.') }}₫
                                                        <del>{{ number_format($product->base_price, 0, ',', '.') }}₫</del>
                                                        @php
                                                            $discountPercent = round((($product->discount_price -$product->base_price) / $product->base_price) * 100);
                                                        @endphp
                                                        <span>-{{ $discountPercent }}%</span>
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
                                                @if($product->discount_price)
                                                <div class="label-block">
                                                    <img src="{{ asset('client/assets/images/product/2.png') }}" alt="label">
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
                                                        <i class="far fa-heart" data-bs-toggle="tooltip" data-bs-title="Thêm vào yêu thích"></i>
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
                                                    @if($product->discount_price)
                                                        {{ number_format($product->discount_price, 0, ',', '.') }}₫
                                                        <del>{{ number_format($product->base_price, 0, ',', '.') }}₫</del>
                                                        @php
                                                            $discountPercent = round((($product->discount_price -$product->base_price) / $product->base_price) * 100);
                                                        @endphp
                                                        <span>-{{ $discountPercent }}%</span>
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
                <h3>Latest Blog</h3>
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
                        <h6>GET 20% OFF</h6>
                        <h4>Subscribe to Our Newsletter!</h4>
                        <p>
                            Join the insider list - you’ll be the first to know about new
                            arrivals, insider - only discounts and receive \$15 off your
                            first order.
                        </p>
                        <input type="text" name="text" placeholder="Your email address..." />
                        <div class="link-hover-anim underline">
                            <a class="btn btn_underline link-strong link-strong-unhovered"
                                href="{{ route('home') }}">Subscribe Now
                                <svg>
                                    <use href="{{ asset('client/assets/svg/icon-sprite.svg#arrow') }}"></use>
                                </svg>
                            </a>
                            <a class="btn btn_underline link-strong link-strong-hovered"
                                href="{{ route('home') }}">Subscribe Now
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
                            @if($product->discount_price)
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
                                <i class="far fa-heart" data-bs-toggle="tooltip" data-bs-title="Thêm vào yêu thích"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Chi tiết sản phẩm --}}
                    <div class="product-detail">
                        <div class="add-button">
                            <a href="{{ route('products.detail', $product->id) }}"
                                title="Xem chi tiết" tabindex="0">
                                <i class="fa-solid fa-eye"></i> Xem chi tiết
                            </a>
                        </div>


                        <a href="{{ route('products.detail', $product->id) }}">
                            <h5>{{ Str::limit($product->name, 40) }}</h5>
                        </a>

                        {{-- Giá sản phẩm --}}
                        <p>
                            @if($product->discount_price)
                                {{ number_format($product->discount_price, 0, ',', '.') }}₫ 
                                <del>{{ number_format($product->base_price, 0, ',', '.') }}₫</del>
                                @php
                                    $discountPercent = round((($product->discount_price -$product->base_price) / $product->base_price) * 100);
                                @endphp
                                <span>-{{ $discountPercent }}%</span>
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
                            <img class="bg-img" src="{{ asset('client/assets/images/instagram/18.jpg') }}"
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
                <div class="col col-12">
                    <div class="instagram-txt-box">
                        <div>
                            <div>
                                <div class="instashop-icon">
                                    <svg>
                                        <use href="{{ asset('client/assets/svg/icon-sprite.svg#instagram') }}"></use>
                                    </svg>
                                    <h3>Instashop</h3>
                                </div>
                                <span></span>
                                <p>A conscious collection made entirely from food crop waste, recycled cotton, other more
                                    sustainable materials.</p>
                            </div>
                            <div>
                                <div class="link-hover-anim underline">
                                    <a class="btn btn_underline link-strong link-strong-unhovered"
                                        href="https://www.instagram.com/" target="_blank">Go To Instagram</a>
                                    <a class="btn btn_underline link-strong link-strong-hovered"
                                        href="https://www.instagram.com/" target="_blank">Go To Instagram</a>
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
                            <img src="{{ asset('storage/'.$brand->logo) }}" alt="{{ $brand->name }}">
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>




@endsection
