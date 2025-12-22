<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from techzaa.in/larkon/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 01 Nov 2025 10:41:49 GMT -->

<head>
    <!-- Title Meta -->
       <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
    <meta charset="utf-8" />
    <title>Dashboard | Larkon - Responsive Admin Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="A fully responsive premium admin dashboard template" />
    <meta name="author" content="Techzaa" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('storage/logo1.png')}}" />

    <!-- Vendor css (Require in all Page) -->
    <link href="{{ asset('admin/assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css')}}" />

    <!-- Icons css (Require in all Page) -->
    <link href="{{ asset('admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css (Require in all Page) -->
    <link href="{{ asset('admin/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Theme Config js (Require in all Page) -->
    <script src="{{ asset('admin/assets/js/config.js') }}"></script>
</head>

<body>
    <!-- START Wrapper -->
    <div class="wrapper">
        <!-- ========== Topbar Start ========== -->
        <header class="topbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="d-flex align-items-center">
                        <!-- Menu Toggle Button -->
                        <div class="topbar-item">
                            <button type="button" class="button-toggle-menu me-2">
                                <iconify-icon icon="solar:hamburger-menu-broken"
                                    class="fs-24 align-middle"></iconify-icon>
                            </button>
                        </div>

                        <!-- Menu Toggle Button -->
                        <div class="topbar-item">
                            <h4 class="fw-bold topbar-button pe-none text-uppercase mb-0">
                                Xin chào!
                            </h4>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-1">
                        <!-- Theme Color (Light/Dark) -->
                        <div class="topbar-item">
                            <button type="button" class="topbar-button" id="light-dark-mode">
                                <iconify-icon icon="solar:moon-bold-duotone" class="fs-24 align-middle"></iconify-icon>
                            </button>
                        </div>

                        <!-- Notification -->
                        <div class="dropdown topbar-item">
                            <button type="button" class="topbar-button position-relative"
                                id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <iconify-icon icon="solar:bell-bing-bold-duotone"
                                    class="fs-24 align-middle"></iconify-icon>
                                <span
                                    class="position-absolute topbar-badge fs-10 translate-middle badge bg-danger rounded-pill">0<span
                                        class="visually-hidden">unread messages</span></span>
                            </button>
                            <div class="dropdown-menu py-0 dropdown-lg dropdown-menu-end"
                                aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0 fs-16 fw-semibold">Notifications</h6>
                                        </div>
                                        <div class="col-auto">
                                            <a href="javascript: void(0);" class="text-dark text-decoration-underline">
                                                <small>Clear All</small>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div data-simplebar style="max-height: 280px">
                                    <!-- Item -->
                                    <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom text-wrap">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('admin/assets/images/users/avatar-1.jpg') }}"
                                                    class="img-fluid me-2 avatar-sm rounded-circle" alt="avatar-1" />
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-0">
                                                    <span class="fw-medium">Josephine Thompson </span>commented on admin
                                                    panel
                                                    <span>" Wow 😍! this admin looks good and awesome
                                                        design"</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- Item -->
                                    <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-sm me-2">
                                                    <span
                                                        class="avatar-title bg-soft-info text-info fs-20 rounded-circle">
                                                        D
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-0 fw-semibold">Donoghue Susan</p>
                                                <p class="mb-0 text-wrap">
                                                    Hi, How are you? What about our next meeting
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- Item -->
                                    <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('admin/assets/images/users/avatar-3.jpg') }}"
                                                    class="img-fluid me-2 avatar-sm rounded-circle" alt="avatar-3" />
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-0 fw-semibold">Jacob Gines</p>
                                                <p class="mb-0 text-wrap">
                                                    Answered to your comment on the cash flow forecast's
                                                    graph 🔔.
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- Item -->
                                    <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-sm me-2">
                                                    <span
                                                        class="avatar-title bg-soft-warning text-warning fs-20 rounded-circle">
                                                        <iconify-icon
                                                            icon="iconamoon:comment-dots-duotone"></iconify-icon>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-0 fw-semibold text-wrap">
                                                    You have received <b>20</b> new messages in the
                                                    conversation
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- Item -->
                                    <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('admin/assets/images/users/avatar-5.jpg') }}"
                                                    class="img-fluid me-2 avatar-sm rounded-circle" alt="avatar-5" />
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-0 fw-semibold">Shawn Bunch</p>
                                                <p class="mb-0 text-wrap">Commented on Admin</p>
                                            </div>
                                        </div>
                                    </a>
                                </div> --}}
                                <div class="text-center py-3">
                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm">View All Notification
                                        <i class="bx bx-right-arrow-alt ms-1"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Theme Setting -->
                        <div class="topbar-item d-none d-md-flex">
                            <button type="button" class="topbar-button" id="theme-settings-btn"
                                data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas"
                                aria-controls="theme-settings-offcanvas">
                                <iconify-icon icon="solar:settings-bold-duotone"
                                    class="fs-24 align-middle"></iconify-icon>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </header>



        <!-- Right Sidebar (Theme Settings) -->
        <div>
            <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-settings-offcanvas">
                <div class="d-flex align-items-center bg-primary p-3 offcanvas-header">
                    <h5 class="text-white m-0">Cài đặt</h5>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body p-0">
                    <div data-simplebar class="h-100">
                        <div class="p-3 settings-bar">
                            <div>
                                <h5 class="mb-3 font-16 fw-semibold">Màu nền</h5>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-bs-theme"
                                        id="layout-color-light" value="light" />
                                    <label class="form-check-label" for="layout-color-light">Sáng</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-bs-theme"
                                        id="layout-color-dark" value="dark" />
                                    <label class="form-check-label" for="layout-color-dark">Tối</label>
                                </div>
                            </div>

                            <div>
                                <h5 class="my-3 font-16 fw-semibold">Màu thanh trên</h5>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-topbar-color"
                                        id="topbar-color-light" value="light" />
                                    <label class="form-check-label" for="topbar-color-light">Sáng</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-topbar-color"
                                        id="topbar-color-dark" value="dark" />
                                    <label class="form-check-label" for="topbar-color-dark">Tối</label>
                                </div>
                            </div>

                            <div>
                                <h5 class="my-3 font-16 fw-semibold">Màu Menu</h5>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-color"
                                        id="leftbar-color-light" value="light" />
                                    <label class="form-check-label" for="leftbar-color-light">
                                        Sáng
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-color"
                                        id="leftbar-color-dark" value="dark" />
                                    <label class="form-check-label" for="leftbar-color-dark">
                                        Tối
                                    </label>
                                </div>
                            </div>

                            <div>
                                <h5 class="my-3 font-16 fw-semibold">Kích thước thanh bên</h5>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-size-default" value="default" />
                                    <label class="form-check-label" for="leftbar-size-default">
                                        Mặc định
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-size-small" value="condensed" />
                                    <label class="form-check-label" for="leftbar-size-small">
                                        Thu gọn
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-hidden" value="hidden" />
                                    <label class="form-check-label" for="leftbar-hidden">
                                        Ẩn
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-size-small-hover-active" value="sm-hover-active" />
                                    <label class="form-check-label" for="leftbar-size-small-hover-active">
                                        Hiển thị nhỏ khi rê chuột
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-size-small-hover" value="sm-hover" />
                                    <label class="form-check-label" for="leftbar-size-small-hover">
                                        Hiển thị nhỏ khi rê chuột
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="offcanvas-footer border-top p-3 text-center">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-danger w-100" id="reset-layout">
                                Lưu
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ========== Topbar End ========== -->

        <!-- ========== App Menu Start ========== -->
        <div class="main-nav">
            <!-- Sidebar Logo -->
            <div class="logo-box">
                <a href="index.html" class="logo-dark">
                    <img src="{{ asset('storage/logo2.png')}}" class="logo-sm" alt="logo sm" />
                    <img src="{{ asset('storage/logo2.png')}}" class="logo-lg" alt="logo dark" />
                </a>

                <a href="index.html" class="logo-light">
                    <img src="{{ asset('storage/logo2.png')}}" class="logo-sm" alt="logo sm" />
                    <img src="{{ asset('storage/logo2.png')}}" class="logo-lg" alt="logo light" />
                </a>
            </div>

            <!-- Menu Toggle Button (sm-hover) -->
            <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
                <iconify-icon icon="solar:double-alt-arrow-right-bold-duotone"
                    class="button-sm-hover-icon"></iconify-icon>
            </button>

            <div class="scrollbar" data-simplebar>
                <ul class="navbar-nav" id="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:widget-5-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Thống Kê </span>
                        </a>
                    </li>

                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.categories.list') }}" role="button"
                            aria-expanded="false" aria-controls="sidebarCategory">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:clipboard-list-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Danh mục </span>
                        </a>

                    </li>
                     <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.brands.index') }}" 
                             aria-expanded="false" >
                            <span class="nav-icon">
                                <iconify-icon icon="solar:leaf-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Thương hiệu </span>
                        </a>
                        
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.attributes.list') }}" role="button"
                            aria-expanded="false" aria-controls="sidebarAttributes">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:confetti-minimalistic-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Thuộc tính </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-arrow" href="#sidebarProducts" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarProducts">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:t-shirt-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Sản phẩm </span>
                        </a>
                        <div class="collapse" id="sidebarProducts">
                            <ul class="nav sub-navbar-nav">
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="{{ route('admin.products.list') }}">Danh sách</a>
                                </li>

                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="{{ route('admin.products.create') }}">Thêm</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    
                    

                    {{-- <li class="nav-item">
                        <a class="nav-link " href="#sidebarInventory" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarInventory">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:box-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Hoàn trả </span>
                        </a>
                        
                    </li> --}}

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.orders.list') }}"
                            role="button" aria-expanded="false" aria-controls="sidebarOrders">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:bag-smile-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Đơn hàng </span>
                        </a>
                        
                    </li>


                    

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.list') }}" role="button">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Người dùng </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('admin.posts.index') }}"
                            role="button"
                            aria-expanded="false"
                            aria-controls="sidebarPosts"
                        >
                            <span class="nav-icon">
                                <iconify-icon icon="solar:document-add-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Bài viết </span>
                        </a>
                    </li>

                    {{-- <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('admin.comments.list') }}"
                            role="button"
                            aria-expanded="false"
                            aria-controls="sidebarComments"
                        >
                            <span class="nav-icon">
                            <iconify-icon icon="solar:chat-dots-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Bình luận </span>
                        </a>
                        </li> --}}

                        <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('admin.reviews.index') }}"
                            role="button"
                            aria-expanded="false"
                            aria-controls="sidebarReviews"
                        >
                            <span class="nav-icon">
                            <iconify-icon icon="solar:star-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Đánh giá </span>
                        </a>
                        </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.vouchers.index') }}"
                            role="button" aria-expanded="false" aria-controls="sidebarInvoice">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Mã giảm giá </span>
                        </a>
                        
                    </li>

                    {{-- <li class="nav-item">
                        <a class="nav-link" href="settings.html">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:settings-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Settings </span>
                        </a>
                    </li> --}}
                   

                    {{-- <li class="menu-title mt-2">Users</li>
                    <li class="nav-item">
                        <a class="nav-link" href="apps-chat.html">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:chat-round-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Chat </span>
                        </a>
                    </li> --}}


            </div>
        </div>
        <!-- ========== App Menu End ========== -->

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        @yield('content')
        <!-- ==================================================== -->
        <!-- End Page Content -->
        <!-- ==================================================== -->
    </div>
    <!-- END Wrapper -->

    <!-- Vendor Javascript (Require in all Page) -->
    <script src="{{ asset('admin/assets/js/vendor.js') }}"></script>

    <!-- App Javascript (Require in all Page) -->
    <script src="{{ asset('admin/assets/js/app.js') }}"></script>

    <!-- Vector Map Js -->
    <script src="{{ asset('admin/assets/vendor/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/jsvectormap/maps/world.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

<script>
    lightbox.option({
        fadeDuration: 200,
        imageFadeDuration: 200,
        resizeDuration: 200,
        wrapAround: true
    });
</script>

    @stack('scripts')
</body>


</html>
