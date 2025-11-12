@extends('client.layouts.master')

@section('title', 'Katie - Online Fashion Store')

@section('content')

<section class="section-b-space pt-0"> 
    <div class="heading-banner">
        <div class="custom-container container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4>Product</h4>
                </div>
                <div class="col-sm-6">
                    <ul class="breadcrumb float-end">
                        <li class="breadcrumb-item"> <a href="#">Home</a> </li>
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-b-space pt-0 product-thumbnail-page"> 
    <div class="custom-container container">
        <div class="row gy-4">
            <div class="col-lg-6"> 
                <div class="row sticky">
                    <div class="col-sm-2 col-3">
                        <div class="swiper product-slider product-slider-img"> 
                            <div class="swiper-wrapper"> 
                                <div class="swiper-slide"> <img src="{{ asset('client/assets/images/product/slider/1.jpg') }}" alt=""></div>
                                <div class="swiper-slide"> <img src="{{ asset('client/assets/images/product/slider/2.jpg') }}" alt=""></div>
                                <div class="swiper-slide"> <img src="{{ asset('client/assets/images/product/slider/3.jpg') }}" alt=""></div>
                                <div class="swiper-slide"> <img src="{{ asset('client/assets/images/product/slider/4.jpg') }}" alt=""><span> <i class="iconsax" data-icon="play"></i></span></div>
                                <div class="swiper-slide"> <img src="{{ asset('client/assets/images/product/slider/5.jpg') }}" alt=""></div>
                                <div class="swiper-slide"> <img src="{{ asset('client/assets/images/product/slider/6.jpg') }}" alt=""></div>
                                <div class="swiper-slide"> <img src="{{ asset('client/assets/images/product/slider/7.jpg') }}" alt=""></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-10 col-9">
                        <div class="swiper product-slider-thumb product-slider-img-1">
                            <div class="swiper-wrapper ratio_square-2">
                                <div class="swiper-slide"> <img class="bg-img" src="{{ asset('client/assets/images/product/slider/1.jpg') }}" alt=""></div>
                                <div class="swiper-slide"> <img class="bg-img" src="{{ asset('client/assets/images/product/slider/2.jpg') }}" alt=""></div>
                                <div class="swiper-slide"> <img class="bg-img" src="{{ asset('client/assets/images/product/slider/3.jpg') }}" alt=""></div>
                                <div class="swiper-slide"> 
                                    <video class="video-tag" loop="" autoplay="" muted="">
                                        <source src="{{ asset('client/assets/images/product/slider/clothing.mp4') }}" type="video/mp4"> 
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <div class="swiper-slide"> <img class="bg-img" src="{{ asset('client/assets/images/product/slider/5.jpg') }}" alt=""></div>
                                <div class="swiper-slide"> <img class="bg-img" src="{{ asset('client/assets/images/product/slider/6.jpg') }}" alt=""></div>
                                <div class="swiper-slide"> <img class="bg-img" src="{{ asset('client/assets/images/product/slider/7.jpg') }}" alt=""></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="product-detail-box"> 
                    <div class="product-option"> 
                        <div class="move-fast-box d-flex align-items-center gap-1">
                            <img src="{{ asset('client/assets/images/gif/fire.gif') }}" alt="">
                            <p>Move fast!</p>
                        </div>
                        <h3>Rustic Minidress with Halterneck</h3>
                        <p>\$20.00 <del>\$35.00</del><span class="offer-btn">25% off</span></p>
                        <div class="rating"> 
                            <ul> 
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                <li><i class="fa-regular fa-star"></i></li>
                            </ul>
                            <p>(4.7) Rating</p>
                            <p>Dressing up. People just don't do it anymore. We have to change that.</p>
                        </div>
                        <div class="buy-box border-buttom">
                            <ul> 
                                <li><span data-bs-toggle="modal" data-bs-target="#size-chart" title="Quick View" tabindex="0"><i class="iconsax me-2" data-icon="ruler"></i>Size Chart</span></li>
                                <li><span data-bs-toggle="modal" data-bs-target="#terms-conditions-modal" title="Quick View" tabindex="0"><i class="iconsax me-2" data-icon="truck"></i>Delivery & return</span></li>
                                <li><span data-bs-toggle="modal" data-bs-target="#question-box" title="Quick View" tabindex="0"><i class="iconsax me-2" data-icon="question-message"></i>Ask a Question</span></li>
                            </ul>
                        </div>
                        <div class="d-flex">
                            <div> 
                                <h5>Size:</h5>
                                <div class="size-box">
                                    <ul class="selected">
                                        <li><a href="#">s</a></li>
                                        <li><a href="#">m</a></li>
                                        <li class="active"><a href="#">l</a></li>
                                        <li><a href="#">xl</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5>Color:</h5>
                            <div class="color-box">
                                <ul class="color-variant">
                                    <li class="bg-color-brown"></li>
                                    <li class="bg-color-chocolate"></li>
                                    <li class="bg-color-coffee"></li>
                                    <li class="bg-color-black"></li>
                                </ul>
                            </div>
                        </div>
                        <div class="quantity-box d-flex align-items-center gap-3">
                            <div class="quantity">
                                <button class="minus" type="button"><i class="fa-solid fa-minus"></i></button>
                                <input type="number" value="1" min="1" max="20">
                                <button class="plus" type="button"><i class="fa-solid fa-plus"></i></button>
                            </div>
                            <div class="d-flex align-items-center gap-3 w-100">   
                                <a class="btn btn_black sm" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Add To Cart</a>
                                <a class="btn btn_outline sm" href="#">Buy Now</a>
                            </div>
                        </div>
                        <div class="buy-box">
                            <ul> 
                                <li><a href="wishlist.html"><i class="fa-regular fa-heart me-2"></i>Add To Wishlist</a></li>
                                <li><a href="compare.html"><i class="fa-solid fa-arrows-rotate me-2"></i>Add To Compare</a></li>
                                <li><a href="#" data-bs-toggle="modal" data-bs-target="#social-box" title="Quick View" tabindex="0"><i class="fa-solid fa-share-nodes me-2"></i>Share</a></li>
                            </ul>
                        </div>
                        <div class="sale-box"> 
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ asset('client/assets/images/gif/timer.gif') }}" alt="">
                                <p>Limited Time Left! Hurry, Sale Ending!</p>
                            </div>
                            <div class="countdown">
                                <ul class="clockdiv1">
                                    <li> 
                                        <div class="timer">
                                            <div class="days"></div>
                                        </div><span class="title">Days</span>
                                    </li>
                                    <li>:</li>
                                    <li> 
                                        <div class="timer">
                                            <div class="hours"></div>
                                        </div><span class="title">Hours</span>
                                    </li>
                                    <li>:</li>
                                    <li> 
                                        <div class="timer">
                                            <div class="minutes"></div>
                                        </div><span class="title">Min</span>
                                    </li>
                                    <li>:</li>
                                    <li> 
                                        <div class="timer">
                                            <div class="seconds"></div>
                                        </div><span class="title">Sec</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="dz-info"> 
                            <ul> 
                                <li>
                                    <div class="d-flex align-items-center gap-2"> 
                                        <h6>Sku:</h6>
                                        <p> SKU_45 </p>
                                    </div>
                                </li>
                                <li> 
                                    <div class="d-flex align-items-center gap-2"> 
                                        <h6>Available:</h6>
                                        <p>Pre-Order</p>
                                    </div>
                                </li>
                                <li> 
                                    <div class="d-flex align-items-center gap-2"> 
                                        <h6>Tags:</h6>
                                        <p>Color  Pink Clay, Athletic, Accessories, Vendor Kalles</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center gap-2"> 
                                        <h6>Vendor:</h6>
                                        <p>Balenciaga</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="share-option">
                            <h5>Secure Checkout</h5>
                            <img class="img-fluid" src="{{ asset('client/assets/images/other-img/secure_payments.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="product-section-box x-small-section pt-0"> 
        <div class="custom-container container">
            <div class="row"> 
                <div class="col-12"> 
                    <ul class="product-tab theme-scrollbar nav nav-tabs nav-underline" id="Product" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="Description-tab" data-bs-toggle="tab" data-bs-target="#Description-tab-pane" role="tab" aria-controls="Description-tab-pane" aria-selected="true">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="specification-tab" data-bs-toggle="tab" data-bs-target="#specification-tab-pane" role="tab" aria-controls="specification-tab-pane" aria-selected="false">Specification</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="Reviews-tab" data-bs-toggle="tab" data-bs-target="#Reviews-tab-pane" role="tab" aria-controls="Reviews-tab-pane" aria-selected="false">Reviews</button>
                        </li>
                    </ul>
                    <div class="tab-content product-content" id="ProductContent">
                        <div class="tab-pane fade show active" id="Description-tab-pane" role="tabpanel" aria-labelledby="Description-tab" tabindex="0">
                            <div class="row gy-4"> 
                                <div class="col-12">    
                                    <p class="paragraphs">Experience the perfect blend of comfort and style with our Summer Breeze Cotton Dress. Crafted from 100% premium cotton, this dress offers a soft and breathable feel, making it ideal for warm weather. The lightweight fabric ensures you stay cool and comfortable throughout the day.</p>
                                    <p class="paragraphs">Perfect for casual outings, beach trips, or summer parties. Pair it with sandals for a relaxed look or dress it up with heels and accessories for a more polished ensemble.</p>
                                </div>
                                <div class="col-12">   
                                    <div class="row gy-4"> 
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="general-summery"> 
                                                <h5>Product Specifications</h5>
                                                <ul> 
                                                    <li>100% Premium Cotton</li>
                                                    <li>A-line silhouette with a flattering fit</li>
                                                    <li>Knee-length for versatile styling</li>
                                                    <li>V-neck for a touch of elegance</li>
                                                    <li>Short sleeves for a casual look</li>
                                                    <li>Available in solid colors and floral prints</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="general-summery"> 
                                                <h5>Washing Instructions</h5>
                                                <ul> 
                                                    <li>Use cold water for washing</li>
                                                    <li>Use a low heat setting for drying.</li>
                                                    <li>Avoid using bleach on this fabric.</li>
                                                    <li>Use a low heat setting when ironing.</li>
                                                    <li>Do not take this item to a dry cleaner.</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="general-summery"> 
                                                <h5>Size & Fit</h5>
                                                <ul> 
                                                    <li>The model (height 5'8) is wearing a size S</li>
                                                    <li>Measurements taken from size Small</li>
                                                    <li>Chest: 30"</li>
                                                    <li>Length: 20"</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="specification-tab-pane" role="tabpanel" aria-labelledby="specification-tab" tabindex="0"> 
                            <p>I like to be real. I don't like things to be staged or fussy. Grunge is a hippied romantic version of punk.</p>
                            <div class="table-responsive theme-scrollbar">
                                <table class="specification-table table striped">
                                    <tr>
                                        <th>Product Dimensions</th>
                                        <td>15 x 15 x 3 cm; 250 Grams</td>
                                    </tr>
                                    <tr>
                                        <th>Date First Available</th>
                                        <td>5 April 2021</td>
                                    </tr>
                                    <tr>
                                        <th>Manufacturer</th>
                                        <td>Aditya Birla Fashion and Retail Limited</td>
                                    </tr>
                                    <tr>
                                        <th>ASIN</th>
                                        <td>B06Y28LCDN</td>
                                    </tr>
                                    <tr>
                                        <th>Item model number</th>
                                        <td>AMKP317G04244</td>
                                    </tr>
                                    <tr>
                                        <th>Department</th>
                                        <td>Men</td>
                                    </tr>
                                    <tr>
                                        <th>Item Weight</th>
                                        <td>250 G</td>
                                    </tr>
                                    <tr>
                                        <th>Item Dimensions LxWxH</th>
                                        <td>15 x 15 x 3 Centimeters</td>
                                    </tr>
                                    <tr>
                                        <th>Net Quantity</th>
                                        <td>1 U</td>
                                    </tr>
                                    <tr>
                                        <th>Included Components</th>
                                        <td>1-T-shirt</td>
                                    </tr>
                                    <tr>
                                        <th>Generic Name</th>
                                        <td>T-shirt</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Reviews-tab-pane" role="tabpanel" aria-labelledby="Reviews-tab" tabindex="0"> 
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

<section class="section-b-space pt-0">
    <div class="custom-container container product-contain">
        <div class="title text-start"> 
            <h3>Related Products</h3>
            <svg>
                <use href="{{ asset('client/assets/svg/icon-sprite.svg#main-line') }}"></use>
            </svg>
        </div>
        <div class="swiper special-offer-slide-2">
            <div class="swiper-wrapper ratio1_3">
                <div class="swiper-slide">
                    <div class="product-box-3">
                        <div class="img-wrapper">
                            <div class="label-block"><span class="lable-1">NEW</span><a class="label-2 wishlist-icon" href="javascript:void(0)" tabindex="0"><i class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Add to Wishlist"></i></a></div>
                            <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img" src="{{ asset('client/assets/images/product/product-3/11.jpg') }}" alt="product"></a><a class="pro-sec" href="product.html"> <img class="bg-img" src="{{ asset('client/assets/images/product/product-3/9.jpg') }}" alt="product"></a></div>
                            <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Add to cart"></i></a><a href="compare.html" tabindex="0"><i class="iconsax" data-icon="arrow-up-down" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Compare"></i></a><a href="#" data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0"><i class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Quick View"></i></a></div>
                        </div>
                        <div class="product-detail">
                            <ul class="rating">      
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                <li><i class="fa-regular fa-star"></i></li>
                                <li>4.3</li>
                            </ul><a href="product.html"> 
                                <h6>Greciilooks Women's Stylish Top</h6></a>
                            <p>\$100.00  
                                <del>\$140.00</del><span>-20%</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="product-box-3">
                        <div class="img-wrapper">
                            <div class="label-block"><span class="lable-1">NEW</span><a class="label-2 wishlist-icon" href="javascript:void(0)" tabindex="0"><i class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Add to Wishlist"></i></a></div>
                            <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img" src="{{ asset('client/assets/images/product/product-3/18.jpg') }}" alt="product"></a><a class="pro-sec" href="product.html"> <img class="bg-img" src="{{ asset('client/assets/images/product/product-3/22.jpg') }}" alt="product"></a></div>
                            <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Add to cart"></i></a><a href="compare.html" tabindex="0"><i class="iconsax" data-icon="arrow-up-down" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Compare"></i></a><a href="#" data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0"><i class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Quick View"></i></a></div>
                        </div>
                        <div class="product-detail">
                            <ul class="rating">      
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-regular fa-star"></i></li>
                                <li>4.3</li>
                            </ul><a href="product.html"> 
                                <h6>Wide Linen-Blend Trousers</h6></a>
                            <p>\$100.00  
                                <del>\$18.00</del>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="product-box-3">
                        <div class="img-wrapper">
                            <div class="label-block"><span class="lable-1">NEW</span><a class="label-2 wishlist-icon" href="javascript:void(0)" tabindex="0"><i class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Add to Wishlist"></i></a></div>
                            <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img" src="{{ asset('client/assets/images/product/product-3/12.jpg') }}" alt="product"></a><a class="pro-sec" href="product.html"> <img class="bg-img" src="{{ asset('client/assets/images/product/product-3/10.jpg') }}" alt="product"></a></div>
                            <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart" tabindex="0"><i class="iconsax" data-icon="basket-2" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Add to cart"></i></a><a href="compare.html" tabindex="0"><i class="iconsax" data-icon="arrow-up-down" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Compare"></i></a><a href="#" data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0"><i class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Quick View"></i></a></div>
                        </div>
                        <div class="product-detail">
                            <ul class="rating">      
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-solid fa-star"></i></li>
                                <li><i class="fa-regular fa-star"></i></li>
                                <li>4.3</li>
                            </ul><a href="product.html"> 
                                <h6>Blue lined White T-Shirt</h6></a>
                            <p>\$190.00  
                                <del>\$210.00</del>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection