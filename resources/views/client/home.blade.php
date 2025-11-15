@extends('client.layouts.master')

@section('title', 'Katie - Online Fashion Store')

@section('content')
    {{-- danh mục và banner --}}
    <section class="pt-0 home-section-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-2 d-none d-xl-block">
                    <ul>
                        <li><a href="#">Women's Clothing</a></li>
                        <li><a href="#">Men's Clothing</a></li>
                        <li><a href="#">Kids Clothing</a></li>
                        <!-- Add more categories -->
                    </ul>
                </div>

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
            <h3>Fashikart specials</h3>
            <svg>
                <use href="{{ asset('client/assets/svg/icon-sprite.svg#main-line') }}"></use>
            </svg>
        </div>

        <div class="row trending-products">
            <div class="col-12">
                <div class="theme-tab-1">

                    <!-- =============== NAV TABS =============== -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#features-products"
                                role="tab">
                                <h6>Featured Products</h6>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#latest-products" role="tab">
                                <h6>Latest Products</h6>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#seller-products" role="tab">
                                <h6>Best Seller Products</h6>
                            </a>
                        </li>
                    </ul>

                    <!-- =============== TAB CONTENT =============== -->
                    <div class="tab-content">

                        <!-- FEATURED -->
                        <div class="tab-pane fade show active" id="features-products" role="tabpanel">
                            <div class="row g-4">
                                @foreach ($featuredProducts as $p)
                                <div class="col-xxl-3 col-md-4 col-6">
                                    <div class="product-box">
                                        <div class="img-wrapper">

                                            <div class="label-block">
                                                @if ($p->discount_price)
                                                    <span>Sale</span>
                                                @endif
                                            </div>

                                            <div class="product-image">
                                                <a href="{{ route('products.detail', $p->id) }}">
                                                    <img class="bg-img"
                                                        src="{{ $p->image_main ? asset('storage/products/' . $p->image_main) : asset('client/assets/images/no-image.png') }}"
                                                        alt="{{ $p->name }}">
                                                </a>
                                            </div>
                                        </div>

                                        <div class="product-detail">
                                            <a href="{{ route('products.detail', $p->id) }}">
                                                <h6>{{ $p->name }}</h6>
                                            </a>

                                            <p>
                                                @if ($p->discount_price)
                                                    ${{ number_format($p->discount_price) }}
                                                    <del>${{ number_format($p->base_price) }}</del>
                                                @else
                                                    ${{ number_format($p->base_price) }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- LATEST -->
                        <div class="tab-pane fade" id="latest-products" role="tabpanel">
                            <div class="row g-4">
                                @foreach ($latestProducts as $p)
                                <div class="col-xxl-3 col-md-4 col-6">
                                    <div class="product-box">
                                        <div class="img-wrapper">
                                            <div class="label-block">
                                                @if ($p->discount_price)
                                                    <span>Sale</span>
                                                @endif
                                            </div>

                                            <div class="product-image">
                                                <a href="{{ route('products.detail', $p->id) }}">
                                                    <img class="bg-img"
                                                        src="{{ $p->image_main ? asset('storage/products/' . $p->image_main) : asset('client/assets/images/no-image.png') }}"
                                                        alt="{{ $p->name }}">
                                                </a>
                                            </div>
                                        </div>

                                        <div class="product-detail">
                                            <a href="{{ route('products.detail', $p->id) }}">
                                                <h6>{{ $p->name }}</h6>
                                            </a>

                                            <p>
                                                @if ($p->discount_price)
                                                    ${{ number_format($p->discount_price) }}
                                                    <del>${{ number_format($p->base_price) }}</del>
                                                @else
                                                    ${{ number_format($p->base_price) }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- BEST SELLER -->
                        <div class="tab-pane fade" id="seller-products" role="tabpanel">
                            <div class="row g-4">
                                @foreach ($bestSellerProducts as $p)
                                <div class="col-xxl-3 col-md-4 col-6">
                                    <div class="product-box">
                                        <div class="img-wrapper">

                                            <div class="label-block">
                                                @if ($p->discount_price)
                                                    <span>Sale</span>
                                                @endif
                                            </div>

                                            <div class="product-image">
                                                <a href="{{ route('products.detail', $p->id) }}">
                                                    <img class="bg-img"
                                                        src="{{ $p->image_main ? asset('storage/products/' . $p->image_main) : asset('client/assets/images/no-image.png') }}"
                                                        alt="{{ $p->name }}">
                                                </a>
                                            </div>

                                        </div>

                                        <div class="product-detail">
                                            <a href="{{ route('products.detail', $p->id) }}">
                                                <h6>{{ $p->name }}</h6>
                                            </a>

                                            <p>
                                                @if ($p->discount_price)
                                                    ${{ number_format($p->discount_price) }}
                                                    <del>${{ number_format($p->base_price) }}</del>
                                                @else
                                                    ${{ number_format($p->base_price) }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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
                <h3>Fashikart specials</h3>
                <svg>
                    <use href="{{ asset('client/assets/svg/icon-sprite.svg#main-line') }}"></use>
                </svg>
            </div>
            <div class="swiper fashikart-slide">
                <div class="swiper-wrapper trending-products ratio_square">
                    <div class="swiper-slide product-box">
                        <div class="img-wrapper">
                            <div class="label-block">
                                <img src="{{ asset('client/assets/images/product/2.png') }}" alt="label">
                                <span>on <br>Sale!</span>
                            </div>
                            <div class="product-image">
                                <a href="#">
                                    <img class="bg-img"
                                        src="{{ asset('client/assets/images/product/product-4/7.jpg') }}"
                                        alt="product">
                                </a>
                            </div>
                            <div class="cart-info-icon">
                                <a class="wishlist-icon" href="javascript:void(0)" tabindex="0">
                                    <i class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip"
                                        data-bs-title="Add to Wishlist"></i>
                                </a>
                                <a href="compare.html" tabindex="0">
                                    <i class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                        data-bs-toggle="tooltip" data-bs-title="Compare"></i>
                                </a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#quick-view"
                                    tabindex="0">
                                    <i class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                        data-bs-title="Quick View"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-detail">
                            <div class="add-button">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                                    title="add product" tabindex="0">
                                    <i class="fa-solid fa-plus"></i> Add To Cart
                                </a>
                            </div>
                            <div class="color-box">
                                <ul class="color-variant">
                                    <li class="bg-color-purple"></li>
                                    <li class="bg-color-blue"></li>
                                    <li class="bg-color-red"></li>
                                    <li class="bg-color-yellow"></li>
                                </ul>
                                <span>4.5 <i class="fa-solid fa-star"></i></span>
                            </div>
                            <a href="#">
                                <h6>ASIAN Women's Barfi-02 Shoes</h6>
                            </a>
                            <p>\$100.00 <del>\$140.00</del></p>
                        </div>
                    </div>
                    <div class="swiper-slide product-box">
                        <div class="img-wrapper">
                            <div class="product-image">
                                <a href="#">
                                    <img class="bg-img"
                                        src="{{ asset('client/assets/images/product/product-4/8.jpg') }}"
                                        alt="product">
                                </a>
                            </div>
                            <div class="cart-info-icon">
                                <a class="wishlist-icon" href="javascript:void(0)" tabindex="0">
                                    <i class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip"
                                        data-bs-title="Add to Wishlist"></i>
                                </a>
                                <a href="compare.html" tabindex="0">
                                    <i class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                        data-bs-toggle="tooltip" data-bs-title="Compare"></i>
                                </a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#quick-view"
                                    tabindex="0">
                                    <i class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                        data-bs-title="Quick View"></i>
                                </a>
                            </div>
                            <div class="countdown">
                                <ul class="clockdiv4">
                                    <li>
                                        <div class="timer">
                                            <div class="days"></div>
                                        </div>
                                        <span class="title">Days</span>
                                    </li>
                                    <li class="dot"><span>:</span></li>
                                    <li>
                                        <div class="timer">
                                            <div class="hours"></div>
                                        </div>
                                        <span class="title">Hours</span>
                                    </li>
                                    <li class="dot"><span>:</span></li>
                                    <li>
                                        <div class="timer">
                                            <div class="minutes"></div>
                                        </div>
                                        <span class="title">Min</span>
                                    </li>
                                    <li class="dot"><span>:</span></li>
                                    <li>
                                        <div class="timer">
                                            <div class="seconds"></div>
                                        </div>
                                        <span class="title">Sec</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="product-detail">
                            <div class="add-button">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                                    title="add product" tabindex="0">
                                    <i class="fa-solid fa-plus"></i> Add To Cart
                                </a>
                            </div>
                            <div class="color-box">
                                <ul class="color-variant">
                                    <li class="bg-color-purple"></li>
                                    <li class="bg-color-blue"></li>
                                    <li class="bg-color-red"></li>
                                    <li class="bg-color-yellow"></li>
                                </ul>
                                <span>3.5 <i class="fa-solid fa-star"></i></span>
                            </div>
                            <a href="#">
                                <h6>Women Rayon Solid Hat</h6>
                            </a>
                            <p>\$120.00 <del>\$140.00</del></p>
                        </div>
                    </div>
                    <div class="swiper-slide product-box">
                        <div class="img-wrapper">
                            <div class="label-block">
                                <img src="{{ asset('client/assets/images/product/3.png') }}" alt="label">
                                <span>on <br>Sale!</span>
                            </div>
                            <div class="product-image">
                                <a href="#">
                                    <img class="bg-img"
                                        src="{{ asset('client/assets/images/product/product-4/9.jpg') }}"
                                        alt="product">
                                </a>
                            </div>
                            <div class="cart-info-icon">
                                <a class="wishlist-icon" href="javascript:void(0)" tabindex="0">
                                    <i class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip"
                                        data-bs-title="Add to Wishlist"></i>
                                </a>
                                <a href="compare.html" tabindex="0">
                                    <i class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                        data-bs-toggle="tooltip" data-bs-title="Compare"></i>
                                </a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#quick-view"
                                    tabindex="0">
                                    <i class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                        data-bs-title="Quick View"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-detail">
                            <div class="add-button">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                                    title="add product" tabindex="0">
                                    <i class="fa-solid fa-plus"></i> Add To Cart
                                </a>
                            </div>
                            <div class="color-box">
                                <ul class="color-variant">
                                    <li class="bg-color-purple"></li>
                                    <li class="bg-color-blue"></li>
                                    <li class="bg-color-red"></li>
                                    <li class="bg-color-yellow"></li>
                                </ul>
                                <span>2.5 <i class="fa-solid fa-star"></i></span>
                            </div>
                            <a href="#">
                                <h6>OJASS Men's Solid Regular Jacket</h6>
                            </a>
                            <p>\$1300 <del>\$140.00</del></p>
                        </div>
                    </div>
                    <div class="swiper-slide product-box">
                        <div class="img-wrapper">
                            <div class="product-image">
                                <a href="#">
                                    <img class="bg-img"
                                        src="{{ asset('client/assets/images/product/product-4/10.jpg') }}"
                                        alt="product">
                                </a>
                            </div>
                            <div class="cart-info-icon">
                                <a class="wishlist-icon" href="javascript:void(0)" tabindex="0">
                                    <i class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip"
                                        data-bs-title="Add to Wishlist"></i>
                                </a>
                                <a href="compare.html" tabindex="0">
                                    <i class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                        data-bs-toggle="tooltip" data-bs-title="Compare"></i>
                                </a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#quick-view"
                                    tabindex="0">
                                    <i class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                        data-bs-title="Quick View"></i>
                                </a>
                            </div>
                            <div class="countdown">
                                <ul class="clockdiv5">
                                    <li>
                                        <div class="timer">
                                            <div class="days"></div>
                                        </div>
                                        <span class="title">Days</span>
                                    </li>
                                    <li class="dot"><span>:</span></li>
                                    <li>
                                        <div class="timer">
                                            <div class="hours"></div>
                                        </div>
                                        <span class="title">Hours</span>
                                    </li>
                                    <li class="dot"><span>:</span></li>
                                    <li>
                                        <div class="timer">
                                            <div class="minutes"></div>
                                        </div>
                                        <span class="title">Min</span>
                                    </li>
                                    <li class="dot"><span>:</span></li>
                                    <li>
                                        <div class="timer">
                                            <div class="seconds"></div>
                                        </div>
                                        <span class="title">Sec</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="product-detail">
                            <div class="add-button">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                                    title="add product" tabindex="0">
                                    <i class="fa-solid fa-plus"></i> Add To Cart
                                </a>
                            </div>
                            <div class="color-box">
                                <ul class="color-variant">
                                    <li class="bg-color-purple"></li>
                                    <li class="bg-color-blue"></li>
                                    <li class="bg-color-red"></li>
                                    <li class="bg-color-yellow"></li>
                                </ul>
                                <span>3.5 <i class="fa-solid fa-star"></i></span>
                            </div>
                            <a href="#">
                                <h6>Fiesto Fashion Women's Handbag</h6>
                            </a>
                            <p>\$120.00 <del>\$140.00</del></p>
                        </div>
                    </div>
                    <div class="swiper-slide product-box">
                        <div class="img-wrapper">
                            <div class="product-image">
                                <a href="#">
                                    <img class="bg-img"
                                        src="{{ asset('client/assets/images/product/product-4/3.jpg') }}"
                                        alt="product">
                                </a>
                            </div>
                            <div class="cart-info-icon">
                                <a class="wishlist-icon" href="javascript:void(0)" tabindex="0">
                                    <i class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip"
                                        data-bs-title="Add to Wishlist"></i>
                                </a>
                                <a href="compare.html" tabindex="0">
                                    <i class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                        data-bs-toggle="tooltip" data-bs-title="Compare"></i>
                                </a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#quick-view"
                                    tabindex="0">
                                    <i class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                        data-bs-title="Quick View"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-detail">
                            <div class="add-button">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart"
                                    title="add product" tabindex="0">
                                    <i class="fa-solid fa-plus"></i> Add To Cart
                                </a>
                            </div>
                            <div class="color-box">
                                <ul class="color-variant">
                                    <li class="bg-color-purple"></li>
                                    <li class="bg-color-blue"></li>
                                    <li class="bg-color-red"></li>
                                    <li class="bg-color-yellow"></li>
                                </ul>
                                <span>2.5 <i class="fa-solid fa-star"></i></span>
                            </div>
                            <a href="#">
                                <h6>Beautiful Lycra Solid Women's High Zipper</h6>
                            </a>
                            <p>\$1300 <del>\$140.00</del></p>
                        </div>
                    </div>
                </div>
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
    <section class="section-b-space">
        <div class="custom-container container">
            <div class="swiper logo-slider">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <a href="#">
                            <img src="{{ asset('client/assets/images/logos/1.png') }}" alt="logo">
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#">
                            <img src="{{ asset('client/assets/images/logos/2.png') }}" alt="logo">
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#">
                            <img src="{{ asset('client/assets/images/logos/3.png') }}" alt="logo">
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#">
                            <img src="{{ asset('client/assets/images/logos/4.png') }}" alt="logo">
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#">
                            <img src="{{ asset('client/assets/images/logos/5.png') }}" alt="logo">
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#">
                            <img src="{{ asset('client/assets/images/logos/6.png') }}" alt="logo">
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#">
                            <img src="{{ asset('client/assets/images/logos/7.png') }}" alt="logo">
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#">
                            <img src="{{ asset('client/assets/images/logos/3.png') }}" alt="logo">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
