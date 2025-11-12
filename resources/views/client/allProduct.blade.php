@extends('client.layouts.master')

@section('title', 'Katie - Online Fashion Store')

@section('content')
<section class="section-b-space pt-0"> 
    <div class="heading-banner">
        <div class="custom-container container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4>Collection Left Sidebar</h4>
                </div>
              
            </div>
        </div>
    </div>
</section>

<section class="section-b-space pt-0"> 
    <div class="custom-container container">
        <div class="row"> 
            <!-- Sidebar bên trái giữ nguyên -->
            <div class="col-3"> 
                <div class="custom-accordion theme-scrollbar left-box">
                    <div class="left-accordion"> 
                        <h5>Back</h5>
                        <i class="back-button fa-solid fa-xmark"></i>
                    </div>
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        <div class="search-box">
                            <input type="search" name="text" placeholder="Search here...">
                            <i class="iconsax" data-icon="search-normal-2"></i>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header tags-header">
                                <button class="accordion-button">
                                    <span>Applied Filters</span>
                                    <span>view all</span>
                                </button>
                            </h2>
                            <div class="accordion-collapse collapse show" id="panelsStayOpen-collapse">
                                <div class="accordion-body">
                                    <ul class="tags"> 
                                        <li><a href="#">T-Shirt <i class="iconsax" data-icon="add"></i></a></li>
                                        <li><a href="#">Handbags<i class="iconsax" data-icon="add"></i></a></li>
                                        <li><a href="#">Trends<i class="iconsax" data-icon="add"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseEight">
                                    <span>Collections</span>
                                </button>
                            </h2>
                            <div class="accordion-collapse collapse show" id="panelsStayOpen-collapseEight">
                                <div class="accordion-body">
                                    <ul class="collection-list">
                                        <li> 
                                            <input class="custom-checkbox" id="category10" type="checkbox" name="text">
                                            <label for="category10">All products</label>
                                        </li>
                                        <li> 
                                            <input class="custom-checkbox" id="category11" type="checkbox" name="text">
                                            <label for="category11">Best sellers</label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item"> 
                            <h2 class="accordion-header">
                                <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo">
                                    <span>Categories</span>
                                </button>
                            </h2>
                            <div class="accordion-collapse collapse show" id="panelsStayOpen-collapseTwo">
                                <div class="accordion-body">
                                    <ul class="catagories-side theme-scrollbar">
                                        <li> 
                                            <input class="custom-checkbox" id="category1" type="checkbox" name="text">
                                            <label for="category1">Fashion (30)</label>
                                        </li>
                                        <li> 
                                            <input class="custom-checkbox" id="category2" type="checkbox" name="text">
                                            <label for="category2">Trends</label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour">
                                    <span>Filter</span>
                                </button>
                            </h2>
                            <div class="accordion-collapse collapse show" id="panelsStayOpen-collapseFour">
                                <div class="accordion-body">
                                    <div class="range-slider">
                                        <input class="range-slider-input" type="range" min="0" max="120000" step="1" value="20000">
                                        <input class="range-slider-input" type="range" min="0" max="120000" step="1" value="100000">
                                        <div class="range-slider-display"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne">
                                    <span>Color</span>
                                </button>
                            </h2>
                            <div class="accordion-collapse collapse show" id="panelsStayOpen-collapseOne">
                                <div class="accordion-body">
                                    <div class="color-box">
                                        <ul class="color-variant">
                                            <li class="bg-color-purple"></li>
                                            <li class="bg-color-blue"></li>
                                            <li class="bg-color-red"></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phần nội dung bên phải với 3 sản phẩm -->
            <div class="col-xl-9">
                <div class="sticky">
                    <div class="top-filter-menu">
                        <div>
                            <a class="filter-button btn">
                                <h6><i class="iconsax" data-icon="filter"></i>Filter Menu</h6>
                            </a>
                            <div class="category-dropdown">
                                <label for="cars">Sort By:</label>
                                <select class="form-select" id="cars" name="carlist">
                                    <option value="">Best selling</option>
                                    <option value="">Popularity</option>
                                    <option value="">Featured</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="product-tab-content ratio1_3">
                        <div class="row-cols-lg-4 row-cols-md-3 row-cols-2 grid-section view-option row g-3 g-xl-4">
                            <!-- Product 1 -->
                            <div> 
                                <div class="product-box-3">
                                    <div class="img-wrapper">
                                        <div class="label-block">
                                            <a class="label-2 wishlist-icon" href="javascript:void(0)" tabindex="0">
                                                <i class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Add to Wishlist"></i>
                                            </a>
                                        </div>
                                        <div class="product-image">
                                            <a class="pro-first" href="#">
                                                <img class="bg-img" src="{{ asset('client/assets/images/product/product-3/1.jpg') }}" alt="Greciilooks Women's Stylish Top">
                                            </a>
                                            <a class="pro-sec" href="#">
                                                <img class="bg-img" src="{{ asset('client/assets/images/product/product-3/20.jpg') }}" alt="Greciilooks Women's Stylish Top">
                                            </a>
                                        </div>
                                        <div class="cart-info-icon">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart" tabindex="0">
                                                <i class="iconsax" data-icon="basket-2" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Add to cart"></i>
                                            </a>
                                            <a href="#" tabindex="0">
                                                <i class="iconsax" data-icon="arrow-up-down" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Compare"></i>
                                            </a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0">
                                                <i class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Quick View"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-detail">
                                        <ul class="rating">      
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                            <li><i class="fa-regular fa-star"></i></li>
                                            <li>4.3</li>
                                        </ul>
                                        <a href="#"> 
                                            <h6>Greciilooks Women's Stylish Top</h6>
                                        </a>
                                        <p>\$100.00 <del>\$140.00</del><span>-20%</span></p>
                                        <div class="listing-button">
                                            <a class="btn" href="#">Quick Shop</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Product 2 -->
                            <div> 
                                <div class="product-box-3">
                                    <div class="img-wrapper">
                                        <div class="label-block">
                                            <span class="lable-1">NEW</span>
                                            <a class="label-2 wishlist-icon" href="javascript:void(0)" tabindex="0">
                                                <i class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Add to Wishlist"></i>
                                            </a>
                                        </div>
                                        <div class="product-image">
                                            <a class="pro-first" href="#">
                                                <img class="bg-img" src="{{ asset('client/assets/images/product/product-3/3.jpg') }}" alt="Long Sleeve Rounded T-Shirt">
                                            </a>
                                            <a class="pro-sec" href="#">
                                                <img class="bg-img" src="{{ asset('client/assets/images/product/product-3/18.jpg') }}" alt="Long Sleeve Rounded T-Shirt">
                                            </a>
                                        </div>
                                        <div class="cart-info-icon">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart" tabindex="0">
                                                <i class="iconsax" data-icon="basket-2" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Add to cart"></i>
                                            </a>
                                            <a href="#" tabindex="0">
                                                <i class="iconsax" data-icon="arrow-up-down" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Compare"></i>
                                            </a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0">
                                                <i class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Quick View"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-detail">
                                        <ul class="rating">      
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li>4.5</li>
                                        </ul>
                                        <a href="#"> 
                                            <h6>Long Sleeve Rounded T-Shirt</h6>
                                        </a>
                                        <p>\$120.30 <del>\$140.00</del><span>-20%</span></p>
                                        <div class="listing-button">
                                            <a class="btn" href="#">Quick Shop</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Product 3 -->
                            <div> 
                                <div class="product-box-3">
                                    <div class="img-wrapper">
                                        <div class="label-block">
                                            <a class="label-2 wishlist-icon" href="javascript:void(0)" tabindex="0">
                                                <i class="iconsax" data-icon="heart" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Add to Wishlist"></i>
                                            </a>
                                        </div>
                                        <div class="product-image">
                                            <a class="pro-first" href="#">
                                                <img class="bg-img" src="{{ asset('client/assets/images/product/product-3/4.jpg') }}" alt="Blue lined White T-Shirt">
                                            </a>
                                            <a class="pro-sec" href="#">
                                                <img class="bg-img" src="{{ asset('client/assets/images/product/product-3/17.jpg') }}" alt="Blue lined White T-Shirt">
                                            </a>
                                        </div>
                                        <div class="cart-info-icon">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart" tabindex="0">
                                                <i class="iconsax" data-icon="basket-2" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Add to cart"></i>
                                            </a>
                                            <a href="#" tabindex="0">
                                                <i class="iconsax" data-icon="arrow-up-down" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Compare"></i>
                                            </a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0">
                                                <i class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip" data-bs-title="Quick View"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-detail">
                                        <ul class="rating">      
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                            <li>4.3</li>
                                        </ul>
                                        <a href="#"> 
                                            <h6>Blue lined White T-Shirt</h6>
                                        </a>
                                        <p>\$190.00 <del>\$210.00</del></p>
                                        <div class="listing-button">
                                            <a class="btn" href="#">Quick Shop</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pagination-wrap">
                        <ul class="pagination"> 
                            <li><a class="prev" href="#"><i class="iconsax" data-icon="chevron-left"></i></a></li>
                            <li><a class="active" href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a class="next" href="#"><i class="iconsax" data-icon="chevron-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection