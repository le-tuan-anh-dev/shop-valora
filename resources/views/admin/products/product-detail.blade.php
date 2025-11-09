@extends('admin.layouts.main_nav')
@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="container py-4">

    <a href="{{ route('admin.products.list') }}" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách
    </a>

    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <div class="row">
                {{-- Ảnh sản phẩm --}}
                <div class="col-md-4 text-center">
                    @if($product->image_main)
                        <img src="{{ asset('storage/' . $product->image_main) }}" class="img-fluid rounded mb-3" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/300" class="img-fluid rounded mb-3" alt="No image">
                    @endif
                </div>

                {{-- Thông tin sản phẩm --}}
                <div class="col-md-8">
                    <h3 class="fw-bold">{{ $product->name }}</h3>
                    <p class="text-muted mb-1">
                        <strong>Danh mục:</strong> {{ $product->category->name ?? 'Chưa phân loại' }}
                    </p>
                    <p class="text-muted mb-1">
                        <strong>Mô tả:</strong> {{ $product->description ?? '—' }}
                    </p>
                    <p class="mb-1">
                        <strong>Giá gốc:</strong> {{ number_format($product->base_price, 0, ',', '.') }}₫
                        @if($product->discount_price)
                            <span class="text-danger ms-2">
                                <strong>Giá khuyến mãi:</strong> {{ number_format($product->discount_price, 0, ',', '.') }}₫
                            </span>
                        @endif
                    </p>
                    <p class="mb-1"><strong>Tồn kho:</strong> {{ $product->stock }}</p>
                    <p class="mb-1">
                        <strong>Trạng thái:</strong>
                        @if($product->is_active)
                            <span class="badge bg-success">Hiển thị</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </p>
                    <p class="mb-1"><strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
                    <p class="mb-3"><strong>Ngày cập nhật:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</p>

                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning me-2">
                        <i class="bi bi-pencil-square"></i> Chỉnh sửa
                    </a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </form>
                </div>
            </div>

            {{-- Biến thể sản phẩm --}}
            <div class="mt-4">
                <h5>Biến thể sản phẩm</h5>
                @if($product->variants->count() > 0)
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>SKU</th>
                                <th>Tiêu đề</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variants as $v)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $v->sku ?? '—' }}</td>
                                    <td>{{ $v->title }}</td>
                                    <td>{{ number_format($v->price, 0, ',', '.') }}₫</td>
                                    <td>{{ $v->stock }}</td>
                                    <td>
                                        @if($v->is_active)
                                            <span class="badge bg-success">Hiển thị</span>
                                        @else
                                            <span class="badge bg-secondary">Ẩn</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">Sản phẩm chưa có biến thể nào.</p>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- SweetAlert xác nhận xóa --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener('submit', function (e) {
        if (e.target.classList.contains('delete-form')) {
            e.preventDefault();
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa?',
                text: "Sản phẩm sẽ bị xóa vĩnh viễn khỏi hệ thống!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        }
    });
});
</script>
@endpush

@endsection
