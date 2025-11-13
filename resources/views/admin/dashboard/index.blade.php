@extends('admin.layouts.main_nav')
@section('title', 'Dashboard - Thống kê')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        {{-- Header Section --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                    <h4 class="page-title">Dashboard - Thống kê</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row">
            {{-- Tổng sản phẩm --}}
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:t-shirt-bold-duotone" class="fs-24"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-medium text-muted mb-0">Tổng sản phẩm</p>
                                <h4 class="mb-0">{{ number_format($stats['total_products']) }}</h4>
                                <small class="text-success">
                                    <iconify-icon icon="solar:arrow-up-bold-duotone"></iconify-icon>
                                    {{ $stats['active_products'] }} đang hoạt động
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tổng danh mục --}}
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-soft-info text-info d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:clipboard-list-bold-duotone" class="fs-24"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-medium text-muted mb-0">Danh mục</p>
                                <h4 class="mb-0">{{ number_format($stats['total_categories']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tổng khách hàng --}}
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-soft-success text-success d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-24"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-medium text-muted mb-0">Tổng khách hàng</p>
                                <h4 class="mb-0">{{ number_format($stats['total_customers']) }}</h4>
                                <small class="text-success">
                                    <iconify-icon icon="solar:arrow-up-bold-duotone"></iconify-icon>
                                    +{{ $stats['new_customers_this_month'] }} tháng này
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Doanh thu --}}
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-soft-warning text-warning d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:wallet-money-bold-duotone" class="fs-24"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-medium text-muted mb-0">Doanh thu</p>
                                <h4 class="mb-0">{{ number_format($stats['total_revenue'], 0, ',', '.') }}₫</h4>
                                <small class="text-success">
                                    <iconify-icon icon="solar:arrow-up-bold-duotone"></iconify-icon>
                                    {{ number_format($stats['revenue_this_month'], 0, ',', '.') }}₫ tháng này
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Additional Stats --}}
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-soft-danger text-danger d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:bag-smile-bold-duotone" class="fs-24"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-medium text-muted mb-0">Tổng đơn hàng</p>
                                <h4 class="mb-0">{{ number_format($stats['total_orders']) }}</h4>
                                <small class="text-muted">
                                    {{ $stats['completed_orders'] }} hoàn thành
                                </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-soft-secondary text-secondary d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-24"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-medium text-muted mb-0">Đơn chờ xử lý</p>
                                <h4 class="mb-0">{{ number_format($stats['pending_orders']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-soft-success text-success d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-24"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-medium text-muted mb-0">Đơn hoàn thành</p>
                                <h4 class="mb-0">{{ number_format($stats['completed_orders']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-soft-info text-info d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:user-plus-bold-duotone" class="fs-24"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-medium text-muted mb-0">Khách hàng mới</p>
                                <h4 class="mb-0">{{ number_format($stats['new_customers_this_month']) }}</h4>
                                <small class="text-muted">Tháng này</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="row">
            {{-- Doanh thu theo tháng --}}
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">Doanh thu theo tháng (12 tháng gần nhất)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            {{-- Đơn hàng & Khách hàng theo tháng --}}
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">Đơn hàng & Khách hàng</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="ordersCustomersChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Products Row --}}
        <div class="row">
            {{-- Sản phẩm bán chạy --}}
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">Top 10 sản phẩm bán chạy</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th style="width: 60px;">Ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Danh mục</th>
                                        <th class="text-center">Đã bán</th>
                                        <th class="text-end">Giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($best_selling_products as $index => $product)
                                    <tr>
                                        <td class="fw-semibold">{{ $index + 1 }}</td>
                                        <td>
                                            @if($product->image_main)
                                                <img src="{{ asset('storage/' . $product->image_main) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="rounded" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <iconify-icon icon="solar:image-broken" class="text-muted fs-20"></iconify-icon>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <h6 class="fw-semibold mb-0">{{ $product->name }}</h6>
                                        </td>
                                        <td>
                                            @if($product->category)
                                                <span class="badge bg-light-info text-info">
                                                    {{ $product->category->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light-success text-success">
                                                {{ number_format($product->sold_count) }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-semibold">
                                            {{ number_format($product->discount_price ?? $product->base_price, 0, ',', '.') }}₫
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <p class="text-muted mb-0">Chưa có dữ liệu</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sản phẩm theo danh mục --}}
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">Sản phẩm theo danh mục</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="productsByCategoryChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Data Row --}}
        <div class="row">
            {{-- Đơn hàng gần đây --}}
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">Đơn hàng gần đây</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Khách hàng</th>
                                        <th class="text-end">Tổng tiền</th>
                                        <th class="text-center">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recent_orders as $order)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">#{{ $order->order_number }}</span>
                                        </td>
                                        <td>
                                            @if($order->user)
                                                <span>{{ $order->user->name }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-end fw-semibold">
                                            {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'processing' => 'info',
                                                    'shipped' => 'primary',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'Chờ xử lý',
                                                    'processing' => 'Đang xử lý',
                                                    'shipped' => 'Đang giao',
                                                    'completed' => 'Hoàn thành',
                                                    'cancelled' => 'Đã hủy'
                                                ];
                                                $color = $statusColors[$order->status] ?? 'secondary';
                                                $label = $statusLabels[$order->status] ?? ucfirst($order->status);
                                            @endphp
                                            <span class="badge bg-light-{{ $color }} text-{{ $color }}">
                                                {{ $label }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <p class="text-muted mb-0">Chưa có đơn hàng</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Khách hàng mới --}}
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">Khách hàng mới</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Điện thoại</th>
                                        <th class="text-center">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recent_customers as $customer)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ $customer->name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $customer->email }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $customer->phone ?? '—' }}</span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusColors = [
                                                    'active' => 'success',
                                                    'locked' => 'warning',
                                                    'banned' => 'danger'
                                                ];
                                                $statusLabels = [
                                                    'active' => 'Hoạt động',
                                                    'locked' => 'Đã khóa',
                                                    'banned' => 'Đã cấm'
                                                ];
                                                $color = $statusColors[$customer->status] ?? 'secondary';
                                                $label = $statusLabels[$customer->status] ?? ucfirst($customer->status);
                                            @endphp
                                            <span class="badge bg-light-{{ $color }} text-{{ $color }}">
                                                {{ $label }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <p class="text-muted mb-0">Chưa có khách hàng</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Doanh thu theo tháng
    const revenueData = @json($revenue_by_month);
    const revenueLabels = revenueData.map(item => {
        const monthNames = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                          'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
        return monthNames[item.month - 1] + '/' + item.year;
    });
    const revenueValues = revenueData.map(item => parseFloat(item.revenue) || 0);

    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Doanh thu (₫)',
                data: revenueValues,
                borderColor: 'rgb(8, 66, 152)',
                backgroundColor: 'rgba(8, 66, 152, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + '₫';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + '₫';
                        }
                    }
                }
            }
        }
    });

    // Đơn hàng & Khách hàng
    const ordersData = @json($orders_by_month);
    const customersData = @json($customers_by_month);
    const ordersValues = ordersData.map(item => item.count);
    const customersValues = customersData.map(item => item.count);

    const ordersCustomersCtx = document.getElementById('ordersCustomersChart').getContext('2d');
    new Chart(ordersCustomersCtx, {
        type: 'bar',
        data: {
            labels: revenueLabels, // Hiển thị tất cả 12 tháng
            datasets: [
                {
                    label: 'Đơn hàng',
                    data: ordersValues,
                    backgroundColor: 'rgba(10, 179, 156, 0.8)',
                    borderColor: 'rgb(10, 179, 156)',
                    borderWidth: 1
                },
                {
                    label: 'Khách hàng mới',
                    data: customersValues,
                    backgroundColor: 'rgba(240, 101, 72, 0.8)',
                    borderColor: 'rgb(240, 101, 72)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Sản phẩm theo danh mục
    const categoryData = @json($products_by_category);
    const categoryLabels = categoryData.map(item => item.name);
    const categoryValues = categoryData.map(item => item.products_count);

    const categoryCtx = document.getElementById('productsByCategoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryValues,
                backgroundColor: [
                    'rgba(8, 66, 152, 0.8)',
                    'rgba(10, 179, 156, 0.8)',
                    'rgba(240, 101, 72, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(108, 117, 125, 0.8)',
                    'rgba(13, 110, 253, 0.8)',
                    'rgba(25, 135, 84, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
    .page-content {
        padding: 20px 0;
    }

    .card {
        border: none;
        box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
        margin-bottom: 24px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 1.5rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .avatar-sm {
        width: 48px;
        height: 48px;
    }

    .bg-soft-primary {
        background-color: rgba(8, 66, 152, 0.1) !important;
    }

    .text-primary {
        color: #084298 !important;
    }

    .bg-soft-info {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }

    .text-info {
        color: #0d6efd !important;
    }

    .bg-soft-success {
        background-color: rgba(10, 179, 156, 0.1) !important;
    }

    .text-success {
        color: #0ab39c !important;
    }

    .bg-soft-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .bg-soft-danger {
        background-color: rgba(240, 101, 72, 0.1) !important;
    }

    .text-danger {
        color: #f06548 !important;
    }

    .bg-soft-secondary {
        background-color: rgba(108, 117, 125, 0.1) !important;
    }

    .text-secondary {
        color: #6c757d !important;
    }

    .table > :not(caption) > * > * {
        padding: 0.85rem 0.75rem;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table-light {
        background-color: #f8f9fa;
    }

    .badge.bg-light-success {
        background-color: #d1f8ea !important;
        color: #0f7e4f !important;
    }

    .badge.bg-light-info {
        background-color: #d1ecf1 !important;
        color: #0c5460 !important;
    }

    .badge.bg-light-warning {
        background-color: #fff3cd !important;
        color: #664d03 !important;
    }

    .badge.bg-light-danger {
        background-color: #f8d7da !important;
        color: #842029 !important;
    }

    .badge.bg-light-primary {
        background-color: #cfe2ff !important;
        color: #084298 !important;
    }

    .badge.bg-light-secondary {
        background-color: #e2e3e5 !important;
        color: #383d41 !important;
    }
</style>
@endpush

@endsection

