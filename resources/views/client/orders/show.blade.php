{{-- resources/views/client/orders/show.blade.php --}}
@extends('client.layouts.master')

@section('content')
<div class="container my-4">
    <h2 class="mb-3">Chi tiết đơn hàng {{ $order->order_number }}</h2>

    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mt-2">{{ session('error') }}</div>
    @endif

    <div class="row mt-3">
        <div class="col-md-6 mb-3">
            <h5>Thông tin giao hàng</h5>
            <div class="card p-3">
                <p class="mb-1"><strong>Người nhận:</strong> {{ $order->receiver_name }}</p>
                <p class="mb-1"><strong>Điện thoại:</strong> {{ $order->receiver_phone }}</p>
                <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                @if($order->note)
                    <p class="mb-0"><strong>Ghi chú:</strong> {{ $order->note }}</p>
                @endif
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <h5>Thông tin thanh toán</h5>
            <div class="card p-3">
                <p class="mb-1">
                    <strong>Phương thức:</strong>
                    {{ $paymentMethod->name ?? 'Không xác định' }}
                </p>
                <p class="mb-1">
                    <strong>Trạng thái thanh toán:</strong>
                    @if($order->payment_status === 'paid')
                        <span class="badge bg-success">Đã thanh toán</span>
                    @else
                        <span class="badge bg-secondary">Chưa thanh toán</span>
                    @endif
                </p>
                <p class="mb-1">
                    <strong>Trạng thái đơn hàng:</strong>
                    @switch($order->status)
                        @case('pending')
                            <span class="badge bg-warning text-dark">Chờ xử lý</span>
                            @break
                        @case('cancelled')
                        @case('cancelled_by_customer')
                        @case('cancelled_by_admin')
                            <span class="badge bg-danger">Đã hủy</span>
                            @break
                        @case('completed')
                            <span class="badge bg-success">Hoàn thành</span>
                            @break
                        @case('delivered')
                            <span class="badge bg-success">Đã giao</span>
                            @break
                        @default
                            <span class="badge bg-secondary">{{ $order->status }}</span>
                    @endswitch
                </p>
                <hr>
                <p class="mb-1"><strong>Tạm tính:</strong> {{ number_format($order->subtotal, 0, ',', '.') }} đ</p>
                <p class="mb-1"><strong>Khuyến mãi:</strong> -{{ number_format($order->promotion_amount, 0, ',', '.') }} đ</p>
                <p class="mb-1"><strong>Phí vận chuyển:</strong> {{ number_format($order->shipping_fee, 0, ',', '.') }} đ</p>
                <p class="mb-0"><strong>Tổng tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} đ</p>

                @if($order->status === 'pending')
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?')">
                            Hủy đơn hàng
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <hr>

    <h5>Sản phẩm trong đơn</h5>
    <div class="table-responsive mt-2">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 100px;">Ảnh</th>
                    <th>Sản phẩm</th>
                    <th>Phân loại</th>
                    <th>Giá</th>
                    <th style="width: 80px;">Số lượng</th>
                    <th>Thành tiền</th>
                    <th style="width: 180px;">Đánh giá</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderItems as $item)
                    @php
                        $productImage = $item->product_image
                            ? asset('storage/' . ltrim($item->product_image, '/'))
                            : null;

                        // Chỉ cho đánh giá nếu đơn đã giao/hoàn thành
                        $canReview = in_array($order->status, ['completed', 'delivered']);

                        // Nếu bạn đã truyền $reviewedMap từ controller:
                        $alreadyReviewed = isset($reviewedMap[$item->product_id]) 
                            && $reviewedMap[$item->product_id]->count() > 0;
                    @endphp
                    <tr>
                        {{-- Ảnh --}}
                        <td>
                            @if($productImage)
                                <img src="{{ $productImage }}" alt="Product" class="img-fluid"
                                     style="max-width: 80px; max-height: 80px; object-fit: cover;">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>

                        {{-- Sản phẩm + Brand --}}
                        <td>
                            {{ $item->product_name }}

                            {{-- SKU sản phẩm --}}
                            @if($item->product && $item->product->sku)
                                <div class="text-muted small">SKU: {{ $item->product->sku }}</div>
                            @endif

                            {{-- Thương hiệu --}}
                            @if($item->product && $item->product->brand)
                                <div class="text-muted small">
                                    Thương hiệu: <strong>{{ $item->product->brand->name }}</strong>
                                </div>
                            @endif
                        </td>

                        {{-- Biến thể / phân loại --}}
                       {{-- Phân loại / Biến thể --}}
<td>
    @php
        // 1. Ưu tiên dùng variant_name nếu đã lưu cứng trong order_items
        $variantLabel = $item->variant_name;

        // 2. Nếu chưa có, mà vẫn có quan hệ variant + attributeValues => build từ quan hệ
        if (!$variantLabel && $item->variant && $item->variant->attributeValues?->count()) {
            $parts = [];

            foreach ($item->variant->attributeValues as $attrValue) {
                // Tên thuộc tính (Màu sắc, Size, ...)
                $attrName  = $attrValue->attribute->name ?? null;
                // Giá trị (Đỏ, L, ...)
                $valueName = $attrValue->value;

                if ($attrName) {
                    $parts[] = $attrName . ': ' . $valueName;
                } else {
                    $parts[] = $valueName;
                }
            }

            $variantLabel = implode(', ', $parts);
        }

        // 3. Nếu vẫn chưa có (không có quan hệ), fallback về JSON variant_attributes (cũ)
        if (!$variantLabel && is_array($item->variant_attributes) && count($item->variant_attributes)) {
            $variantLabel = implode(', ', $item->variant_attributes);
        }
    @endphp

    {{-- Hiển thị label biến thể --}}
    @if($variantLabel)
        {{ $variantLabel }}
    @else
        <span class="text-muted">-</span>
    @endif

    {{-- SKU biến thể (nếu có) --}}
    @if($item->variant && $item->variant->sku)
        <div class="text-muted small">SKU: {{ $item->variant->sku }}</div>
    @endif
</td>

                        {{-- Giá / SL / Thành tiền --}}
                        <td>{{ number_format($item->unit_price, 0, ',', '.') }} đ</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->total_price, 0, ',', '.') }} đ</td>

                        {{-- Cột Đánh giá --}}
                        <td>
                            @if($canReview && empty($alreadyReviewed))
                                <form action="{{ route('reviews.store') }}" method="POST" class="small">
                                    @csrf
                                    <input type="hidden" name="order_item_id" value="{{ $item->id }}">

                                    <div class="mb-1">
                                        <select name="rating" class="form-select form-select-sm" required>
                                            <option value="">-- Chọn sao --</option>
                                            <option value="5">5 sao - Tuyệt vời</option>
                                            <option value="4">4 sao - Tốt</option>
                                            <option value="3">3 sao - Bình thường</option>
                                            <option value="2">2 sao - Tạm được</option>
                                            <option value="1">1 sao - Tệ</option>
                                        </select>
                                    </div>

                                    <div class="mb-1">
                                        <textarea name="content" class="form-control form-control-sm" rows="2"
                                                  placeholder="Cảm nhận của bạn (không bắt buộc)"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-sm btn-primary w-100">
                                        Gửi đánh giá
                                    </button>
                                </form>
                            @elseif(!empty($alreadyReviewed))
                                <span class="text-success small">Bạn đã đánh giá</span>
                            @else
                                <span class="text-muted small">Đánh giá sau khi đơn hoàn thành</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary mt-3">
        ← Quay lại danh sách đơn hàng
    </a>
</div>
@endsection