@extends('admin.layouts.main_nav')
@section('title', 'Danh sách sản phẩm')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        {{-- Header Section --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                    <h4 class="page-title">Danh sách sản phẩm</h4>
                    <div class="page-title-right">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <iconify-icon icon="solar:add-circle-bold-duotone" class="me-1"></iconify-icon>
                            Thêm sản phẩm
                        </a>
                    </div>
                </div>
            </div>
        </div>

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

        {{-- Main Content --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- Search & Filters --}}
                    <div class="card-header bg-white border-bottom">
                        <form method="GET" action="{{ route('admin.products.list') }}" class="row g-3 align-items-end">
                            <div class="col-lg-8 col-md-6">
                                <label class="form-label fw-semibold mb-2">
                                    <iconify-icon icon="solar:magnifer-linear" class="me-1"></iconify-icon>
                                    Tìm kiếm
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <iconify-icon icon="solar:magnifer-linear" class="text-muted"></iconify-icon>
                                    </span>
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="Tìm kiếm theo tên hoặc mô tả..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <button class="btn btn-primary w-100" type="submit">
                                    <iconify-icon icon="solar:magnifer-linear" class="me-1"></iconify-icon>
                                    Tìm kiếm
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Product Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th style="width: 60px;">Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th style="width: 140px;">Danh mục</th>
                                    <th style="width: 140px;">Thương hiệu</th>
                                    <th class="text-end" style="width: 110px;">Giá nhập</th>
                                    <th class="text-end" style="width: 110px;">Giá bán </th>
                                    <th class="text-end" style="width: 110px;">Giá KM</th>
                                    <th class="text-center" style="width: 90px;">Tồn kho</th>
                                    <th class="text-center" style="width: 100px;">Trạng thái</th>
                                    <th class="text-center" style="width: 140px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $key => $item)
                                    <tr>
                                        {{-- STT --}}
                                        <td class="text-center fw-semibold text-muted">
                                            {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                                        </td>

                                        {{-- Image --}}
                                        <td>
                                            @if ($item->image_main)
                                                <img src="{{ asset('storage/' . $item->image_main) }}" 
                                                     alt="Product" 
                                                     class="rounded" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <iconify-icon icon="solar:image-broken" class="text-muted fs-20"></iconify-icon>
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Name & Description --}}
                                        <td>
                                            <h6 class="fw-semibold mb-1">{{ $item->name }}</h6>
                                            @if($item->description)
                                                <p class="text-muted mb-0 fs-12">{{ Str::limit($item->description, 50) }}</p>
                                            @endif
                                        </td>

                                        {{-- Category --}}
                                        <td>
                                            @if($item->category)
                                                <span class="badge bg-light-info text-info">
                                                    {{ $item->category->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        {{-- Brand --}}
                                            <td>
                                                @if($item->brand)
                                                    <span class="badge bg-light-primary text-primary">
                                                        {{ $item->brand->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        <td class="text-end fw-semibold">
                                            {{ number_format($item->cost_price, 0, ',', '.') }}₫
                                        </td>



                                        {{-- Base Price --}}
                                        <td class="text-end fw-semibold">
                                            {{ number_format($item->base_price, 0, ',', '.') }}₫
                                        </td>

                                        {{-- Discount Price --}}
                                        <td class="text-end">
                                            @if ($item->discount_price)
                                                <div class="fw-semibold text-danger">
                                                    {{ number_format($item->discount_price, 0, ',', '.') }}₫
                                                </div>
                                                @php
                                                    $discount = round((($item->base_price - $item->discount_price) / $item->base_price) * 100);
                                                @endphp
                                               
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- Stock --}}
                                        <td class="text-center">
                                            @if($item->stock == 0)
                                                <span class="badge bg-light-danger text-danger">
                                                    <iconify-icon icon="solar:close-circle-linear" class="me-1"></iconify-icon>
                                                    Hết
                                                </span>
                                            @elseif($item->stock <= 10)
                                                <span class="badge bg-light-danger text-danger">
                                                    {{ $item->stock }}
                                                </span>
                                            @elseif($item->stock <= 50)
                                                <span class="badge bg-light-warning text-warning">
                                                    {{ $item->stock }}
                                                </span>
                                            @else
                                                <span class="badge bg-light-success text-success">
                                                    {{ $item->stock }}
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Status --}}
                                        <td class="text-center">
                                            @if ($item->is_active)
                                                <span class="badge bg-light-success text-success">
                                                    <iconify-icon icon="solar:check-circle-bold-duotone" class="me-1"></iconify-icon>
                                                    Hiển thị
                                                </span>
                                            @else
                                                <span class="badge bg-light-secondary text-secondary">
                                                    <iconify-icon icon="solar-eye-closed-bold-duotone" class="me-1"></iconify-icon>
                                                    Ẩn
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('admin.products.show', $item->id) }}" 
                                                   class="btn btn-sm btn-icon btn-light" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-title="Xem chi tiết">
                                                    <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $item->id) }}" 
                                                   class="btn btn-sm btn-icon btn-light" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-title="Chỉnh sửa">
                                                    <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon>
                                                </a>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="mb-3">
                                                <iconify-icon icon="solar:inbox-bold-duotone" style="font-size: 3rem; opacity: 0.5;"></iconify-icon>
                                            </div>
                                            <h5 class="text-muted">Không có sản phẩm nào</h5>
                                            <p class="text-muted mb-3">Bắt đầu thêm sản phẩm mới vào hệ thống</p>
                                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                                <iconify-icon icon="solar:add-circle-bold-duotone" class="me-1"></iconify-icon>
                                                Thêm sản phẩm mới
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Info --}}
                    @if($products->hasPages())
                        <div class="card-footer bg-light">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <div class="text-muted">
                                        Hiển thị <span class="fw-semibold">{{ $products->firstItem() }}</span> 
                                        đến <span class="fw-semibold">{{ $products->lastItem() }}</span> 
                                        trong tổng <span class="fw-semibold">{{ $products->total() }}</span> sản phẩm
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-end">
                                        {{ $products->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

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
        border-bottom: 1px solid #e9ecef;
        margin-bottom: 1.5rem !important;
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
    .text-sm.text-gray-700 {
        display: none !important;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 1rem 1.5rem;
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

    .table-light th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
    }

    /* Badge colors */
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

    .badge.bg-light-secondary {
        background-color: #e2e3e5 !important;
        color: #383d41 !important;
    }

    /* Button icon */
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 0.25rem;
    }

    .btn-light {
        background-color: #f8f9fa;
        border-color: #e9ecef;
        color: #6c757d;
    }

    .btn-light:hover {
        background-color: #e2e6ea;
        border-color: #dae0e5;
        color: #495057;
    }

    /* Input group */
    .input-group-text {
        background-color: white !important;
        border-color: #e9ecef;
        color: #6c757d;
    }

    .input-group .form-control {
        border-color: #e9ecef;
    }

    .input-group .form-control:focus {
        border-color: #084298;
        box-shadow: 0 0 0 0.25rem rgba(8, 66, 152, 0.1);
    }

    /* Alert */
    .alert {
        border: none;
        border-radius: 0.375rem;
    }

    .alert-success {
        background-color: rgba(10, 179, 156, 0.1);
        color: #0f7e4f;
    }

    .alert-danger {
        background-color: rgba(240, 101, 72, 0.1);
        color: #f06548;
    }

    /* Form label */
    .form-label {
        font-size: 0.95rem;
        color: #212529;
    }

    .form-control {
        border-color: #e9ecef;
        padding: 0.625rem 0.875rem;
    }

    .form-control:focus {
        border-color: #084298;
        box-shadow: 0 0 0 0.25rem rgba(8, 66, 152, 0.1);
    }

    /* Button */
    .btn-primary {
        background-color: #084298;
        border-color: #084298;
    }

    .btn-primary:hover {
        background-color: #0a3272;
        border-color: #0a3272;
    }

    /* Pagination */
    .pagination {
        margin: 0;
    }

    .pagination .page-link {
        color: #084298;
        border-color: #e9ecef;
    }

    .pagination .page-link:hover {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    .pagination .page-link.active {
        background-color: #084298;
        border-color: #084298;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table {
            font-size: 0.875rem;
        }

        .btn-icon {
            width: 28px;
            height: 28px;
        }

        .d-flex.gap-2 {
            gap: 0.5rem !important;
        }
    }

    @media (max-width: 576px) {
        .page-title-box {
            flex-direction: column;
        }

        .page-title-right {
            width: 100%;
            margin-top: 1rem;
        }

        .page-title-right .btn {
            width: 100%;
        }

        .card-header {
            padding: 1rem;
        }

        .card-footer {
            padding: 1rem;
        }

        .table-responsive {
            font-size: 0.8rem;
        }

        .row.g-3 {
            gap: 0.75rem !important;
        }

        .col-lg-8,
        .col-lg-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
    }
</style>
@endpush

{{-- Scripts --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // SweetAlert Delete Confirmation
    document.body.addEventListener('submit', function(e) {
        if (e.target.classList.contains('delete-form')) {
            e.preventDefault();
            const form = e.target;
            
            Swal.fire({
                title: 'Xóa sản phẩm?',
                text: "Sản phẩm sẽ bị xóa vĩnh viễn và không thể khôi phục!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="me-1"></iconify-icon> Xóa',
                cancelButtonText: '<iconify-icon icon="solar:close-circle-linear" class="me-1"></iconify-icon> Hủy',
                confirmButtonColor: '#f06548',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false,
                didOpen: () => {
                    const confirmBtn = Swal.getConfirmButton();
                    const cancelBtn = Swal.getCancelButton();
                    if (confirmBtn) confirmBtn.style.marginRight = '8px';
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
        
    });
});
</script>
@endpush

@endsection