<header> 
    <div class="top_header"> 
        <p>Free Coupne Code: Summer Sale On Selected items Use:<span>NEW 26</span>
            <a href="#"> SHOP NOW</a>
        </p>
    </div>
    
    <div class="custom-container container header-1">
        <div class="row"> 
            <!-- Mobile Menu -->
            <div class="col-12 p-0"> 
                <div class="mobile-fix-option"> 
                    <ul> 
                        <li><a href="{{ route('home') }}"><i class="iconsax" ></i>Home</a></li>
                       
                        
                        <a href="#" data-bs-toggle="modal" data-bs-target="#addtocart">
                            <i class="iconsax" data-icon="basket-2"></i>
                        </a>

                        <li><a href="#"><i class="iconsax" data-icon="heart"></i>My Wish</a></li>
                       
                    </ul>
                </div>
            </div>
            
            <!-- Main Navigation -->
            <div class="col-12">
                <div class="main-menu"> 
                    <a class="brand-logo" href="#"> 
                        <img class="img-fluid for-light" src="{{ asset('client/assets/images/logo/logo.png')}}" alt="logo"/>
                        <img class="img-fluid for-dark" src="{{ asset('client/assets/images/logo/logo-white-1.png')}}" alt="logo"/>
                    </a>
                    
                    <nav id="main-nav">
                        <ul class="nav-menu sm-horizontal theme-scrollbar" id="sm-horizontal">
                            <li class="mobile-back" id="mobile-back">
                                Back<i class="fa-solid fa-angle-right ps-2"></i>
                            </li>
                            <li><a class="nav-link" href="#">Home</a></li>
                            <li><a class="nav-link" href="#">Shop</a></li>
                            <li><a class="nav-link" href="#">About</a></li>
                            <li><a class="nav-link" href="#">Contact</a></li>
                        </ul>
                    </nav>
                    
                    <div class="sub_header">
                        <div class="toggle-nav" id="toggle-nav">
                            <i class="fa-solid fa-bars-staggered sidebar-bar"></i>
                        </div>
                        <ul class="justify-content-end">
                            <li> 
                                <button data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop">
                                    <i class="iconsax" data-icon="search-normal-2"></i>
                                </button>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="iconsax" data-icon="heart"></i>
                                    <span class="cart_qty_cls">2</span>
                                </a>
                            </li>
                            <li class="onhover-div">
                                <a href="#"><i class="iconsax" data-icon="user-2"></i></a>
                                <div class="onhover-show-div user"> 
                                    <ul> 
                                        <li><a href="#">Login</a></li>
                                        <li><a href="#">Register</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="onhover-div shopping-cart"> 
                                <a class="p-0" href="{{ route('cart.index') }}"  data-bs-target="#offcanvasRight">
                                    <div class="shoping-prize">
                                        <i class="iconsax pe-2" data-icon="basket-2"></i>0 items
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>