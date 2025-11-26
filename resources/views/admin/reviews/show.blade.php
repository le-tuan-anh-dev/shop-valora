@extends('admin.layouts.main_nav')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    Chi tiết đánh giá: <span class="text-primary">{{ $product->name }}</span>
                </h4>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        {{-- 💡 FORM LỌC (Chỉ lọc trong sản phẩm này) --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    
                    {{-- Lọc Biến thể --}}
                    <div class="col-md-3">
                        <label class="form-label">Biến thể</label>
                        <select name="variant_id" class="form-select">
                            <option value="">-- Tất cả biến thể --</option>
                            @foreach($variants as $var)
                                <option value="{{ $var->id }}" {{ request('variant_id') == $var->id ? 'selected' : '' }}>
                                    {{ $var->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Lọc Số sao --}}
                    <div class="col-md-2">
                        <label class="form-label">Đánh giá</label>
                        <select name="rating" class="form-select">
                            <option value="">Tất cả sao</option>
                            @for($i=5; $i>=1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected':'' }}>{{ $i }} Sao</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Thời gian --}}
                    <div class="col-md-2">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 📚 DANH SÁCH ĐÁNH GIÁ --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 150px;">Biến thể</th>
                                <th style="width: 150px;">Khách hàng</th>
                                <th style="width: 300px;">Nội dung & Ảnh</th>
                                <th>Phản hồi từ Admin</th>
                                <th style="width: 120px;">Thời gian</th>
                                <th style="width: 100px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reviews as $review)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    
                                    {{-- Cột Biến thể --}}
                                    <td>
                                        @if(optional($review->variant)->title)
                                            <span class="badge bg-light text-dark border">
                                                {{ $review->variant->title }}
                                            </span>
                                        @else
                                            <span class="text-muted small">Không có</span>
                                        @endif
                                    </td>

                                    {{-- Cột Khách hàng --}}
                                    <td>
                                        <span class="fw-semibold">{{ $review->user->name ?? 'Ẩn danh' }}</span>
                                    </td>

                                    {{-- Cột Nội dung --}}
                                    <td>
                                        <div class="mb-2 text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="mb-2 text-dark fst-italic">"{{ $review->content }}"</p>
                                        
                                        @if($review->images->count())
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($review->images as $img)
                                                    <a href="{{ asset('storage/'.$img->image_path) }}" target="_blank">
                                                        <img src="{{ asset('storage/'.$img->image_path) }}" 
                                                            class="rounded border" width="50" height="50" style="object-fit:cover;">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Cột Phản hồi (Replies) --}}
                                    <td>
                                        @foreach($review->replies as $reply)
                                            <div class="bg-light p-2 rounded mb-2 border border-start-0 border-end-0 border-top-0 border-bottom-0 border-start border-3 border-info">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <small class="fw-bold text-info">Admin:</small>
                                                    <div>
                                                        {{-- Sửa Reply --}}
                                                        <button class="btn btn-sm btn-link text-warning p-0 me-2" 
                                                                data-bs-toggle="modal" data-bs-target="#editReplyModal{{ $reply->id }}">
                                                            <i class="bx bx-edit"></i>
                                                        </button>
                                                        {{-- Xóa Reply --}}
                                                        <form action="{{ route('admin.reviews.destroy', $reply->id) }}" method="POST" class="d-inline">
                                                            @csrf @method('DELETE')
                                                            <button class="btn btn-sm btn-link text-danger p-0" onclick="return confirm('Xóa phản hồi này?')">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <span class="d-block text-muted small mt-1">{{ $reply->content }}</span>
                                            </div>

                                            {{-- Modal Sửa Reply --}}
                                            <div class="modal fade" id="editReplyModal{{ $reply->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <form action="{{ route('admin.reviews.update', $reply->id) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Sửa phản hồi</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <textarea name="content" class="form-control" rows="3" required>{{ $reply->content }}</textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary btn-sm">Cập nhật</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </td>

                                    {{-- Cột Thời gian --}}
                                    <td>
                                        {{ $review->created_at->format('d/m/Y') }}<br>
                                        <small class="text-muted">{{ $review->created_at->format('H:i') }}</small>
                                    </td>

                                    {{-- Cột Hành động --}}
                                    <td>
                                        <div class="d-flex gap-2">
                                            {{-- Nút trả lời --}}
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#replyModal{{ $review->id }}" title="Trả lời">
                                                <i class="bx bx-reply"></i>
                                            </button>

                                            {{-- Nút Xóa --}}
                                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Xóa toàn bộ đánh giá này?')" title="Xóa">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        {{-- Modal Trả lời --}}
                                        <div class="modal fade" id="replyModal{{ $review->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <form action="{{ route('admin.reviews.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="parent_id" value="{{ $review->id }}">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Trả lời: {{ $review->user->name ?? 'User' }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="mb-2 p-2 bg-light fst-italic border small">"{{ $review->content }}"</p>
                                                            <textarea name="content" class="form-control" rows="3" placeholder="Nhập câu trả lời..." required></textarea>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-success">Gửi phản hồi</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Không tìm thấy đánh giá nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                <div class="mt-4 d-flex justify-content-end">
                    {{ $reviews->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection