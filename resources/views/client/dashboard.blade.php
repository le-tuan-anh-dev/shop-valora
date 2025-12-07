{{-- resources/views/client/dashboard.blade.php --}}
@extends('client.layouts.master')

@section('title', 'Katie - Dashboard')

@section('content')
    <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>Của tôi</h4>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-end">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>-
                            <li class=" "><a href="{{ route('client.dashboard') }}">Của tôi</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-b-space pt-0">
        <div class="custom-container container user-dashboard-section">
            <div class="row">
                {{-- Sidebar trái --}}
                <div class="col-xl-3 col-lg-4">
                    <div class="left-dashboard-show">
                        <button class="btn btn_black sm rounded bg-primary">Show Menu</button>
                    </div>
                    <div class="dashboard-left-sidebar sticky">
                        <div class="profile-box">
                            <div class="profile-bg-img"></div>
                            <div class="dashboard-left-sidebar-close"><i class="fa-solid fa-xmark"></i></div>
                            <div class="profile-contain">
                                @php
                                    $avatar = $user->image
                                        ? asset('storage/' . $user->image)
                                        : asset('client/assets/images/user/12.jpg');
                                @endphp
                                <div class="profile-image">
                                    <img class="img-fluid" src="{{ $avatar }}" alt="Avatar">
                                </div>
                                <div class="profile-name">
                                    <h4>{{ $user->name }}</h4>
                                    <h6>{{ $user->email }}</h6>
                                    <span data-bs-toggle="modal" data-bs-target="#edit-profile-modal" title="Chỉnh sửa"
                                        tabindex="0">
                                        Edit Profile
                                    </span>
                                </div>
                            </div>
                        </div>

                        <ul class="nav flex-column nav-pills dashboard-tab" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <li>
                                <button class="nav-link active" id="dashboard-tab" data-bs-toggle="pill"
                                    data-bs-target="#dashboard" role="tab" aria-controls="dashboard"
                                    aria-selected="true">
                                    <i class="iconsax" data-icon="home-1"></i> Trang tổng quan
                                </button>
                            </li>
                            <li>
                                <button class="nav-link" id="notifications-tab" data-bs-toggle="pill"
                                    data-bs-target="#notifications" role="tab" aria-controls="notifications"
                                    aria-selected="false">
                                    <i class="iconsax" data-icon="lamp-2"></i>Thông báo
                                </button>
                            </li>
                            <li>
                                <button class="nav-link" id="order-tab" data-bs-toggle="pill" data-bs-target="#order"
                                    role="tab" aria-controls="order" aria-selected="false">
                                    <i class="iconsax" data-icon="receipt-square"></i>Đơn hàng
                                </button>
                            </li>
                            <li>
                                <button class="nav-link" id="wishlist-tab" data-bs-toggle="pill" data-bs-target="#wishlist"
                                    role="tab" aria-controls="wishlist" aria-selected="false">
                                    <i class="iconsax" data-icon="heart"></i>Danh sách yêu thích
                                </button>
                            </li>
                            <li>
                                <button class="nav-link" id="address-tab" data-bs-toggle="pill" data-bs-target="#address"
                                    role="tab" aria-controls="address" aria-selected="false">
                                    <i class="iconsax" data-icon="cue-cards"></i>Địa Chỉ
                                </button>
                            </li>
                        </ul>

                        <div class="logout-button">
                            <a class="btn btn_black sm" data-bs-toggle="modal" data-bs-target="#Confirmation-modal"
                                title="Logout" tabindex="0">
                                <i class="iconsax me-1" data-icon="logout-1"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Nội dung phải --}}
                <div class="col-xl-9 col-lg-8">
                    {{-- Flash messages --}}
                    @if (session('success'))
                        <div class="alert alert-success mt-2">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger mt-2">{{ session('error') }}</div>
                    @endif

                    <div class="tab-content" id="v-pills-tabContent">

                        {{-- Dashboard Tab --}}
                        <div class="tab-pane fade show active" id="dashboard" role="tabpanel"
                            aria-labelledby="dashboard-tab">
                            <div class="dashboard-right-box">
                                <div class="my-dashboard-tab">
                                    <div class="sidebar-title">
                                        <div class="loader-line"></div>
                                        <h4>My Dashboard</h4>
                                    </div>

                                    <div class="dashboard-user-name">
                                        <h6>Hello, <b>{{ $user->name }}</b></h6>
                                        <p>Từ trang Dashboard, bạn có thể xem nhanh lịch sử đơn hàng, cập nhật thông tin cá
                                            nhân và quản lý địa chỉ giao hàng.</p>

                                        <div class="mt-3">
                                            <a href="{{ route('orders.index') }}" class="btn btn_black sm me-2">
                                                Xem tất cả đơn hàng
                                            </a>
                                        </div>
                                    </div>

                                    <div class="total-box">
                                        <div class="row gy-4">
                                            <div class="col-xl-4">
                                                <div class="totle-contain">
                                                    <div class="wallet-point">
                                                        <img src="{{ asset('client/assets/images/svg-icon/wallet.svg') }}"
                                                            alt="">
                                                        <img class="img-1"
                                                            src="{{ asset('client/assets/images/svg-icon/wallet.svg') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="totle-detail">
                                                        <h6>Total Spent</h6>
                                                        <h4>${{ number_format($totalSpent, 2) }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="totle-contain">
                                                    <div class="wallet-point">
                                                        <img src="{{ asset('client/assets/images/svg-icon/coin.svg') }}"
                                                            alt="">
                                                        <img class="img-1"
                                                            src="{{ asset('client/assets/images/svg-icon/coin.svg') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="totle-detail">
                                                        <h6>Total Orders</h6>
                                                        <h4>{{ $totalOrders }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="totle-contain">
                                                    <div class="wallet-point">
                                                        <img src="{{ asset('client/assets/images/svg-icon/order.svg') }}"
                                                            alt="">
                                                        <img class="img-1"
                                                            src="{{ asset('client/assets/images/svg-icon/order.svg') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="totle-detail">
                                                        <h6>Balance</h6>
                                                        <h4>${{ number_format(0, 2) }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="profile-about">
                                        <div class="row">
                                            <div class="col-xl-7">
                                                <div class="sidebar-title">
                                                    <div class="loader-line"></div>
                                                    <h5>Profile Information</h5>
                                                </div>
                                                <ul class="profile-information">
                                                    <li>
                                                        <h6>Name :</h6>
                                                        <p>{{ $user->name }}</p>
                                                    </li>
                                                    <li>
                                                        <h6>Phone:</h6>
                                                        <p>{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                                                    </li>
                                                    <li>
                                                        <h6>Address:</h6>
                                                        <p>
                                                            @if ($defaultAddress)
                                                                {{ $defaultAddress->name }} -
                                                                {{ $defaultAddress->phone }}<br>
                                                                {{ $defaultAddress->address }}
                                                            @else
                                                                Chưa có địa chỉ mặc định
                                                            @endif
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-xl-5">
                                                <div class="profile-image d-none d-xl-block">
                                                    <img class="img-fluid"
                                                        src="{{ asset('client/assets/images/other-img/dashboard.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- Notifications Tab --}}
                        <div class="tab-pane fade" id="notifications" role="tabpanel"
                            aria-labelledby="notifications-tab">
                            <div class="dashboard-right-box">
                                <div class="notification-tab">
                                    <div class="sidebar-title">
                                        <div class="loader-line"></div>
                                        <h4>Notifications</h4>
                                    </div>
                                    <ul class="notification-body">
                                        @forelse($notifications as $notification)
                                            @php $data = $notification->data; @endphp
                                            <li>
                                                <div class="user-img">
                                                    <i class="fa-solid fa-bell"></i>
                                                </div>
                                                <div class="user-contant">
                                                    <h6>
                                                        {{ $data['message'] ?? 'Thông báo đơn hàng' }}
                                                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                                                    </h6>
                                                    <p>
                                                        Mã đơn: <strong>{{ $data['order_number'] ?? 'N/A' }}</strong><br>
                                                        Trạng thái:
                                                        <strong>{{ $data['status_label'] ?? ($data['status'] ?? 'Không xác định') }}</strong>
                                                    </p>
                                                </div>
                                            </li>
                                        @empty
                                            <li>
                                                <p>Bạn chưa có thông báo nào.</p>
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Order Tab --}}
                        <div class="tab-pane fade" id="order" role="tabpanel" aria-labelledby="order-tab">
                            <div class="dashboard-right-box">
                                <div class="order">
                                    <div class="sidebar-title">
                                        <div class="loader-line"></div>
                                        <h4>
Lịch sử đơn hàng của tôi</h4>
                                    </div>

                                    <div class="row gy-4">
                                        @forelse($orders as $order)
                                            @php
                                                $firstItem = $order->items->first();
                                                $statusMap = [
                                                    'pending' => 'Đang chờ',
                                                    'confirmed' => 'Đã xác nhận',
                                                    'awaiting_pickup' => 'Chờ lấy hàng',
                                                    'shipping' => 'Đang giao',
                                                    'delivered' => 'Đã giao',
                                                    'completed' => 'Hoàn thành',
                                                    'cancelled_by_customer' => 'Khách đã hủy',
                                                    'cancelled_by_admin' => 'Shop đã hủy',
                                                    'delivery_failed' => 'Giao hàng thất bại',
                                                ];
                                                $displayStatus = $statusMap[$order->status] ?? $order->status;
                                            @endphp

                                            <div class="col-12">
                                                <div class="order-box">
                                                    <div class="order-container">
                                                        <div class="order-icon">
                                                            <i class="iconsax" data-icon="box"></i>
                                                            @if (in_array($order->status, ['delivered', 'completed']))
                                                                <div class="couplet"><i class="fa-solid fa-check"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="order-detail">
                                                            <h5>{{ $displayStatus }}</h5>
                                                            <p>
                                                                Mã đơn: <b>{{ $order->order_number }}</b><br>
                                                                Ngày đặt: {{ $order->created_at?->format('d/m/Y H:i') }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    @if ($firstItem)
                                                        <div class="product-order-detail">
                                                            <div class="product-box">
                                                                @php
                                                                    $firstItemImage = $firstItem->product_image
                                                                        ? asset(
                                                                            'storage/' .
                                                                                ltrim($firstItem->product_image, '/'),
                                                                        )
                                                                        : asset(
                                                                            'client/assets/images/notification/1.jpg',
                                                                        );
                                                                @endphp

                                                                <a href="{{ route('orders.show', $order->id) }}">
                                                                    <img src="{{ $firstItemImage }}" alt="">
                                                                </a>
                                                                <div class="order-wrap">
                                                                    <h5>{{ $firstItem->product_name }}</h5>

                                                                    {{-- Thương hiệu --}}
                                                                    @if($firstItem->product && $firstItem->product->brand)
                                                                        <div class="text-muted small mb-1">
                                                                            Thương hiệu:
                                                                            <strong>{{ $firstItem->product->brand->name }}</strong>
                                                                        </div>
                                                                    @endif

                                                                    {{-- ========== BIẾN THỂ / THUỘC TÍNH ========== --}}
                                                                    @php
                                                                        // 1. Nếu variant_name đã được lưu trong order_items thì ưu tiên dùng
                                                                        $variantLabel = $firstItem->variant_name;

                                                                        // 2. Nếu variant_name null => build từ quan hệ variant.attributeValues.attribute
                                                                        if (
                                                                            !$variantLabel &&
                                                                            $firstItem->variant &&
                                                                            $firstItem->variant->attributeValues?->count()
                                                                        ) {
                                                                            $parts = [];

                                                                            foreach ($firstItem->variant->attributeValues as $attrValue) {
                                                                                $attrName  = $attrValue->attribute->name ?? null; // "Màu sắc", "Kích cỡ"
                                                                                $valueName = $attrValue->value;                   // "Đỏ", "M", ...

                                                                                if ($attrName) {
                                                                                    $parts[] = $attrName . ': ' . $valueName;
                                                                                } else {
                                                                                    $parts[] = $valueName;
                                                                                }
                                                                            }

                                                                            // Ví dụ: "Màu sắc: Đỏ, Kích cỡ: M"
                                                                            $variantLabel = implode(', ', $parts);
                                                                        }
                                                                    @endphp

                                                                    @if($variantLabel)
                                                                        <div class="text-muted small mb-1">
                                                                            Phân loại: {{ $variantLabel }}
                                                                        </div>
                                                                    @endif
                                                                    {{-- ======== HẾT BIẾN THỂ / THUỘC TÍNH ======== --}}

                                                                    <p>{{ \Illuminate\Support\Str::limit($firstItem->product_description, 120) }}
                                                                    </p>
                                                                    <ul>
                                                                        <li>
                                                                            <p>Giá :</p>
                                                                            <span>{{ number_format($firstItem->unit_price, 0, ',', '.') }}
                                                                                ₫</span>
                                                                        </li>
                                                                        <li>
                                                                            <p>Số lượng :</p>
                                                                            <span>{{ $firstItem->quantity }}</span>
                                                                        </li>
                                                                        <li>
                                                                            <p>Tổng Tiền :</p>
                                                                            <span>{{ number_format($order->total_amount, 0, ',', '.') }}
                                                                                ₫</span>
                                                                        </li>
                                                                    </ul>
                                                                    @if ($order->items->count() > 1)
                                                                        <small>+ {{ $order->items->count() - 1 }} sản phẩm
                                                                            khác</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="mt-2 d-flex gap-2">
                                                        <a href="{{ route('orders.show', $order->id) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            Xem chi tiết
                                                        </a>

                                                        @if ($order->status === 'pending')
                                                            <form action="{{ route('orders.cancel', $order->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?')">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger">
                                                                    Hủy đơn
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p>Bạn chưa có đơn hàng nào.</p>
                                            </div>
                                        @endforelse
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- Wishlist Tab (dùng dữ liệu thật) --}}
                        <div class="tab-pane fade" id="wishlist" role="tabpanel" aria-labelledby="wishlist-tab">
                            <div class="dashboard-right-box">
                                <div class="wishlist-box ratio1_3">
                                    <div class="sidebar-title">
                                        <div class="loader-line"></div>
                                        <h4>Wishlist</h4>
                                    </div>

                                    <div class="row-cols-md-3 row-cols-2 grid-section view-option row gy-4 g-xl-4">

                                        @forelse($wishlistProducts as $product)
                                            <div class="col">
                                                <div class="product-box-3 product-wishlist">
                                                    <div class="img-wrapper">
                                                        {{-- CHỈ CÒN NÚT THÙNG RÁC Ở GÓC ẢNH --}}
                                                        <div class="label-block">
                                                            <form action="{{ route('wishlist.remove', $product->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Xóa sản phẩm này khỏi danh sách yêu thích?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="label-2 wishlist-icon delete-button"
                                                                    title="Xóa khỏi danh sách yêu thích"
                                                                    style="border:none;background:transparent;">
                                                                    {{-- Dùng icon FontAwesome thùng rác --}}
                                                                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                                                </button>
                                                            </form>
                                                        </div>

                                                        <div class="product-image">
                                                            @php
                                                                $image = $product->image_main
                                                                    ? asset(
                                                                        'storage/' . ltrim($product->image_main, '/'),
                                                                    )
                                                                    : asset(
                                                                        'client/assets/images/product/product-3/1.jpg',
                                                                    );
                                                            @endphp

                                                            <a class="pro-first"
                                                                href="{{ route('products.detail', $product->id) }}">
                                                                <img class="bg-img" src="{{ $image }}"
                                                                    alt="{{ $product->name }}">
                                                            </a>
                                                            <a class="pro-sec"
                                                                href="{{ route('products.detail', $product->id) }}">
                                                                <img class="bg-img" src="{{ $image }}"
                                                                    alt="{{ $product->name }}">
                                                            </a>
                                                        </div>

                                                        {{-- (Tùy chọn) Nút thêm vào giỏ --}}
                                                        <div class="cart-info-icon">
                                                            <form action="{{ route('cart.add', $product->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit" title="Thêm vào giỏ"
                                                                    tabindex="0"
                                                                    style="border:none;background:transparent;">
                                                                    <i class="iconsax" data-icon="basket-2"
                                                                        aria-hidden="true"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    {{-- Product Detail --}}
                                                    <div class="product-detail">

                                                        {{-- NÚT XEM CHI TIẾT TO, RÕ --}}
                                                        <div class="add-button mb-2">
                                                            <a href="{{ route('products.detail', $product->id) }}"
                                                                class="btn btn_black sm w-100 text-center">
                                                                <i class="fa-solid fa-eye me-1"></i> Xem chi tiết
                                                            </a>
                                                        </div>

                                                        <a href="{{ route('products.detail', $product->id) }}">
                                                            <h5>{{ \Illuminate\Support\Str::limit($product->name, 40) }}
                                                            </h5>
                                                        </a>

                                                        @php
                                                            $hasDiscount =
                                                                $product->discount_price &&
                                                                $product->discount_price < $product->base_price;
                                                            $displayPrice = $hasDiscount
                                                                ? $product->discount_price
                                                                : $product->base_price;
                                                        @endphp

                                                        <p>
                                                            {{ number_format($displayPrice, 0, ',', '.') }}đ

                                                            @if ($hasDiscount)
                                                                <del>{{ number_format($product->base_price, 0, ',', '.') }}đ</del>
                                                                @php
                                                                    $discountPercent = round(
                                                                        (($product->base_price -
                                                                            $product->discount_price) /
                                                                            $product->base_price) *
                                                                            100,
                                                                    );
                                                                @endphp
                                                                <span>-{{ $discountPercent }}%</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p>Bạn chưa có sản phẩm yêu thích nào.</p>
                                            </div>
                                        @endforelse

                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Address Tab --}}
                        <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                            <div class="dashboard-right-box">
                                <div class="address-tab">
                                    <div class="loader-line"></div>
                                    <h4>My Address Details</h4>
                                </div>

                                <div class="row gy-3">
                                    @forelse($addresses as $address)
                                        <div class="col-xxl-4 col-md-6">
                                            <div class="address-option">

                                                {{-- Form cập nhật / đặt mặc định --}}
                                                <form action="{{ route('checkout.update-address', $address->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <span class="delivery-address-box">

                                                        <span class="form-check">
                                                            <input class="custom-radio" type="radio" name="is_default"
                                                                value="1" {{ $address->is_default ? 'checked' : '' }}
                                                                onclick="this.form.submit()">
                                                            <label class="form-check-label ms-1">Đặt làm mặc định</label>
                                                        </span>

                                                        <span class="address-detail">
                                                            <span class="address">
                                                                <span class="address-title">
                                                                    <input type="text" name="name"
                                                                        class="form-control form-control-sm"
                                                                        value="{{ old('name', $address->name) }}"
                                                                        placeholder="Nhập họ tên">
                                                                </span>
                                                            </span>

                                                            <span class="address">
                                                                <span class="address-home">
                                                                    <span class="address-tag">Address:</span>
                                                                    <textarea name="address" class="form-control form-control-sm" rows="2" placeholder="Địa chỉ chi tiết">{{ old('address', $address->address) }}</textarea>
                                                                </span>
                                                            </span>

                                                            <span class="address">
                                                                <span class="address-home">
                                                                    <span class="address-tag">Phone :</span>
                                                                    <input type="text" name="phone"
                                                                        class="form-control form-control-sm"
                                                                        value="{{ old('phone', $address->phone) }}">
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </span>

                                                    <span class="buttons mt-2 d-flex gap-2">
                                                        <button type="submit" class="btn btn_black sm">Lưu</button>
                                                    </span>
                                                </form>

                                                {{-- Form xóa --}}
                                                <form action="{{ route('checkout.delete-address', $address->id) }}"
                                                    method="POST" onsubmit="return confirm('Xóa địa chỉ này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn_outline sm mt-2">Xóa</button>
                                                </form>

                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p>Bạn chưa có địa chỉ nào.</p>
                                        </div>
                                    @endforelse
                                </div>

                                <hr>

                                <h5 class="mt-3 mb-2">Thêm địa chỉ mới</h5>
                                <form action="{{ route('checkout.store-address') }}" method="POST" class="row g-2">
                                    @csrf
                                    <div class="col-md-4">
                                        <label class="form-label">Tên Người đặt</label>
                                        <input type="text" name="name" class="form-control" placeholder="Họ tên"
                                            value="{{ old('name') }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="text" name="phone" class="form-control"
                                            placeholder="Số điện thoại" value="{{ old('phone') }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Mặc định</label><br>
                                        <input type="checkbox" name="is_default" value="1"> Đặt làm địa chỉ mặc định
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Địa chỉ chi tiết</label>
                                        <textarea name="address" class="form-control" rows="3" placeholder="Địa chỉ chi tiết" required>{{ old('address') }}</textarea>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn btn_black sm">+ Thêm địa chỉ</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                </div> {{-- end .tab-content --}}
            </div> {{-- end row --}}
        </div>
        </div>
    </section>

    {{-- Modal Edit Profile --}}
    <div class="modal fade" id="edit-profile-modal" tabindex="-1" aria-labelledby="editProfileLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('client.dashboard.update-profile') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileLabel">Cập nhật thông tin cá nhân</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Họ tên</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ảnh đại diện</label>
                            <input type="file" name="image" class="form-control">
                            @if ($user->image)
                                <small class="text-muted d-block mt-1">
                                    Ảnh hiện tại:
                                    <img src="{{ asset('storage/' . $user->image) }}" alt=""
                                        style="height:40px;border-radius:50%;">
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn_outline sm" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn_black sm">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Logout --}}
    <div class="modal fade" id="Confirmation-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận đăng xuất</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn đăng xuất không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn_outline sm" data-bs-dismiss="modal">Hủy</button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn_black sm">Đăng xuất</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection