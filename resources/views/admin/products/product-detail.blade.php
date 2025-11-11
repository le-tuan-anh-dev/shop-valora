@extends('admin.layouts.main_nav')
@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        {{-- Header Section --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{ route('admin.products.list') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
                        </a>
                    </div>
                    <h4 class="page-title">Chi tiết sản phẩm</h4>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-4">
                            {{-- Product Image Section --}}
                            <div class="col-lg-4 col-md-5">
                                <div class="bg-light rounded-2 p-3 text-center" style="min-height: 350px; display: flex; align-items: center; justify-content: center;">
                                    @if($product->image_main)
                                        <img src="{{ asset('storage/' . $product->image_main) }}" 
                                             class="img-fluid rounded-2" 
                                             alt="{{ $product->name }}"
                                             style="max-height: 320px; object-fit: cover;">
                                    @else
                                        <div class="text-center">
                                            <i class="bi bi-image display-4 text-muted"></i>
                                            <p class="text-muted mt-2">Chưa có ảnh</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Product Info Section --}}
                            <div class="col-lg-8 col-md-7">
                                {{-- Product Name --}}
                                <h3 class="fw-bold mb-3">{{ $product->name }}</h3>

                                {{-- Product Details --}}
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div>
                                            <p class="text-muted mb-1">
                                                <i class="bi bi-tag me-2"></i><strong>Danh mục</strong>
                                            </p>
                                            <p class="ps-4">{{ $product->category->name ?? 'Chưa phân loại' }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div>
                                            <p class="text-muted mb-1">
                                                <i class="bi bi-info-circle me-2"></i><strong>Trạng thái</strong>
                                            </p>
                                            <p class="ps-4">
                                                @if($product->is_active)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i> Hiển thị
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-eye-slash me-1"></i> Ẩn
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div>
                                            <p class="text-muted mb-1">
                                                <i class="bi bi-tag-fill me-2"></i><strong>Giá gốc</strong>
                                            </p>
                                            <p class="ps-4 fw-semibold">{{ number_format($product->base_price, 0, ',', '.') }}₫</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div>
                                            <p class="text-muted mb-1">
                                                <i class="bi bi-percent me-2"></i><strong>Giá khuyến mãi</strong>
                                            </p>
                                            <p class="ps-4">
                                                @if($product->discount_price)
                                                    <span class="text-danger fw-semibold">
                                                        {{ number_format($product->discount_price, 0, ',', '.') }}₫
                                                    </span>
                                                    <span class="badge bg-danger-subtle text-danger ms-2">
                                                        -{{ round((($product->base_price - $product->discount_price) / $product->base_price) * 100) }}%
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div>
                                            <p class="text-muted mb-1">
                                                <i class="bi bi-box me-2"></i><strong>Tồn kho</strong>
                                            </p>
                                            <p class="ps-4 fw-semibold">
                                                @if($product->stock > 0)
                                                    <span class="text-success">{{ $product->stock }}</span>
                                                @else
                                                    <span class="text-danger">{{ $product->stock }} (Hết hàng)</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div>
                                            <p class="text-muted mb-1">
                                                <i class="bi bi-calendar me-2"></i><strong>Cập nhật</strong>
                                            </p>
                                            <p class="ps-4">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Description --}}
                                @if($product->description)
                                    <div class="mb-4">
                                        <p class="text-muted mb-2">
                                            <i class="bi bi-file-text me-2"></i><strong>Mô tả</strong>
                                        </p>
                                        <div class="ps-4 bg-light p-3 rounded-2">
                                            {{ $product->description }}
                                        </div>
                                    </div>
                                @endif

                                {{-- Action Buttons --}}
                                <div class="d-flex flex-wrap gap-2 mt-4">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil-square me-1"></i> Chỉnh sửa
                                    </a>
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                        <i class="bi bi-trash me-1"></i> Xóa
                                    </button>
                                    <a href="{{ route('admin.products.list') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x me-1"></i> Đóng
                                    </a>
                                </div>

                                {{-- Hidden Delete Form --}}
                                <form id="deleteForm" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Product Variants Section --}}
                <div class="card">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-list-check me-2"></i>Biến thể sản phẩm
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($product->variants->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">#</th>
                                            <th>SKU</th>
                                            <th>Tiêu đề</th>
                                            <th class="text-end">Giá</th>
                                            <th class="text-center">Tồn kho</th>
                                            <th class="text-center">Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variants as $v)
                                            <tr>
                                                <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                                <td>
                                                    <code class="bg-light px-2 py-1 rounded">{{ $v->sku ?? '—' }}</code>
                                                </td>
                                                <td>{{ $v->title }}</td>
                                                <td class="text-end fw-semibold">
                                                    {{ number_format($v->price, 0, ',', '.') }}₫
                                                </td>
                                                <td class="text-center">
                                                    @if($v->stock > 0)
                                                        <span class="badge badge-soft-success">{{ $v->stock }}</span>
                                                    @else
                                                        <span class="badge badge-soft-danger">{{ $v->stock }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($v->is_active)
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle"></i>
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            <i class="bi bi-x-circle"></i>
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <p class="text-muted mt-2">Sản phẩm chưa có biến thể nào</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- Styles --}}
@push('styles')
<style>
    .page-content {
        padding: 20px 0;
    }

    .page-title-box {
        padding-bottom: 20px;
    }

    .card {
        border: none;
        box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
        margin-bottom: 24px;
    }

    .table > :not(caption) > * > * {
        padding: 0.85rem 0.75rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .badge-soft-success {
        color: #0ab39c;
        background-color: rgba(10, 179, 156, 0.1);
    }

    .badge-soft-danger {
        color: #f06548;
        background-color: rgba(240, 101, 72, 0.1);
    }

    .bg-danger-subtle {
        background-color: rgba(240, 101, 72, 0.1);
    }

    @media (max-width: 768px) {
        .row.g-4 {
            gap: 1.5rem !important;
        }

        .d-flex.flex-wrap.gap-2 {
            flex-direction: column;
        }

        .d-flex.flex-wrap.gap-2 > .btn {
            width: 100%;
        }

        .table {
            font-size: 0.875rem;
        }
    }
</style>
@endpush

{{-- Scripts --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete() {
    Swal.fire({
        title: 'Xác nhận xóa?',
        text: "Sản phẩm sẽ bị xóa vĩnh viễn và không thể khôi phục!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-trash me-1"></i> Xóa',
        cancelButtonText: '<i class="bi bi-x-circle me-1"></i> Hủy',
        confirmButtonColor: '#f06548',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm').submit();
        }
    });
}
</script>
@endpush

@endsection