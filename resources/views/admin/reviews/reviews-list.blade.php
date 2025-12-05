@extends('admin.layouts.main_nav')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Quản lý Đánh giá</h4>
                </div>
            </div>
        </div>

        {{-- 💡 FORM LỌC (FILTER) --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    
                    {{-- 1. Lọc Sản phẩm (Select từ DB) --}}
                    <div class="col-md-3">
                        <label class="form-label">Sản phẩm</label>
                        <select name="product_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Tất cả sản phẩm --</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                                    {{ $prod->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. Lọc Biến thể --}}
                    <div class="col-md-3">
                        <label class="form-label">Biến thể (Size/Màu)</label>
                        <select name="variant_id" class="form-select" {{ empty($variants) ? 'disabled' : '' }}>
                            <option value="">-- Tất cả biến thể --</option>
                            @if(!empty($variants))
                                @foreach($variants as $var)
                                    <option value="{{ $var->id }}" {{ request('variant_id') == $var->id ? 'selected' : '' }}>
                                        {{ $var->title }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @if(empty($variants) && !request('product_id'))
                            <small class="text-muted">Vui lòng chọn sản phẩm trước</small>
                        @endif
                    </div>

                    {{-- 3. Lọc Số sao --}}
                    <div class="col-md-2">
                        <label class="form-label">Đánh giá</label>
                        <select name="rating" class="form-select">
                            <option value="">Tất cả sao</option>
                            @for($i=5; $i>=1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected':'' }}>{{ $i }} Sao</option>
                            @endfor
                        </select>
                    </div>

                    {{-- 4. Khoảng thời gian --}}
                    <div class="col-md-2">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>

                    {{-- Nút Submit --}}
                    <div class="col-12 text-end">
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-light me-2">Đặt lại</a>
                        <button type="submit" class="btn btn-primary">Lọc kết quả</button>
                    </div>
                </form>
            </div>
        </div>
        
        <hr>

        {{-- 📚 DANH SÁCH DẠNG TABLE (Đã gộp và cập nhật) --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 200px;">Sản phẩm / Biến thể</th>
                                <th style="width: 150px;">Khách hàng</th>
                                <th style="width: 300px;">Nội dung đánh giá</th>
                                <th>Phản hồi từ Admin</th>
                                <th style="width: 120px;">Thời gian</th>
                                <th style="width: 100px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reviews as $key => $review)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    
                                    {{-- Cột Sản phẩm (ĐÃ LOẠI BỎ VARIANT ID) --}}
                                    <td>
                                        <h6 class="mb-1 text-primary">{{ $review->product->name ?? 'Sản phẩm đã xóa' }}</h6>
                                        @if(optional($review->variant)->title)
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                                Biến thể: **{{ $review->variant->title }}**
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Cột Khách hàng --}}
                                    <td>
                                        <span class="fw-semibold">{{ $review->user->name ?? 'Ẩn danh' }}</span>
                                    </td>

                                    {{-- Cột Nội dung Review --}}
                                    <td>
                                        <div class="mb-2 text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="mb-2 text-dark fst-italic">"{{ $review->content }}"</p>
                                        
                                        {{-- Hiển thị ảnh review (giữ nguyên) --}}
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

                                    {{-- Cột Phản hồi (Replies) (giữ nguyên) --}}
                                    <td>
                                        @foreach($review->replies as $reply)
                                            <div class="bg-light p-2 rounded mb-2 border border-start-0 border-end-0 border-top-0 border-bottom-0 border-start border-3 border-info">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <small class="fw-bold text-info">Admin:</small>
                                                    <div>
                                                        {{-- Nút Sửa Reply --}}
                                                        <button class="btn btn-sm btn-link text-warning p-0 me-2" 
                                                                data-bs-toggle="modal" data-bs-target="#editReplyModal{{ $reply->id }}">
                                                            <i class="bx bx-edit"></i>
                                                        </button>
                                                        {{-- Nút Xóa Reply --}}
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

                                            {{-- Modal Sửa Reply (giữ nguyên) --}}
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
                                            {{-- Nút mở Modal Trả lời --}}
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#replyModal{{ $review->id }}" title="Trả lời">
                                                <i class="bx bx-reply"></i>
                                            </button>

                                            {{-- Nút Xóa Review Gốc --}}
                                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Xóa toàn bộ đánh giá này?')" title="Xóa">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        {{-- Modal Trả lời Review (giữ nguyên) --}}
                                        <div class="modal fade" id="replyModal{{ $review->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <form action="{{ route('admin.reviews.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $review->product_id }}">
                                                    <input type="hidden" name="parent_id" value="{{ $review->id }}">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Trả lời khách hàng: {{ $review->user->name ?? 'Ẩn danh' }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="mb-2 p-2 bg-light fst-italic">"{{ $review->content }}"</p>
                                                            <label class="form-label">Nội dung phản hồi:</label>
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
                                    <td colspan="7" class="text-center text-muted py-4">Không tìm thấy đánh giá nào phù hợp.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PHÂN TRANG (CURSOR PAGINATION) --}}
                <div class="mt-4 d-flex justify-content-end">
                    {{ $reviews->links() }} 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection