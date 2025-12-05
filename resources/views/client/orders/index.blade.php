    @extends('client.layouts.master')

    @section('content')
    <div class="container my-4">
        <h2 class="mb-3">Đơn hàng của tôi</h2>

        @if(session('success'))
            <div class="alert alert-success mt-2">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mt-2">{{ session('error') }}</div>
        @endif

        @if($orders->isEmpty())
            <p class="mt-3">Bạn chưa có đơn hàng nào.</p>
        @else
            <div class="table-responsive mt-3">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 160px;">Mã đơn</th>
                            <th style="width: 160px;">Ngày đặt</th>
                            <th style="width: 150px;">Thanh toán</th>
                            <th style="width: 150px;">Trạng thái</th>
                            <th style="width: 150px;">Tổng tiền</th>
                            <th style="width: 120px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</td>
                                <td>
                                    {{ $paymentMethods[$order->payment_method_id] ?? 'Không xác định' }}
                                </td>
                                <td>
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">Đã hủy</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-success">Hoàn thành</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $order->status }}</span>
                                    @endswitch
                                </td>
                                <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                        Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @endsection