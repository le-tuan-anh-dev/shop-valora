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
                                        Sửa thông tin
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
                                <i class="iconsax me-1" data-icon="logout-1"></i> Đăng xuất
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Nội dung phải --}}
                <div class="col-xl-9 col-lg-8">
                    
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="alert alert-success position-fixed top-0 end-0 m-3 auto-hide-alert"
                            style="z-index: 9999;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger position-fixed top-0 end-0 m-3 auto-hide-alert"
                            style="z-index: 9999;">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="tab-content" id="v-pills-tabContent">

                        {{-- Dashboard Tab --}}
                        <div class="tab-pane fade show active" id="dashboard" role="tabpanel"
                            aria-labelledby="dashboard-tab">
                            <div class="dashboard-right-box">
                                <div class="my-dashboard-tab">
                                    <div class="sidebar-title">
                                        <div class="loader-line"></div>
                                        <h4>Của tôi</h4>
                                    </div>

                                    <div class="dashboard-user-name">
                                        <h6>Xin chào, <b>{{ $user->name }}</b></h6>
                                        <p>Từ trang này, bạn có thể xem nhanh lịch sử đơn hàng, cập nhật thông tin cá
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
                                                        <h6>Tổng tiền đã chi</h6>
                                                        <h4>{{ number_format($totalSpent) }}</h4>
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
                                                        <h6>Tổng đơn hàng</h6>
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
                                                        <h6>Số dư</h6>
                                                        <h4>{{ number_format(0) }}</h4>
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
                                                    <h5>Thông tin</h5>
                                                </div>
                                                <ul class="profile-information">
                                                    <li>
                                                        <h6>Tên :</h6>
                                                        <p>{{ $user->name }}</p>
                                                    </li>
                                                    <li>
                                                        <h6>Số điện thoai:</h6>
                                                        <p>{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                                                    </li>
                                                    <li>
                                                        <h6>Địa chỉ:</h6>
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
                                        <h4>Thông báo</h4>
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
                                        <h4>Lịch sử đơn hàng của tôi</h4>
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

                                                                    @if($firstItem->product && $firstItem->product->brand)
                                                                        <div class="text-muted small mb-1">
                                                                            Thương hiệu:
                                                                            <strong>{{ $firstItem->product->brand->name }}</strong>
                                                                        </div>
                                                                    @endif

                                                                    @php
                                                                        $variantLabel = $firstItem->variant_name;

                                                                        if (
                                                                            !$variantLabel &&
                                                                            $firstItem->variant &&
                                                                            $firstItem->variant->attributeValues?->count()
                                                                        ) {
                                                                            $parts = [];

                                                                            foreach ($firstItem->variant->attributeValues as $attrValue) {
                                                                                $attrName  = $attrValue->attribute->name ?? null;
                                                                                $valueName = $attrValue->value;

                                                                                if ($attrName) {
                                                                                    $parts[] = $attrName . ': ' . $valueName;
                                                                                } else {
                                                                                    $parts[] = $valueName;
                                                                                }
                                                                            }

                                                                            $variantLabel = implode(', ', $parts);
                                                                        }
                                                                    @endphp

                                                                    @if($variantLabel)
                                                                        <div class="text-muted small mb-1">
                                                                            Phân loại: {{ $variantLabel }}
                                                                        </div>
                                                                    @endif

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

                        {{-- Wishlist Tab--}}
                        <div class="tab-pane fade" id="wishlist" role="tabpanel" aria-labelledby="wishlist-tab">
                            <div class="dashboard-right-box">
                                <div class="wishlist-box ratio1_3">
                                    <div class="sidebar-title">
                                        <div class="loader-line"></div>
                                        <h4>Danh sách yêu thích</h4>
                                    </div>

                                    <div class="row-cols-md-3 row-cols-2 grid-section view-option row gy-4 g-xl-4">

                                        @forelse($wishlistProducts as $product)
                                            <div class="col">
                                                <div class="product-box">
                                                    <div class="img-wrapper">
                                                        @php
                                                            $hasDiscount = $product->discount_price && $product->discount_price < $product->base_price;
                                                        @endphp

                                                        @if($hasDiscount)
                                                            @php
                                                                $discountPercent = round((($product->base_price - $product->discount_price) / $product->base_price) * 100);
                                                            @endphp
                                                            <div class="label-block">
                                                                <img src="{{ asset('client/assets/images/product/2.png') }}" alt="label">
                                                                <span>Giảm <br>giá!</span>
                                                            </div>
                                                        @endif

                                                        
                                                        <div style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                                            <form action="{{ route('wishlist.remove', $product->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Xóa sản phẩm này khỏi danh sách yêu thích?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm"
                                                                    title="Xóa khỏi danh sách yêu thích"
                                                                    style="border:none;padding:6px 10px;border-radius:4px;cursor:pointer;">
                                                                    
                                                                    <i class="fas fa-trash" style="font-size:14px;"></i>
                                                                </button>
                                                            </form>
                                                        </div>

                                                        <div class="product-image">
                                                            <a href="{{ route('products.detail', $product->id) }}">
                                                                @php
                                                                    $image = $product->image_main
                                                                        ? asset('storage/' . ltrim($product->image_main, '/'))
                                                                        : asset('client/assets/images/product/product-4/1.jpg');
                                                                @endphp
                                                                <img class="bg-img" src="{{ $image }}"
                                                                    alt="{{ $product->name }}"
                                                                    onerror="this.src='{{ asset('client/assets/images/product/product-4/1.jpg') }}'">
                                                            </a>
                                                        </div>

                                                        <div class="cart-info-icon">
                                                            <a class="wishlist-icon" href="javascript:void(0)" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Thêm vào giỏ">
                                                                <i class="iconsax" data-icon="basket-2"></i>
                                                            </a>
                                                        </div>
                                                    </div>

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

                                                        <p>
                                                            @if($hasDiscount)
                                                                {{ number_format($product->discount_price, 0, ',', '.') }}₫ 
                                                                <del>{{ number_format($product->base_price, 0, ',', '.') }}₫</del>
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
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <h4 style="margin: 0; font-weight: 600;">Thông Tin Địa Chỉ Của Tôi</h4>
                                    </div>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addAddressModal" style="background-color: #c9a876; border: none; color: white;">
                                        <i class="fas fa-plus"></i> Thêm Địa Chỉ
                                    </button>
                                </div>

                                @if($addresses->count() > 0)
                                    <div class="row gy-4">
                                        @foreach($addresses as $address)
                                            <div class="col-lg-4 col-md-6">
                                                <div style="border: 1px solid #e9ecef; border-radius: 8px; padding: 25px; background: white; position: relative; height: 100%;">
                                                    
                                                    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 15px;">
                                                        <form action="{{ route('checkout.update-address', $address->id) }}" method="POST" style="display: flex; align-items: center; gap: 10px;">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="edit_name" value="{{ $address->name }}">
                                                            <input type="hidden" name="edit_phone" value="{{ $address->phone }}">
                                                            <input type="hidden" name="edit_province_id" value="{{ $address->province_id }}">
                                                            <input type="hidden" name="edit_district_id" value="{{ $address->district_id }}">
                                                            <input type="hidden" name="edit_ward_code" value="{{ $address->ward_code }}">
                                                            <input type="hidden" name="edit_address" value="{{ $address->address }}">
                                                            <input type="hidden" name="edit_is_default" value="1">
                                                            
                                                            <input class="form-check-input" type="radio" name="set_default" 
                                                                id="address_{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }}
                                                                onchange="this.closest('form').submit()"
                                                                style="cursor: pointer; margin: 0;">
                                                        </form>
                                                        @if($address->is_default)
                                                            <span class="badge bg-warning" style="background-color: #c9a876 !important;">Mặc định</span>
                                                        @endif
                                                    </div>

                                                    <h5 style="margin: 0 0 15px 0; font-weight: 600; font-size: 18px;">{{ $address->name }}</h5>

                                                    <div style="color: #666; font-size: 14px; line-height: 1.8; margin-bottom: 15px;">
                                                        <p style="margin: 0 0 8px 0;"><strong>Địa chỉ :</strong> {{ $address->address }}</p>
                                                        @if($address->ward)
                                                            <p style="margin: 0 0 8px 0;">{{ $address->ward->name }}, {{ $address->district->name }}, {{ $address->province->name }}</p>
                                                        @endif
                                                        <p style="margin: 0;"><strong>Số điện thoại:</strong> {{ $address->phone }}</p>
                                                    </div>

                                                    <div style="display: flex; gap: 10px; padding-top: 15px; border-top: 1px solid #e9ecef;">
                                                        <button type="button" class="btn btn-outline-warning flex-grow-1" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editAddressModal-{{ $address->id }}"
                                                            style="color: #c9a876; border-color: #c9a876;">
                                                            Sửa
                                                        </button>
                                                        <form action="{{ route('checkout.delete-address', $address->id) }}" method="POST" 
                                                            style="flex-grow: 1;" onsubmit="return confirm('Xóa địa chỉ này?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger w-100">
                                                                Xóa
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div style="text-align: center; padding: 60px 20px; background-color: #f8f9fa; border-radius: 8px;">
                                        <i class="fas fa-map-marker-alt" style="font-size: 48px; color: #ccc; margin-bottom: 15px; display: block;"></i>
                                        <p style="color: #999; margin: 0; font-size: 16px;">Bạn chưa có địa chỉ nào. Hãy thêm địa chỉ giao hàng đầu tiên!</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    
                        <!-- Modal Thêm Địa Chỉ Mới -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressLabel">Thêm Địa Chỉ Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form action="{{ route('checkout.store-address') }}" method="POST">
                @csrf
                <input type="hidden" name="form_type" value="add">
                <div class="modal-body">
                    <div class="row">
                        <!-- Cột trái -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tên địa chỉ <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                    placeholder="Vd: Nhà riêng, Văn phòng" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                    placeholder="10 chữ số" value="{{ old('phone') }}" maxlength="10">
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                    rows="4" placeholder="Nhập số nhà, tên đường...">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Cột phải -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                                <select name="province_id" class="form-control @error('province_id') is-invalid @enderror" id="add_province_id">
                                    <option value="">-- Chọn Tỉnh / Thành phố --</option>
                                    @foreach($provinces ?? [] as $province)
                                        <option value="{{ $province['ProvinceID'] }}" {{ old('province_id') == $province['ProvinceID'] ? 'selected' : '' }}>
                                            {{ $province['ProvinceName'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('province_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Quận / Huyện <span class="text-danger">*</span></label>
                                <select name="district_id" class="form-control @error('district_id') is-invalid @enderror" id="add_district_id">
                                    <option value="">-- Chọn Quận / Huyện --</option>
                                </select>
                                @error('district_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phường / Xã <span class="text-danger">*</span></label>
                                <select name="ward_code" class="form-control @error('ward_code') is-invalid @enderror" id="add_ward_code">
                                    <option value="">-- Chọn Phường / Xã --</option>
                                </select>
                                @error('ward_code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefault">
                                    <label class="form-check-label" for="isDefault">
                                        Đặt làm địa chỉ mặc định
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning" style="background-color: #c9a876; border: none; color: white;">
                        <i class="fas fa-save"></i> Thêm Địa Chỉ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Chỉnh Sửa Địa Chỉ -->
@foreach($addresses as $address)
    <div class="modal fade" id="editAddressModal-{{ $address->id }}" tabindex="-1" aria-labelledby="editAddressLabel-{{ $address->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAddressLabel-{{ $address->id }}">Chỉnh Sửa Địa Chỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <form action="{{ route('checkout.update-address', $address->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_type" value="edit">
                    <input type="hidden" name="edit_address_id" value="{{ $address->id }}">
                    <div class="modal-body">
                        <div class="row">
                            <!-- Cột trái -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tên địa chỉ <span class="text-danger">*</span></label>
                                    <input type="text" name="edit_name" class="form-control @error('edit_name') is-invalid @enderror" 
                                        value="{{ old('edit_name', $address->name) }}">
                                    @error('edit_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" name="edit_phone" class="form-control @error('edit_phone') is-invalid @enderror" 
                                        value="{{ old('edit_phone', $address->phone) }}" maxlength="10">
                                    @error('edit_phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                                    <textarea name="edit_address" class="form-control @error('edit_address') is-invalid @enderror" rows="4">{{ old('edit_address', $address->address) }}</textarea>
                                    @error('edit_address')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                                    <select name="edit_province_id" class="form-control @error('edit_province_id') is-invalid @enderror" 
                                        id="edit_province_{{ $address->id }}">
                                        <option value="">-- Chọn Tỉnh / Thành phố --</option>
                                        @foreach($provinces ?? [] as $province)
                                            <option value="{{ $province['ProvinceID'] }}" {{ old('edit_province_id', $address->province_id) == $province['ProvinceID'] ? 'selected' : '' }}>
                                                {{ $province['ProvinceName'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('edit_province_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Quận / Huyện <span class="text-danger">*</span></label>
                                    <select name="edit_district_id" class="form-control @error('edit_district_id') is-invalid @enderror" 
                                        id="edit_district_{{ $address->id }}">
                                        <option value="">-- Chọn Quận / Huyện --</option>
                                    </select>
                                    @error('edit_district_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phường / Xã <span class="text-danger">*</span></label>
                                    <select name="edit_ward_code" class="form-control @error('edit_ward_code') is-invalid @enderror" 
                                        id="edit_ward_{{ $address->id }}">
                                        <option value="">-- Chọn Phường / Xã --</option>
                                    </select>
                                    @error('edit_ward_code')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="edit_is_default" value="1" 
                                            id="editIsDefault_{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }}>
                                        <label class="form-check-label" for="editIsDefault_{{ $address->id }}">
                                            Đặt làm địa chỉ mặc định
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-warning" style="background-color: #c9a876; border: none; color: white;">
                            <i class="fas fa-save"></i> Lưu Thay Đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal Edit Profile --}}
    <div class="modal fade" id="edit-profile-modal" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <ul class="nav nav-tabs" id="profileTabs" role="tablist" style="border-bottom: 1px solid #dee2e6;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-content" type="button" role="tab" aria-controls="profile-content" aria-selected="true">
                            Thông tin cá nhân
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-content" type="button" role="tab" aria-controls="password-content" aria-selected="false">
                            Đổi mật khẩu
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="profileTabsContent">
                    <div class="tab-pane fade show active" id="profile-content" role="tabpanel" aria-labelledby="profile-tab">
                        <form action="{{ route('client.dashboard.update-profile') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Cập nhật thông tin cá nhân</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Họ tên</label>
                                    <input type="text" name="profile_name" class="form-control" value="{{ old('profile_name', $user->name) }}">
                                    @error('profile_name')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="profile_email" class="form-control" value="{{ old('profile_email', $user->email) }}">
                                    @error('profile_email')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" name="profile_phone" class="form-control" value="{{ old('profile_phone', $user->phone) }}">
                                    @error('profile_phone')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ảnh đại diện</label>
                                    <input type="file" name="profile_image" class="form-control" accept="image/*">
                                    @if ($user->image)
                                        <small class="text-muted d-block mt-1">
                                            Ảnh hiện tại:
                                            <img src="{{ asset('storage/' . $user->image) }}" alt="" style="height:40px;border-radius:50%;">
                                        </small>
                                    @endif
                                    @error('profile_image')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn_outline sm" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn_black sm">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="password-content" role="tabpanel" aria-labelledby="password-tab">
                        <form action="{{ route('client.dashboard.change-password') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Đổi mật khẩu</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu hiện tại</label>
                                    <input type="password" name="current_password" class="form-control">
                                    @error('current_password')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu mới</label>
                                    <input type="password" name="new_password" class="form-control">
                                    @error('new_password')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Xác nhận mật khẩu mới</label>
                                    <input type="password" name="new_password_confirmation" class="form-control">
                                    @error('new_password_confirmation')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn_outline sm" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn_black sm">Đổi mật khẩu</button>
                            </div>
                        </form>
                    </div>
                </div>
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

{{-- Thêm data attribute để JS biết có lỗi hay không --}}
<script>
    document.body.setAttribute('data-has-errors', '{{ $errors->any() ? "true" : "false" }}');
    document.body.setAttribute('data-edit-address-id', '{{ old("edit_address_id") }}');
</script>

{{-- Include file JS --}}
<script src="{{ asset('client/assets/js/dashboard-tabs.js') }}"></script>

{{-- Xử lý validation errors --}}
@if ($errors->any())
<script>
(function() {
    
    document.addEventListener('DOMContentLoaded', function() {
        var hasAddFormErrors = {{ $errors->has('name') || $errors->has('phone') || $errors->has('province_id') || $errors->has('district_id') || $errors->has('ward_code') || $errors->has('address') ? 'true' : 'false' }};
        var hasEditFormErrors = {{ $errors->has('edit_name') || $errors->has('edit_phone') || $errors->has('edit_province_id') || $errors->has('edit_district_id') || $errors->has('edit_ward_code') || $errors->has('edit_address') ? 'true' : 'false' }};
        var editAddressId = '{{ old("edit_address_id") }}';
        
        
        if (hasAddFormErrors || hasEditFormErrors) {
            // Chuyển tab
            setTimeout(function() {
                var addressTab = document.getElementById('address-tab');
                if (addressTab) {
                    addressTab.click();
                }
            }, 100);
            
            // Mở modal
            setTimeout(function() {
                var modalId = hasAddFormErrors ? 'addAddressModal' : ('editAddressModal-' + editAddressId);
                var modalEl = document.getElementById(modalId);
                
                if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            }, 500);
        }
    });
})();
</script>
@endif

{{-- Xử lý dropdown địa chỉ --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DEBUG PROVINCES ===');
    
    // Kiểm tra biến provinces từ Blade
    var provinces = @json($provinces ?? []);
    console.log('Provinces data:', provinces);
    console.log('Provinces count:', provinces.length);
    
    // Kiểm tra select element
    var addProvince = document.getElementById('add_province_id');
    console.log('Select element found:', addProvince ? 'YES' : 'NO');
    
    if (addProvince) {
        console.log('Current options count:', addProvince.options.length);
    }
    
    // Test gọi API districts
    console.log('=== TEST API DISTRICTS ===');
    fetch('{{ route("checkout.get-districts") }}?province_id=201')
        .then(function(response) {
            console.log('API Response status:', response.status);
            return response.json();
        })
        .then(function(data) {
            console.log('API Districts data:', data);
        })
        .catch(function(error) {
            console.error('API Error:', error);
        });
    
    // Test gọi API wards
    console.log('=== TEST API WARDS ===');
    fetch('{{ route("checkout.get-wards") }}?district_id=1489')
        .then(function(response) {
            console.log('API Response status:', response.status);
            return response.json();
        })
        .then(function(data) {
            console.log('API Wards data:', data);
        })
        .catch(function(error) {
            console.error('API Error:', error);
        });
});
document.addEventListener('DOMContentLoaded', function() {
    
    // ==================== FORM THÊM ĐỊA CHỈ ====================
    var addProvince = document.getElementById('add_province_id');
    var addDistrict = document.getElementById('add_district_id');
    var addWard = document.getElementById('add_ward_code');

    if (addProvince) {
        addProvince.addEventListener('change', function() {
            loadDistricts(this.value, addDistrict, addWard, null, null);
        });
    }

    if (addDistrict) {
        addDistrict.addEventListener('change', function() {
            loadWards(this.value, addWard, null);
        });
    }

    // Load lại nếu có old value (khi validation fail)
    @if(old('province_id'))
        loadDistricts('{{ old("province_id") }}', addDistrict, addWard, '{{ old("district_id") }}', '{{ old("ward_code") }}');
    @endif

    // ==================== FORM SỬA ĐỊA CHỈ ====================
    @foreach($addresses as $address)
    (function() {
        var addressId = '{{ $address->id }}';
        var editProvince = document.getElementById('edit_province_' + addressId);
        var editDistrict = document.getElementById('edit_district_' + addressId);
        var editWard = document.getElementById('edit_ward_' + addressId);
        
        var savedDistrictId = '{{ $address->district_id }}';
        var savedWardCode = '{{ $address->ward_code }}';

        if (editProvince) {
            editProvince.addEventListener('change', function() {
                loadDistricts(this.value, editDistrict, editWard, null, null);
            });

            // Load districts khi mở modal
            var modal = document.getElementById('editAddressModal-' + addressId);
            if (modal) {
                modal.addEventListener('shown.bs.modal', function() {
                    if (editProvince.value) {
                        loadDistricts(editProvince.value, editDistrict, editWard, savedDistrictId, savedWardCode);
                    }
                });
            }
        }

        if (editDistrict) {
            editDistrict.addEventListener('change', function() {
                loadWards(this.value, editWard, null);
            });
        }
    })();
    @endforeach
});

function loadDistricts(provinceId, districtSelect, wardSelect, selectedDistrictId, selectedWardCode) {
    if (!districtSelect) return;
    
    districtSelect.innerHTML = '<option value="">-- Đang tải --</option>';
    if (wardSelect) {
        wardSelect.innerHTML = '<option value="">-- Chọn Phường / Xã --</option>';
    }

    if (!provinceId) {
        districtSelect.innerHTML = '<option value="">-- Chọn Quận / Huyện --</option>';
        return;
    }

    fetch('{{ route("checkout.get-districts") }}?province_id=' + provinceId)
        .then(function(response) {
            return response.json();
        })
        .then(function(result) {
            districtSelect.innerHTML = '<option value="">-- Chọn Quận / Huyện --</option>';
            
            if (result.success && result.data) {
                result.data.forEach(function(district) {
                    var option = document.createElement('option');
                    option.value = district.DistrictID;
                    option.textContent = district.DistrictName;
                    
                    if (selectedDistrictId && district.DistrictID == selectedDistrictId) {
                        option.selected = true;
                    }
                    districtSelect.appendChild(option);
                });

                if (selectedDistrictId && wardSelect) {
                    loadWards(selectedDistrictId, wardSelect, selectedWardCode);
                }
            }
        })
        .catch(function(error) {
            districtSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>';
        });
}
function loadWards(districtId, wardSelect, selectedWardCode) {
    if (!wardSelect) return;
    
    wardSelect.innerHTML = '<option value="">-- Đang tải... --</option>';

    if (!districtId) {
        wardSelect.innerHTML = '<option value="">-- Chọn Phường / Xã --</option>';
        return;
    }

    fetch('{{ route("checkout.get-wards") }}?district_id=' + districtId)
        .then(function(response) {
            return response.json();
        })
        .then(function(result) {
            wardSelect.innerHTML = '<option value="">-- Chọn Phường / Xã --</option>';
            
            if (result.success && result.data) {
                result.data.forEach(function(ward) {
                    var option = document.createElement('option');
                    option.value = ward.WardCode;
                    option.textContent = ward.WardName;
                    
                    if (selectedWardCode && ward.WardCode == selectedWardCode) {
                        option.selected = true;
                    }
                    wardSelect.appendChild(option);
                });
            }
        })
        .catch(function(error) {
            wardSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>';
        });
}
setTimeout(() => {
        document.querySelectorAll('.auto-hide-alert').forEach(alert => {
            alert.classList.add('fade');
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 500);
        });
    }, 4000);
</script>

@endsection

