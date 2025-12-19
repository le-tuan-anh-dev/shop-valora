<header> 
    <div class="top_header"> 
        <p>
            Mã giảm giá miễn phí: Giảm giá mùa hè cho các mặt hàng được chọn Sử dụng:
            <span>26 sản phẩm </span>
            <a href="#"> MUA NGAY</a>
        </p>
    </div>
    
    <div class="custom-container container header-1">
        <div class="row"> 
            <!-- Mobile Menu -->
            <div class="col-12 p-0"> 
                <div class="mobile-fix-option"> 
                    <ul> 
                        <li>
                            <a href="{{ route('home') }}">
                                <i class="iconsax"></i>Trang chủ
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('cart.index') }}">
                                <i class="iconsax" data-icon="basket-2"></i>Giỏ hàng
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="iconsax" data-icon="heart"></i>Sản phẩm yêu thích
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Navigation -->
            <div class="col-12">
                <div class="main-menu">     
                    <a class="brand-logo" href="{{ route('home') }}"> 
                        <img class="img-fluid for-light"  src="{{ asset('storage/logo1.png')}}" alt="logo"/>
                        <img class="img-fluid for-dark" src="{{ asset('client/assets/images/logo/logo-white-1.png')}}" alt="logo"/>
                    </a>
                    
                    <nav id="main-nav">
                        <ul class="nav-menu sm-horizontal theme-scrollbar" id="sm-horizontal">
                            <li class="mobile-back" id="mobile-back">
                                Back<i class="fa-solid fa-angle-right ps-2"></i>
                            </li>
                            <li><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                            <li><a class="nav-link" href="{{ route('shop.index') }}">Cửa hàng</a></li>
                            <li><a class="nav-link" href="{{ route('posts.index') }}">Bài viết</a></li>
                            <li><a class="nav-link" href="/lienhe">Liên hệ</a></li>
                        </ul>
                    </nav>
                    
                    <div class="sub_header">
                        <div class="toggle-nav" id="toggle-nav">
                            <i class="fa-solid fa-bars sidebar-bar"></i>
                        </div>

                        <ul class="justify-content-end">
                            {{-- Search --}}
                            <li> 
                                <button data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </li>

                            {{-- Wishlist icon --}}
                            <li>
                                <a href="#">
                                    <i class="fa-regular fa-heart"></i>
                                    <span class="cart_qty_cls">2</span>
                                </a>
                            </li>

                            {{-- User --}}
                            @if(Auth::check())
                                <!-- Nếu đã đăng nhập -->
                                <li class="onhover-div">
                                    {{-- BẤM AVATAR / TÊN => VÀO DASHBOARD --}}
                                    <a href="{{ route('client.dashboard') }}">
                                        <i class="fa-solid fa-user"></i> {{ Auth::user()->name }}
                                    </a>

                                    <div class="onhover-show-div user"> 
                                        <ul> 
                                            <li>
                                                <a href="{{ route('client.dashboard') }}">Dashboard</a>
                                            </li>
                                            <li>
                                                <form action="{{ route('logout') }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-logout">Đăng xuất</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @else
                                <!-- Nếu chưa đăng nhập -->
                                <li class="onhover-div">
                                    <a href="#">
                                        <i class="fa-solid fa-user"></i>
                                    </a>
                                    <div class="onhover-show-div user"> 
                                        <ul> 
                                            <li><a href="{{ route('login.show') }}">Đăng nhập</a></li>
                                            <li><a href="{{ route('register.show') }}">Đăng ký</a></li>
                                        </ul>
                                    </div>
                                </li>
                            @endif

                            {{-- Cart --}}
                            <li class="onhover-div shopping-cart"> 
                                <a class="p-0" href="{{ route('cart.index') }}" data-bs-target="#offcanvasRight">
                                    <div class="shoping-prize">
                                        <i class="fa-solid fa-cart-shopping pe-2"></i>
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