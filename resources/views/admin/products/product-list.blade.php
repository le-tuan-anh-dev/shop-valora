@extends('admin.layouts.main_nav')
@section('title', 'Danh sách sản phẩm')

@section('content')
    <div class="container py-4">

        {{-- Tiêu đề và thanh công cụ --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-semibold">Danh sách sản phẩm</h3>
            <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Thêm sản phẩm
            </a>
        </div>

        {{-- Thanh tìm kiếm --}}
        <form method="GET" action="{{ route('admin.products.list') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Nhập tên hoặc mô tả sản phẩm..."
                    value="{{ request('search') }}">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search"></i> Tìm kiếm
                </button>
            </div>
        </form>

        {{-- Thông báo thành công --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Bảng danh sách sản phẩm --}}
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th width="60">#</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá gốc</th>
                        <th>Giá khuyến mãi</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th width="150">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $key => $item)
                        <tr>
                            <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                            <td>
                                @if ($item->image_main)
                                    <img src="{{ asset('storage/' . $item->image_main) }}" alt="Ảnh sản phẩm" width="60"
                                        height="60" class="rounded">
                                @else
                                    <img src="https://via.placeholder.com/60" alt="No image" class="rounded">
                                @endif
                            </td>
                            <td class="text-start">{{ $item->name }}</td>
                            <td>{{ $item->category->name ?? '—' }}</td>
                            <td>{{ number_format($item->base_price, 0, ',', '.') }}₫</td>
                            <td>
                                @if ($item->discount_price)
                                    <span
                                        class="text-danger">{{ number_format($item->discount_price, 0, ',', '.') }}₫</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $item->stock }}</td>
                            <td>
                                @if ($item->is_active)
                                    <span class="badge bg-success">Hiển thị</span>
                                @else
                                    <span class="badge bg-secondary">Ẩn</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.show', $item->id) }}" class="btn btn-sm btn-info me-1"
   data-bs-toggle="tooltip" title="Xem chi tiết">
   <i class="bi bi-eye"></i>
</a>
                                <a href="{{ route('admin.products.edit', $item->id) }}" class="btn btn-sm btn-warning"
                                    data-bs-toggle="tooltip" title="Sửa">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $item->id) }}" method="POST"
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                        title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-muted py-3">Không có sản phẩm nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>

    </div>
@endsection

{{-- SweetAlert xác nhận xóa --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tooltips Bootstrap
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

            // SweetAlert xác nhận xóa
            document.body.addEventListener('submit', function(e) {
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
