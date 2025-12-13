@extends('client.layouts.master')

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Đơn hàng của tôi</h4>
            <p class="text-muted mb-0">Quản lý và theo dõi tất cả đơn hàng của bạn</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($orders->isEmpty())
        {{-- Empty State --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-shopping-bag text-muted" style="font-size: 4rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Chưa có đơn hàng nào</h5>
                <p class="text-muted mb-4">Bạn chưa thực hiện đơn hàng nào. Hãy khám phá các sản phẩm của chúng tôi!</p>
                <a href="{{ route('home') }}" class="btn btn-primary px-4">
                    <i class="fas fa-shopping-cart me-2"></i>Mua sắm ngay
                </a>
            </div>
        </div>
    @else
        {{-- Statistics Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-receipt fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-0">{{ $orders->count() }}</h5>
                        <small class="text-muted">Tổng đơn hàng</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-0">{{ $orders->whereIn('status', ['pending', 'confirmed'])->count() }}</h5>
                        <small class="text-muted">Đang xử lý</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-info mb-2">
                            <i class="fas fa-truck fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-0">{{ $orders->whereIn('status', ['awaiting_pickup', 'shipping'])->count() }}</h5>
                        <small class="text-muted">Đang giao</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-0">{{ $orders->whereIn('status', ['delivered', 'completed'])->count() }}</h5>
                        <small class="text-muted">Hoàn thành</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Orders List --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-list me-2"></i>Danh sách đơn hàng
                    </h6>
                    <span class="badge bg-primary rounded-pill">{{ $orders->count() }} đơn</span>
                </div>
            </div>
            <div class="card-body p-0">
                {{-- Desktop Table --}}
                <div class="table-responsive d-none d-lg-block">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3">Mã đơn hàng</th>
                                <th class="py-3">Ngày đặt</th>
                                <th class="py-3">Thanh toán</th>
                                <th class="py-3">Trạng thái</th>
                                <th class="py-3 text-end">Tổng tiền</th>
                                <th class="py-3 text-center pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <span class="fw-semibold text-primary">#{{ $order->order_number }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div>{{ $order->created_at ? $order->created_at->format('d/m/Y') : '' }}</div>
                                        <small class="text-muted">{{ $order->created_at ? $order->created_at->format('H:i') : '' }}</small>
                                    </td>
                                    <td class="py-3">
                                        <span class="d-inline-flex align-items-center">
                                            @if(str_contains(strtolower($paymentMethods[$order->payment_method_id] ?? ''), 'cod') || str_contains(strtolower($paymentMethods[$order->payment_method_id] ?? ''), 'tiền mặt'))
                                                <i class="fas fa-money-bill-wave text-success me-2"></i>
                                            @else
                                                <i class="fas fa-credit-card text-primary me-2"></i>
                                            @endif
                                            {{ $paymentMethods[$order->payment_method_id] ?? 'Không xác định' }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2">
                                                    <i class="fas fa-hourglass-half me-1"></i>Chờ xử lý
                                                </span>
                                                @break
                                            @case('confirmed')
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info px-3 py-2">
                                                    <i class="fas fa-clipboard-check me-1"></i>Đã xác nhận
                                                </span>
                                                @break
                                            @case('awaiting_pickup')
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2">
                                                    <i class="fas fa-box me-1"></i>Chờ lấy hàng
                                                </span>
                                                @break
                                            @case('shipping')
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2">
                                                    <i class="fas fa-shipping-fast me-1"></i>Đang giao
                                                </span>
                                                @break
                                            @case('delivered')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2">
                                                    <i class="fas fa-truck-loading me-1"></i>Đã giao
                                                </span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2">
                                                    <i class="fas fa-check-double me-1"></i>Hoàn thành
                                                </span>
                                                @break
                                            @case('cancelled_by_customer')
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2">
                                                    <i class="fas fa-user-times me-1"></i>Bạn đã hủy
                                                </span>
                                                @break
                                            @case('cancelled_by_admin')
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2">
                                                    <i class="fas fa-ban me-1"></i>Shop hủy
                                                </span>
                                                @break
                                            @case('delivery_failed')
                                                <span class="badge bg-dark bg-opacity-10 text-dark border border-dark px-3 py-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Giao thất bại
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2">
                                                    {{ $order->status }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td class="py-3 text-end">
                                        <span class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                                    </td>
                                    <td class="py-3 text-center pe-4">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="d-lg-none">
                    @foreach($orders as $order)
                        <div class="border-bottom p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="fw-bold text-primary">#{{ $order->order_number }}</span>
                                    <div class="text-muted small">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</div>
                                </div>
                                @switch($order->status)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                        @break
                                    @case('confirmed')
                                        <span class="badge bg-info">Đã xác nhận</span>
                                        @break
                                    @case('awaiting_pickup')
                                        <span class="badge bg-primary">Chờ lấy hàng</span>
                                        @break
                                    @case('shipping')
                                        <span class="badge bg-primary">Đang giao</span>
                                        @break
                                    @case('delivered')
                                        <span class="badge bg-success">Đã giao</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Hoàn thành</span>
                                        @break
                                    @case('cancelled_by_customer')
                                        <span class="badge bg-danger">Bạn đã hủy</span>
                                        @break
                                    @case('cancelled_by_admin')
                                        <span class="badge bg-danger">Shop hủy</span>
                                        @break
                                    @case('delivery_failed')
                                        <span class="badge bg-dark">Giao thất bại</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $order->status }}</span>
                                @endswitch
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-credit-card me-1"></i>
                                        {{ $paymentMethods[$order->payment_method_id] ?? 'Không xác định' }}
                                    </small>
                                    <div class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}đ</div>
                                </div>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-1"></i>Xem
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        @if(method_exists($orders, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    @endif
</div>
@endsection