@extends('admin.layouts.main_nav')

@section('content')

          @php
              $paymentMethods = [
                  '1' => 'Thanh toán khi nhận hàng',
                  '2' => 'Chuyển khoản',
                  'credit_card' => 'Thẻ tín dụng',
                  'momo' => 'Ví MoMo',
                  'paypal' => 'PayPal'
              ];
              $paymentMethodLabel = $paymentMethods[$order->payment_method_id] ?? ucfirst($order->payment_method);
          @endphp
          <div class="page-content">
               <!-- Start Container -->
               <div class="container-xxl">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <iconify-icon icon="solar:check-circle-bold-duotone" class="me-2"></iconify-icon>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Error Message --}}
            @if (session('error'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <iconify-icon icon="solar:close-circle-bold-duotone" class="me-2"></iconify-icon>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-xl-9 col-lg-8">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                        <div>
                                            @php
                                                $paymentStatusColors = [
                                                    'unpaid' => [
                                                        'bg' => 'bg-light',
                                                        'text' => 'text-dark',
                                                        'label' => 'Chưa thanh toán',
                                                    ],
                                                    'paid' => [
                                                        'bg' => 'bg-success-subtle',
                                                        'text' => 'text-success',
                                                        'label' => 'Đã thanh toán',
                                                    ],
                                                    'failed' => [
                                                        'bg' => 'bg-danger-subtle',
                                                        'text' => 'text-danger',
                                                        'label' => 'Thanh toán thất bại',
                                                    ],
                                                ];
                                                $paymentStatus = $paymentStatusColors[$order->payment_status] ?? [
                                                    'bg' => 'bg-light',
                                                    'text' => 'text-dark',
                                                    'label' => ucfirst($order->payment_status),
                                                ];

                                                $statusColors = [
                                                    'pending' => [
                                                        'border' => 'border-warning',
                                                        'text' => 'text-warning',
                                                        'label' => 'Chờ xác nhận',
                                                    ],
                                                    'confirmed' => [
                                                        'border' => 'border-info',
                                                        'text' => 'text-info',
                                                        'label' => 'Đã xác nhận',
                                                    ],
                                                    'awaiting_pickup' => [
                                                        'border' => 'border-info',
                                                        'text' => 'text-info',
                                                        'label' => 'Chờ lấy hàng',
                                                    ],
                                                    'shipping' => [
                                                        'border' => 'border-primary',
                                                        'text' => 'text-primary',
                                                        'label' => 'Đang giao',
                                                    ],
                                                    'delivered' => [
                                                        'border' => 'border-success',
                                                        'text' => 'text-success',
                                                        'label' => 'Đã giao hàng',
                                                    ],
                                                    'completed' => [
                                                        'border' => 'border-success',
                                                        'text' => 'text-success',
                                                        'label' => 'Đã hoàn thành',
                                                    ],
                                                    'cancelled_by_customer' => [
                                                        'border' => 'border-danger',
                                                        'text' => 'text-danger',
                                                        'label' => 'Khách hủy',
                                                    ],
                                                    'cancelled_by_admin' => [
                                                        'border' => 'border-danger',
                                                        'text' => 'text-danger',
                                                        'label' => 'Admin hủy',
                                                    ],
                                                    'delivery_failed' => [
                                                        'border' => 'border-danger',
                                                        'text' => 'text-danger',
                                                        'label' => 'Giao thất bại',
                                                    ],
                                                ];
                                                $status = $statusColors[$order->status] ?? [
                                                    'border' => 'border-secondary',
                                                    'text' => 'text-secondary',
                                                    'label' => ucfirst($order->status),
                                                ];
                                            @endphp
                                            <h4 class="fw-medium text-dark d-flex align-items-center gap-2">
                                                #{{ $order->order_number }}
                                                <span
                                                    class="badge {{ $paymentStatus['bg'] }} {{ $paymentStatus['text'] }} px-2 py-1 fs-13">{{ $paymentStatus['label'] }}</span>
                                                <span
                                                    class="border {{ $status['border'] }} {{ $status['text'] }} fs-13 px-2 py-1 rounded">{{ $status['label'] }}</span>
                                            </h4>
                                            <p class="mb-0">Đơn hàng / Chi tiết đơn hàng / #{{ $order->order_number }} -
                                                {{ $order->created_at->format('d/m/Y \lú\c H:i') }}</p>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.orders.list') }}" class="btn btn-outline-secondary">
                                                <iconify-icon icon="solar:arrow-left-bold-duotone"
                                                    class="me-1"></iconify-icon>
                                                Quay lại
                                            </a>
                                            <div class="dropdown d-inline">
                                                <button class="btn btn-primary dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown">
                                                    Cập nhật trạng thái
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @php
                                                        $statusOptions = [
                                                            'pending' => 'Chờ xác nhận',
                                                            'confirmed' => 'Đã xác nhận',
                                                            'awaiting_pickup' => 'Chờ lấy hàng',
                                                            'shipping' => 'Đang giao',
                                                            'delivered' => 'Đã giao hàng',
                                                            'completed' => 'Đã hoàn thành',
                                                            'cancelled_by_customer' => 'Khách hủy',
                                                            'cancelled_by_admin' => 'Admin hủy',
                                                            'delivery_failed' => 'Giao thất bại',
                                                        ];
                                                        $allowedStatuses = $allowedStatuses ?? [];
                                                        $isCancelled = in_array($order->status, [
                                                            'cancelled_by_customer',
                                                            'cancelled_by_admin',
                                                            'completed',
                                                        ]);
                                                    @endphp

                                                    @foreach ($statusOptions as $statusKey => $statusLabel)
                                                        @php
                                                            $isAllowed = in_array($statusKey, $allowedStatuses);
                                                            $isCurrent = $order->status == $statusKey;
                                                            $isCancelStatus = in_array($statusKey, [
                                                                'cancelled_by_customer',
                                                                'cancelled_by_admin',
                                                                'delivery_failed',
                                                            ]);
                                                        @endphp
                                                        @if ($isAllowed || $isCurrent)
                                                            @if ($isCancelStatus && !$isCurrent && $loop->first)
                                                                <li>
                                                                    <hr class="dropdown-divider">
                                                                </li>
                                                            @endif
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.orders.updateStatus', $order->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status"
                                                                        value="{{ $statusKey }}">
                                                                    <button type="submit"
                                                                        class="dropdown-item {{ $isCurrent ? 'active' : '' }} {{ $isCancelStatus ? 'text-danger' : '' }}"
                                                                        {{ $isCurrent ? 'disabled' : '' }}>
                                                                        {{ $statusLabel }}
                                                                        @if ($isCurrent)
                                                                            <span class="float-end">✓</span>
                                                                        @endif
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    @endforeach

                                                    @if (empty($allowedStatuses) && !$isCancelled)
                                                        <li class="dropdown-item-text text-muted small">Không có trạng thái
                                                            nào có thể chuyển</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>

                                    </div>

                                    {{-- <div class="mt-4">
                                                       <h4 class="fw-medium text-dark">Tiến trình đơn hàng</h4>
                                                  </div>
                                                  <div class="row row-cols-xxl-5 row-cols-md-2 row-cols-1">
                                                       @php
                                                           $steps = [
                                                               'pending' => [
                                                                   'label' => 'Chờ xác nhận',
                                                                   'width' => in_array($order->status, ['pending', 'confirmed', 'awaiting_pickup', 'shipping', 'delivered', 'completed']) ? 100 : 0,
                                                                   'color' => in_array($order->status, ['pending', 'confirmed', 'awaiting_pickup', 'shipping', 'delivered', 'completed']) ? 'success' : 'secondary'
                                                               ],
                                                               'payment' => [
                                                                   'label' => 'Thanh toán',
                                                                   'width' => $order->payment_status == 'paid' ? 100 : ($order->payment_status == 'unpaid' ? 0 : 100),
                                                                   'color' => $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'unpaid' ? 'warning' : 'danger')
                                                               ],
                                                               'confirmed' => [
                                                                   'label' => 'Đã xác nhận',
                                                                   'width' => in_array($order->status, ['confirmed', 'awaiting_pickup', 'shipping', 'delivered', 'completed']) ? 100 : 0,
                                                                   'color' => in_array($order->status, ['confirmed', 'awaiting_pickup', 'shipping', 'delivered', 'completed']) ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary')
                                                               ],
                                                               'shipping' => [
                                                                   'label' => 'Đang giao',
                                                                   'width' => in_array($order->status, ['shipping', 'delivered', 'completed']) ? 100 : 0,
                                                                   'color' => in_array($order->status, ['shipping', 'delivered', 'completed']) ? 'success' : (in_array($order->status, ['awaiting_pickup', 'confirmed']) ? 'warning' : 'secondary')
                                                               ],
                                                               'completed' => [
                                                                   'label' => 'Hoàn thành',
                                                                   'width' => in_array($order->status, ['delivered', 'completed']) ? 100 : 0,
                                                                   'color' => in_array($order->status, ['delivered', 'completed']) ? 'success' : ($order->status == 'shipping' ? 'warning' : 'secondary')
                                                               ]
                                                           ];
                                                       @endphp
                                                       @foreach ($steps as $key => $step)
                                                       <div class="col">
                                                            <div class="progress mt-3" style="height: 10px;">
                                                                 <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $step['color'] }}"
                                                                      role="progressbar"
                                                                      style="width: {{ $step['width'] }}%"
                                                                      aria-valuenow="{{ $step['width'] }}"
                                                                      aria-valuemin="0"
                                                                      aria-valuemax="100">
                                                                 </div>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2 mt-2">
                                                                 <p class="mb-0">{{ $step['label'] }}</p>
                                                                 @if (in_array($key, ['confirmed', 'shipping']) && in_array($order->status, ['confirmed', 'awaiting_pickup', 'shipping']))
                                                                 <div class="spinner-border spinner-border-sm text-warning" role="status">
                                                                      <span class="visually-hidden">Loading...</span>
                                                                 </div>
                                                                 @endif
                                                            </div>
                                                       </div>
                                                       @endforeach
                                                  </div> --}}
                                </div>
                                @if (!empty($allowedStatuses) && !in_array($order->status, ['completed', 'cancelled_by_customer', 'cancelled_by_admin']))
                                    <div
                                        class="card-footer d-flex flex-wrap align-items-center justify-content-between bg-light-subtle gap-2">
                                        @if ($order->confirmed_at)
                                            <p class="border rounded mb-0 px-2 py-1 bg-body">
                                                <iconify-icon icon="solar:calendar-bold-duotone"
                                                    class="align-middle fs-16"></iconify-icon>
                                                Ngày xác nhận: <span
                                                    class="text-dark fw-medium">{{ $order->confirmed_at->format('d/m/Y H:i') }}</span>
                                            </p>
                                        @endif
                                        <div>
                                            @php
                                                $nextStatusMap = [
                                                    'confirmed' => [
                                                        'status' => 'awaiting_pickup',
                                                        'label' => 'Chuyển sang chờ lấy hàng',
                                                    ],
                                                    'awaiting_pickup' => [
                                                        'status' => 'shipping',
                                                        'label' => 'Chuyển sang đang giao',
                                                    ],
                                                    'shipping' => [
                                                        'status' => 'delivered',
                                                        'label' => 'Chuyển sang đã giao hàng',
                                                    ],
                                                ];
                                                $nextStatus = $nextStatusMap[$order->status] ?? null;
                                            @endphp
                                            @if ($nextStatus && in_array($nextStatus['status'], $allowedStatuses))
                                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status"
                                                        value="{{ $nextStatus['status'] }}">
                                                    <button type="submit"
                                                        class="btn btn-primary">{{ $nextStatus['label'] }}</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Sản phẩm</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table align-middle mb-0 table-hover table-centered">
                                            <thead class="bg-light-subtle border-bottom">
                                                <tr>
                                                    <th>Tên sản phẩm & Biến thể</th>
                                                    <th>Số lượng</th>
                                                    <th>Đơn giá</th>
                                                    <th>Giảm giá</th>
                                                    <th>Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($order->orderItems as $item)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div
                                                                    class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                                    @if ($item->product_image)
                                                                        <img src="{{ asset('storage/' . $item->product_image) }}"
                                                                            alt="" class="avatar-md"
                                                                            style="object-fit: cover;">
                                                                    @else
                                                                        <iconify-icon icon="solar:image-broken"
                                                                            class="fs-24 text-muted"></iconify-icon>
                                                                    @endif
                                                                </div>
                                                                <div>
                                                                    <a href="#"
                                                                        class="text-dark fw-medium fs-15">{{ $item->product_name }}</a>
                                                                    @if ($item->variant_name)
                                                                        <p class="text-muted mb-0 mt-1 fs-13">
                                                                            <span>Biến thể:
                                                                            </span>{{ $item->variant_name }}
                                                                        </p>
                                                                    @endif
                                                                    @if ($item->product_options)
                                                                        @php
                                                                            $options = is_array($item->product_options)
                                                                                ? $item->product_options
                                                                                : json_decode(
                                                                                    $item->product_options,
                                                                                    true,
                                                                                );
                                                                        @endphp
                                                                        @if ($options)
                                                                            <p class="text-muted mb-0 mt-1 fs-13">
                                                                                @foreach ($options as $key => $value)
                                                                                    <span>{{ ucfirst($key) }}:
                                                                                        {{ $value }}</span>
                                                                                    @if (!$loop->last)
                                                                                        ,
                                                                                    @endif
                                                                                @endforeach
                                                                            </p>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ number_format($item->unit_price, 0, ',', '.') }}₫</td>
                                                        <td>{{ number_format($item->discount_amount, 0, ',', '.') }}₫</td>
                                                        <td class="fw-semibold">
                                                            {{ number_format($item->total_price, 0, ',', '.') }}₫
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-4">
                                                            <p class="text-muted mb-0">Không có sản phẩm nào</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Lịch sử đơn hàng</h4>
                                </div>
                                <div class="card-body">
                                    <div class="position-relative ms-2">
                                        <span class="position-absolute start-0 top-0 border border-dashed h-100"></span>

                                        @if ($order->created_at)
                                            <div class="position-relative ps-4">
                                                <div class="mb-4">
                                                    <span
                                                        class="position-absolute start-0 avatar-sm translate-middle-x bg-light d-inline-flex align-items-center justify-content-center rounded-circle text-success fs-20">
                                                        <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                                                    </span>
                                                    <div
                                                        class="ms-2 d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                                        <div>
                                                            <h5 class="mb-1 text-dark fw-medium fs-15">Đơn hàng đã được tạo
                                                            </h5>
                                                            <p class="mb-0">Đơn hàng #{{ $order->order_number }} được tạo
                                                                bởi {{ $order->customer_name }}</p>
                                                        </div>
                                                        <p class="mb-0">{{ $order->created_at->format('d/m/Y, H:i') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($order->confirmed_at)
                                            <div class="position-relative ps-4">
                                                <div class="mb-4">
                                                    <span
                                                        class="position-absolute start-0 avatar-sm translate-middle-x bg-light d-inline-flex align-items-center justify-content-center rounded-circle text-success fs-20">
                                                        <iconify-icon
                                                            icon="solar:check-circle-bold-duotone"></iconify-icon>
                                                    </span>
                                                    <div
                                                        class="ms-2 d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                                        <div>
                                                            <h5 class="mb-1 text-dark fw-medium fs-15">Đơn hàng đã được xác
                                                                nhận</h5>
                                                            <p class="mb-0">Đơn hàng đã được xác nhận và đang được xử lý
                                                            </p>
                                                        </div>
                                                        <p class="mb-0">{{ $order->confirmed_at->format('d/m/Y, H:i') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($order->payment_status == 'paid')
                                            <div class="position-relative ps-4">
                                                <div class="mb-4">
                                                    <span
                                                        class="position-absolute start-0 avatar-sm translate-middle-x bg-light d-inline-flex align-items-center justify-content-center rounded-circle text-success fs-20">
                                                        <iconify-icon
                                                            icon="solar:check-circle-bold-duotone"></iconify-icon>
                                                    </span>
                                                    <div
                                                        class="ms-2 d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                                        <div>
                                                            <h5 class="mb-1 text-dark fw-medium fs-15">Thanh toán thành
                                                                công</h5>
                                                            <p class="mb-2">Phương thức: {{ $paymentMethodLabel }}</p>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <p class="mb-1 text-dark fw-medium">Trạng thái:</p>
                                                                <span
                                                                    class="badge bg-success-subtle text-success px-2 py-1 fs-13">Đã
                                                                    thanh toán</span>
                                                            </div>
                                                        </div>
                                                        <p class="mb-0">{{ $order->created_at->format('d/m/Y, H:i') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($order->completed_at)
                                            <div class="position-relative ps-4">
                                                <div class="mb-4">
                                                    <span
                                                        class="position-absolute start-0 avatar-sm translate-middle-x bg-light d-inline-flex align-items-center justify-content-center rounded-circle text-success fs-20">
                                                        <iconify-icon
                                                            icon="solar:check-circle-bold-duotone"></iconify-icon>
                                                    </span>
                                                    <div
                                                        class="ms-2 d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                                        <div>
                                                            <h5 class="mb-1 text-dark fw-medium fs-15">Đơn hàng đã hoàn
                                                                thành</h5>
                                                            <p class="mb-0">Đơn hàng đã được giao thành công</p>
                                                        </div>
                                                        <p class="mb-0">{{ $order->completed_at->format('d/m/Y, H:i') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($order->cancelled_at)
                                            <div class="position-relative ps-4">
                                                <div class="mb-2">
                                                    <span
                                                        class="position-absolute start-0 avatar-sm translate-middle-x bg-light d-inline-flex align-items-center justify-content-center rounded-circle text-danger fs-20">
                                                        <iconify-icon
                                                            icon="solar:close-circle-bold-duotone"></iconify-icon>
                                                    </span>
                                                    <div
                                                        class="ms-2 d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                                        <div>
                                                            <h5 class="mb-2 text-dark fw-medium fs-15">Đơn hàng đã bị hủy
                                                            </h5>
                                                            @if ($order->admin_note)
                                                                <p class="mb-0 text-muted">Lý do: {{ $order->admin_note }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <p class="mb-0">{{ $order->cancelled_at->format('d/m/Y, H:i') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card bg-light-subtle">
                                <div class="card-body">
                                    <div class="row g-3 g-lg-0">
                                        <div class="col-lg-3 border-end">
                                            <div class="d-flex align-items-center gap-3 justify-content-between px-3">
                                                <div>
                                                    <p class="text-dark fw-medium fs-16 mb-1">Ngày đặt</p>
                                                    <p class="mb-0">{{ $order->created_at->format('d/m/Y') }}</p>
                                                </div>
                                                <div
                                                    class="avatar bg-light d-flex align-items-center justify-content-center rounded">
                                                    <iconify-icon icon="solar:calendar-date-bold-duotone"
                                                        class="fs-35 text-primary"></iconify-icon>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 border-end">
                                            <div class="d-flex align-items-center gap-3 justify-content-between px-3">
                                                <div>
                                                    <p class="text-dark fw-medium fs-16 mb-1">Khách hàng</p>
                                                    <p class="mb-0">{{ $order->customer_name }}</p>
                                                </div>
                                                <div
                                                    class="avatar bg-light d-flex align-items-center justify-content-center rounded">
                                                    <iconify-icon icon="solar:user-circle-bold-duotone"
                                                        class="fs-35 text-primary"></iconify-icon>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 border-end">
                                            <div class="d-flex align-items-center gap-3 justify-content-between px-3">
                                                <div>
                                                    <p class="text-dark fw-medium fs-16 mb-1">Phương thức</p>
                                                    <p class="mb-0">{{ $paymentMethodLabel }}</p>
                                                </div>
                                                <div
                                                    class="avatar bg-light d-flex align-items-center justify-content-center rounded">
                                                    <iconify-icon icon="solar:wallet-money-bold-duotone"
                                                        class="fs-35 text-primary"></iconify-icon>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="d-flex align-items-center gap-3 justify-content-between px-3">
                                                <div>
                                                    <p class="text-dark fw-medium fs-16 mb-1">Mã đơn hàng</p>
                                                    <p class="mb-0">#{{ $order->order_number }}</p>
                                                </div>
                                                <div
                                                    class="avatar bg-light d-flex align-items-center justify-content-center rounded">
                                                    <iconify-icon icon="solar:clipboard-text-bold-duotone"
                                                        class="fs-35 text-primary"></iconify-icon>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Tóm tắt đơn hàng</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="px-0">
                                                <p class="d-flex mb-0 align-items-center gap-1">
                                                    <iconify-icon icon="solar:clipboard-text-broken"></iconify-icon>
                                                    Tổng tiền hàng:
                                                </p>
                                            </td>
                                            <td class="text-end text-dark fw-medium px-0">
                                                {{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
                                        </tr>
                                        <tr>
                                            <td class="px-0">
                                                <p class="d-flex mb-0 align-items-center gap-1">
                                                    <iconify-icon icon="solar:ticket-broken"
                                                        class="align-middle"></iconify-icon>
                                                    Giảm giá:
                                                </p>
                                            </td>
                                            <td class="text-end text-dark fw-medium px-0">
                                                -{{ number_format($order->promotion_amount, 0, ',', '.') }}₫</td>
                                        </tr>
                                        <tr>
                                            <td class="px-0">
                                                <p class="d-flex mb-0 align-items-center gap-1">
                                                    <iconify-icon icon="solar:kick-scooter-broken"
                                                        class="align-middle"></iconify-icon>
                                                    Phí vận chuyển:
                                                </p>
                                            </td>
                                            <td class="text-end text-dark fw-medium px-0">
                                                {{ number_format($order->shipping_fee, 0, ',', '.') }}₫</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between bg-light-subtle">
                            <div>
                                <p class="fw-medium text-dark mb-0">Tổng cộng</p>
                            </div>
                            <div>
                                <p class="fw-medium text-dark mb-0">
                                    {{ number_format($order->total_amount, 0, ',', '.') }}₫</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Thông tin thanh toán</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 bg-light avatar d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:wallet-money-bold-duotone"
                                        class="fs-32 text-primary"></iconify-icon>
                                </div>
                                <div>
                                    <p class="mb-1 text-dark fw-medium">{{ $paymentMethodLabel }}</p>
                                    @if ($order->payment_reference)
                                        <p class="mb-0 text-dark">{{ $order->payment_reference }}</p>
                                    @endif
                                </div>
                                <div class="ms-auto">
                                    @if ($order->payment_status == 'paid')
                                        <iconify-icon icon="solar:check-circle-broken"
                                            class="fs-22 text-success"></iconify-icon>
                                    @elseif($order->payment_status == 'unpaid')
                                        <iconify-icon icon="solar:clock-circle-broken"
                                            class="fs-22 text-warning"></iconify-icon>
                                    @else
                                        <iconify-icon icon="solar:close-circle-broken"
                                            class="fs-22 text-danger"></iconify-icon>
                                    @endif
                                </div>
                            </div>
                            @if ($order->payment_details && isset($order->payment_details['transaction_id']))
                                <p class="text-dark mb-1 fw-medium">
                                    Mã giao dịch:
                                    <span
                                        class="text-muted fw-normal fs-13">{{ $order->payment_details['transaction_id'] }}</span>
                                </p>
                            @endif
                            @if ($order->payment_reference)
                                <p class="text-dark mb-0 fw-medium">
                                    Mã tham chiếu:
                                    <span class="text-muted fw-normal fs-13">{{ $order->payment_reference }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Thông tin khách hàng</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-2">
                                <div
                                    class="avatar rounded-3 border border-light border-3 bg-light d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:user-circle-bold-duotone"
                                        class="fs-32 text-primary"></iconify-icon>
                                </div>
                                <div>
                                    <p class="mb-1 fw-semibold">{{ $order->customer_name }}</p>
                                    <a href="mailto:{{ $order->customer_email }}"
                                        class="link-primary fw-medium">{{ $order->customer_email }}</a>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h5 class="mb-2">Số điện thoại</h5>
                                <p class="mb-1">{{ $order->customer_phone }}</p>
                            </div>

                            <div class="mt-3">
                                <h5 class="mb-2">Địa chỉ giao hàng</h5>
                                <div>
                                    <p class="mb-1">{{ $order->receiver_name }}</p>
                                    <p class="mb-1">{{ $order->shipping_address }}</p>
                                    <p class="mb-1">{{ $order->receiver_phone }}</p>
                                </div>
                            </div>

                            <div class="mt-3">
                                <h5 class="mb-2">Địa chỉ khách hàng</h5>
                                <p class="mb-1">{{ $order->customer_address }}</p>
                            </div>

                            @if ($order->note)
                                <div class="mt-3">
                                    <h5 class="mb-2">Ghi chú</h5>
                                    <p class="mb-1 text-muted">{{ $order->note }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if ($order->admin_note)
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Ghi chú của admin</h4>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $order->admin_note }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
