@extends('client.layouts.master')

@section('content')
<div class="container my-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold mb-0" style="color: rgba(204,162,112,1);">
            Chi tiết đơn hàng {{ $order->order_number }}
        </h2>

        <a href="{{ route('orders.index') }}" class="btn btn-outline-dark">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- SHIPPING INFO --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold"
                     style="border-bottom: 2px solid rgba(204,162,112,0.6);">
                    <i class="fas fa-map-marker-alt me-2" style="color: rgba(204,162,112,1);"></i>
                    Thông tin giao hàng
                </div>

                <div class="card-body">
                    <p><strong>Người nhận:</strong> {{ $order->receiver_name }}</p>
                    <p><strong>Điện thoại:</strong> {{ $order->receiver_phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                    @if($order->note)
                        <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- PAYMENT INFO --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold"
                     style="border-bottom: 2px solid rgba(204,162,112,0.6);">
                    <i class="fas fa-credit-card me-2" style="color: rgba(204,162,112,1);"></i>
                    Thông tin thanh toán
                </div>

                <div class="card-body">

                    <p><strong>Phương thức:</strong> {{ $paymentMethod->name ?? 'Không xác định' }}</p>

                    <p><strong>Thanh toán:</strong>
                        @if($order->payment_status == 'paid')
                            <span class="badge bg-success">Đã thanh toán</span>
                        @else
                            <span class="badge bg-secondary">Chưa thanh toán</span>
                        @endif
                    </p>

                    {{-- STATUS --}}
                    @php
                        $statusMap = [
                            'pending' => ['Chờ xử lý', 'rgba(204,162,112,1)', 'black'],
                            'confirmed' => ['Đã xác nhận', 'rgba(204,162,112,1)', 'black'],
                            'awaiting_pickup' => ['Chờ lấy hàng', 'rgba(204,162,112,1)', 'black'],
                            'shipping' => ['Đang giao', '#0d6efd', 'white'],
                            'completed' => ['Hoàn thành', 'black', 'white'],
                            'delivered' => ['Đã giao', 'rgb(25 135 84)', 'white'],
                            'cancelled' => ['Đã hủy', '#dc3545', 'white'],
                            'cancelled_by_customer' => ['Khách hủy', '#dc3545', 'white'],
                            'cancelled_by_admin' => ['Shop hủy', '#dc3545', 'white'],
                            'delivery_failed' => ['Giao thất bại', '#dc3545', 'white'],
                        ];
                        $status = $statusMap[$order->status] ?? [$order->status, 'gray', 'white'];
                    @endphp

                    <p><strong>Trạng thái đơn:</strong>
                        <span class="badge px-3"
                              style="background: {{ $status[1] }}; color: {{ $status[2] }}">
                            {{ $status[0] }}
                        </span>
                    </p>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <strong>{{ number_format($order->subtotal) }} đ</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Khuyến mãi:</span>
                        <strong class="text-danger">-{{ number_format($order->promotion_amount) }} đ</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <strong>{{ number_format($order->shipping_fee) }} đ</strong>
                    </div>

                    <div class="d-flex justify-content-between fs-5 mt-3">
                        <strong>Tổng tiền:</strong>
                        <strong style="color: rgba(204,162,112,1);">{{ number_format($order->total_amount) }} đ</strong>
                    </div>

                    @if($order->status === 'pending')
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="mt-3">
                            @csrf
                            <button class="btn w-100 text-white"
                                    style="background:black;"
                                    onclick="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?')">
                                <i class="fas fa-times me-1"></i> Hủy đơn hàng
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- ORDER ITEMS --}}
    <div class="card shadow-sm border-0 mt-5">
        <div class="card-header bg-white fw-semibold"
             style="border-bottom: 2px solid rgba(204,162,112,0.6);">
            <i class="fas fa-box-open me-2" style="color: rgba(204,162,112,1);"></i>
            Sản phẩm trong đơn
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Phân loại</th>
                        <th>Giá</th>
                        <th class="text-center">SL</th>
                        <th>Thành tiền</th>
                        <th class="text-center" style="width: 150px;">Đánh giá</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($orderItems as $item)
                        @php
                            $img = $item->product_image ? asset('storage/'.$item->product_image) : null;
                            $canReview = in_array($order->status, ['completed', 'delivered']);
                            $alreadyReviewed = isset($reviewedMap[$item->product_id]) &&
                                               $reviewedMap[$item->product_id]->count() > 0;
                            $variant = $item->variant_name ??
                                       $item->variant?->attributeValues->pluck('value')->join(', ');
                        @endphp

                        <tr>
                            <td>
                                @if($img)
                                    <img src="{{ $img }}" class="rounded"
                                         style="width:80px;height:80px;object-fit:cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="width:80px;height:80px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <strong>{{ $item->product_name }}</strong>
                                @if($item->product?->brand)
                                    <div class="text-muted small">
                                        Thương hiệu: {{ $item->product->brand->name }}
                                    </div>
                                @endif
                            </td>

                            <td class="small">
                                {{ $variant ?: '-' }}
                            </td>

                            <td>{{ number_format($item->unit_price) }} đ</td>

                            <td class="text-center">{{ $item->quantity }}</td>

                            <td class="fw-semibold" style="color: rgba(204,162,112,1);">
                                {{ number_format($item->total_price) }} đ
                            </td>

                            <td class="text-center">
                                @if($canReview && !$alreadyReviewed)
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-dark"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#reviewModal{{ $item->id }}">
                                        <i class="fas fa-star me-1"></i> Đánh giá
                                    </button>
                                @elseif($alreadyReviewed)
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i> Đã đánh giá
                                    </span>
                                @else
                                    <span class="text-muted small">
                                        <i class="fas fa-clock me-1"></i> Chờ nhận hàng
                                    </span>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal đánh giá --}}
                        @if($canReview && !$alreadyReviewed)
                            <div class="modal fade" id="reviewModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background: rgba(204,162,112,0.15); border-bottom: 2px solid rgba(204,162,112,0.6);">
                                            <h5 class="modal-title fw-bold">
                                                <i class="fas fa-star me-2" style="color: rgba(204,162,112,1);"></i>
                                                Đánh giá sản phẩm
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        
                                        <form action="{{ route('reviews.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="order_item_id" value="{{ $item->id }}">
                                            
                                            <div class="modal-body">
                                                {{-- Thông tin sản phẩm --}}
                                                <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                                                    @if($img)
                                                        <img src="{{ $img }}" class="rounded me-3"
                                                             style="width:60px;height:60px;object-fit:cover;">
                                                    @else
                                                        <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center"
                                                             style="width:60px;height:60px;">
                                                            <i class="fas fa-image text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1 fw-semibold">{{ $item->product_name }}</h6>
                                                        @if($variant)
                                                            <small class="text-muted">{{ $variant }}</small>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Chọn sao --}}
                                                <div class="mb-4">
                                                    <label class="form-label fw-semibold">
                                                        Đánh giá của bạn <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="star-rating-container d-flex justify-content-center gap-1 py-3" data-item-id="{{ $item->id }}">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <input type="radio" 
                                                                   name="rating" 
                                                                   value="{{ $i }}" 
                                                                   id="star{{ $item->id }}_{{ $i }}" 
                                                                   class="d-none star-input"
                                                                   required>
                                                            <label for="star{{ $item->id }}_{{ $i }}" 
                                                                   class="star-label px-1"
                                                                   data-rating="{{ $i }}">
                                                                <i class="far fa-star fs-2"></i>
                                                            </label>
                                                        @endfor
                                                    </div>
                                                    <div class="text-center">
                                                        <span class="rating-text small" id="ratingText{{ $item->id }}" style="color: #6c757d;">
                                                            Nhấn vào sao để đánh giá
                                                        </span>
                                                    </div>
                                                </div>

                                                {{-- Nhận xét --}}
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Nhận xét của bạn</label>
                                                    <textarea name="content" 
                                                              class="form-control" 
                                                              rows="4"
                                                              placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                                                    <small class="text-muted">Không bắt buộc</small>
                                                </div>
                                            </div>
                                            
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    Hủy
                                                </button>
                                                <button type="submit" class="btn text-white" style="background: black;">
                                                    <i class="fas fa-paper-plane me-1"></i> Gửi đánh giá
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const goldColor = 'rgba(204,162,112,1)';
    const grayColor = '#dee2e6';
    
    const ratingTexts = {
        1: '⭐ Rất tệ',
        2: '⭐⭐ Tệ', 
        3: '⭐⭐⭐ Bình thường',
        4: '⭐⭐⭐⭐ Tốt',
        5: '⭐⭐⭐⭐⭐ Tuyệt vời'
    };

    document.querySelectorAll('.star-rating-container').forEach(function(container) {
        const itemId = container.dataset.itemId;
        const stars = container.querySelectorAll('.star-label');
        const ratingTextEl = document.getElementById('ratingText' + itemId);
        let selectedRating = 0;

        stars.forEach(function(star, index) {
            const starIcon = star.querySelector('i');
            
            // Hover vào sao
            star.addEventListener('mouseenter', function() {
                for (let i = 0; i <= index; i++) {
                    const icon = stars[i].querySelector('i');
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    icon.style.color = goldColor;
                }
                for (let i = index + 1; i < stars.length; i++) {
                    const icon = stars[i].querySelector('i');
                    if (i >= selectedRating) {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        icon.style.color = grayColor;
                    }
                }
                ratingTextEl.textContent = ratingTexts[index + 1];
                ratingTextEl.style.color = goldColor;
            });

            // Hover ra khỏi sao
            star.addEventListener('mouseleave', function() {
                updateStars();
            });

            // Click chọn sao
            star.addEventListener('click', function() {
                selectedRating = index + 1;
                updateStars();
                ratingTextEl.style.fontWeight = '600';
            });
        });

        function updateStars() {
            stars.forEach(function(star, idx) {
                const icon = star.querySelector('i');
                if (idx < selectedRating) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    icon.style.color = goldColor;
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    icon.style.color = grayColor;
                }
            });
            
            if (selectedRating > 0) {
                ratingTextEl.textContent = ratingTexts[selectedRating];
                ratingTextEl.style.color = goldColor;
            } else {
                ratingTextEl.textContent = 'Nhấn vào sao để đánh giá';
                ratingTextEl.style.color = '#6c757d';
                ratingTextEl.style.fontWeight = 'normal';
            }
        }

        // Reset khi đóng modal
        const modal = document.getElementById('reviewModal' + itemId);
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                selectedRating = 0;
                updateStars();
                this.querySelector('form').reset();
            });
        }
    });
});
</script>
@endpush
@endsection