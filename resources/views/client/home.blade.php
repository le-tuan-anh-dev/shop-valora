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
{{-- ========================================================= --}}
    {{-- PHẦN CHATBOT (ĐÃ CẬP NHẬT GIAO DIỆN & SỬA LỖI)            --}}
    {{-- ========================================================= --}}

    <style>
        /* Nút tròn chat (Icon Robot) */
        #chat-circle {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 65px; /* To hơn chút */
            height: 65px;
            background: #007bff; /* Màu xanh chủ đạo */
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px; /* Icon to hơn */
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            cursor: pointer;
            transition: all 0.3s;
        }

        #chat-circle:hover {
            transform: scale(1.1);
            background: #0056b3;
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.4);
        }

        /* Khung chat Box */
        .chat-box {
            display: none; /* Ẩn mặc định */
            position: fixed;
            bottom: 110px; /* Cách nút tròn một chút */
            right: 30px;
            
            /* --- [FIX QUAN TRỌNG: KÍCH THƯỚC] --- */
            width: 450px;          /* Chiều rộng to hơn (cũ là 380px) */
            max-width: 90vw;       /* Không quá 90% chiều rộng màn hình (cho mobile) */
            
            height: 70vh;          /* Cao bằng 70% chiều cao màn hình -> Không bị tràn lên trên */
            max-height: 600px;     /* Giới hạn cao nhất là 600px */
            /* ------------------------------------ */

            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            overflow: hidden;
            font-family: sans-serif;
            border: 1px solid #ddd;
            flex-direction: column; /* Để xếp dọc Header - Body - Input */
        }

        /* Header Chat */
        .chat-box-header {
            background: linear-gradient(135deg, #007bff, #0056b3); /* Màu gradient đẹp hơn */
            color: white;
            padding: 15px 20px;
            font-weight: bold;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0; /* Không bị co lại */
        }
        .chat-close { cursor: pointer; font-size: 24px; transition: 0.2s; }
        .chat-close:hover { color: #ffdddd; }

        /* Vùng hiển thị tin nhắn */
        #messages {
            flex-grow: 1; /* Tự động chiếm hết khoảng trống còn lại */
            overflow-y: auto; /* Chỉ cuộn nội dung bên trong, không cuộn cả web */
            padding: 20px;
            background: #f4f6f9;
        }

        /* Tùy chỉnh thanh cuộn cho đẹp */
        #messages::-webkit-scrollbar { width: 6px; }
        #messages::-webkit-scrollbar-thumb { background-color: #ccc; border-radius: 4px; }

        .message {
            margin-bottom: 15px;
            padding: 12px 16px;
            border-radius: 12px;
            max-width: 80%;
            word-wrap: break-word;
            line-height: 1.5;
            font-size: 15px; /* Chữ to hơn chút cho dễ đọc */
            position: relative;
        }

        .user {
            background: #007bff;
            color: white;
            margin-left: auto;
            text-align: right;
            border-bottom-right-radius: 2px;
        }

        .ai {
            background: #ffffff;
            color: #333;
            border: 1px solid #e1e1e1;
            border-bottom-left-radius: 2px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        /* Input Area */
        .input-area {
            display: flex;
            padding: 15px;
            background: white;
            border-top: 1px solid #eee;
            flex-shrink: 0; /* Không bị co lại */
        }

        #msg {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 25px;
            outline: none;
            font-size: 15px;
            background: #f9f9f9;
        }
        #msg:focus { border-color: #007bff; background: #fff; }

        #btn-send {
            margin-left: 10px;
            padding: 0 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: background 0.2s;
        }
        #btn-send:hover { background: #0056b3; }

        /* Card Sản Phẩm trong chat */
        .product-container {
            margin-top: 12px;
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        .product-container:hover { transform: translateY(-3px); }
        .product-img {
            width: 100%;
            height: 140px; /* Ảnh to hơn */
            object-fit: cover;
            border-radius: 6px;
            background: #f0f0f0;
        }
        .product-caption {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            margin-top: 8px;
            color: #007bff;
            text-transform: uppercase;
        }
        .product-link { text-decoration: none; display: block; }
    </style>

    <div id="chat-circle" onclick="toggleChat()">
        <i class="fa-solid fa-robot"></i>
    </div>

    <div class="chat-box" id="chat-box" style="display: none;">
        <div class="chat-box-header">
            <span><i class="fa-solid fa-robot" style="margin-right: 8px;"></i> AI Trợ Lý</span>
            <span class="chat-close" onclick="toggleChat()">&times;</span>
        </div>
        
        <div id="messages">
            <div class="message ai">Xin chào! Tôi là AI hỗ trợ. Bạn cần tìm sản phẩm gì không?</div>
        </div>

        <div class="input-area">
            <input id="msg" type="text" placeholder="Nhập câu hỏi..." onkeydown="if(event.keyCode===13) sendMsg()">
            <button id="btn-send" onclick="sendMsg()">Gửi</button>
        </div>
    </div>

    <script>
        // Hàm bật/tắt khung chat
        function toggleChat() {
            var chatBox = document.getElementById('chat-box');
            if (chatBox.style.display === 'none' || chatBox.style.display === '') {
                chatBox.style.display = 'flex';
                // Focus vào ô nhập liệu khi mở
                setTimeout(() => document.getElementById("msg").focus(), 100);
            } else {
                chatBox.style.display = 'none';
            }
        }

        // Hàm gửi tin nhắn (Logic cũ của bạn)
        function sendMsg() {
            let text = document.getElementById("msg").value;
            if (!text) return;

            // Hiển thị tin nhắn User
            let msgDiv = document.createElement('div');
            msgDiv.className = 'message user';
            msgDiv.textContent = text;
            let messagesArea = document.getElementById("messages");
            messagesArea.appendChild(msgDiv);
            messagesArea.scrollTop = messagesArea.scrollHeight; // Auto scroll

            document.getElementById("msg").value = '';

            // Gọi API
            fetch("/chatbot/ask", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" // Laravel Token
                },
                body: JSON.stringify({ message: text })
            })
            .then(res => res.json())
            .then(data => {
                let replyDiv = document.createElement('div');
                replyDiv.className = 'message ai';
                let messageHtml = data.message;
                
                // Lấy danh sách link từ Backend
                const PRODUCT_LINKS = data.product_links || {};

                // --- [FIX LỖI HIỂN THỊ ẢNH] ---
                // Sắp xếp tên dài lên trước để ưu tiên hiển thị (tránh nhầm tên con)
                let productNames = Object.keys(PRODUCT_LINKS).sort((a, b) => b.length - a.length);

                productNames.forEach(name => {
                    const product = PRODUCT_LINKS[name];
                    
                    // Xử lý ký tự đặc biệt trong tên để không lỗi Regex
                    let safeName = name.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                    
                    // Regex tìm tên sản phẩm (không phân biệt hoa thường)
                    const regex = new RegExp(`(${safeName})`, 'gi'); 

                    if (messageHtml.match(regex)) {
                        // In đậm tên sản phẩm
                        messageHtml = messageHtml.replace(regex, "<b>$1</b>");

                        // Tạo thẻ Ảnh (có fallback nếu ảnh lỗi)
                        const imageHtml = `
                            <div class="product-container">
                                <a href="${product.product_url}" target="_blank" class="product-link">
                                    <img src="${product.image_url}" class="product-img" 
                                         alt="${name}"
                                         onerror="this.src='https://placehold.co/400x300?text=No+Image'; this.onerror=null;">
                                    <div class="product-caption">Xem chi tiết</div>
                                </a>
                            </div><br>`;
                        
                        // Chèn ảnh xuống cuối câu trả lời
                        messageHtml += imageHtml;
                    }
                });

                replyDiv.innerHTML = messageHtml;
                messagesArea.appendChild(replyDiv);
                messagesArea.scrollTop = messagesArea.scrollHeight;
            })
            .catch(err => {
                console.error(err);
                alert("Lỗi kết nối Server");
            });
        }
    </script>



@endsection
