@extends('admin.layouts.main_nav')
@section('title', 'Danh sách danh mục')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        {{-- Header Section --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                    <h4 class="page-title">Danh mục sản phẩm</h4>
                    <div class="page-title-right">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                            <iconify-icon icon="solar:add-circle-bold-duotone" class="me-1"></iconify-icon>
                            Thêm danh mục
                        </a>
                    </div>
                </div>
            </div>
        </div>



        {{-- Alert Messages --}}
@if(session('success'))
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <iconify-icon icon="solar:check-circle-bold-duotone" class="me-2"></iconify-icon>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
@elseif(session('error'))
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <iconify-icon icon="solar:danger-bold-duotone" class="me-2"></iconify-icon>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

        {{-- Table Card --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Danh sách danh mục</h5>
                        <div class="card-toolbar">
                            <span class="badge bg-light-primary text-primary">
                                Tổng: {{ $categories->total() ?? count($categories) }}
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Tên danh mục</th>
                                    <th style="width: 200px;">Danh mục cha</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 160px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $c)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">#{{ $c->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">{{ $c->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($c->parent)
                                                <span class="badge bg-light-info text-info">
                                                    {{ $c->parent->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($c->is_active)
                                                <span class="badge bg-light-success text-success">
                                                    <iconify-icon icon="solar:check-circle-bold-duotone" class="me-1"></iconify-icon>
                                                    Hiển thị
                                                </span>
                                            @else
                                                <span class="badge bg-light-secondary text-secondary">
                                                    <iconify-icon icon="solar:eye-closed-bold-duotone" class="me-1"></iconify-icon>
                                                    Ẩn
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.categories.edit', $c->id) }}" 
                                                   class="btn btn-sm btn-icon btn-light" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-title="Chỉnh sửa">
                                                    <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon>
                                                </a>
                                                <form action="{{ route('admin.categories.destroy', $c->id) }}" 
                                                      method="POST" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('Bạn chắc chắn muốn xóa danh mục này?')">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-icon btn-light" 
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-title="Xóa">
                                                        <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="text-danger"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <iconify-icon icon="solar:inbox-bold-duotone" style="font-size: 3rem; opacity: 0.5;"></iconify-icon>
                                                <p class="mt-3 mb-0">Chưa có danh mục nào</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($categories->hasPages())
                        <div class="card-footer">
                            {{ $categories->links('pagination::bootstrap-5') }}
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
        padding: 1rem 1.5rem;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 600;
    }

    .table > :not(caption) > * > * {
        padding: 0.85rem 0.75rem;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    /* Badge colors - Larkon style */
    .badge.bg-light-success {
        background-color: #d1f8ea !important;
        color: #0f7e4f !important;
    }

    .badge.bg-light-info {
        background-color: #d1ecf1 !important;
        color: #0c5460 !important;
    }

    .badge.bg-light-primary {
        background-color: #cfe2ff !important;
        color: #084298 !important;
    }

    .badge.bg-light-secondary {
        background-color: #e2e3e5 !important;
        color: #383d41 !important;
    }

    /* Button icon styles */
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
        color: #6c757d;
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    .btn-light:hover {
        color: #495057;
        background-color: #e2e6ea;
        border-color: #dae0e5;
    }

    /* Card footer pagination */
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 1rem 1.5rem;
    }

    @media (max-width: 768px) {
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

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
        }

        .table {
            font-size: 0.875rem;
        }

        .d-flex.gap-2 {
            flex-wrap: wrap;
        }
    }

    /* Responsive table */
    @media (max-width: 576px) {
        .table-responsive {
            font-size: 0.8rem;
        }

        .btn-icon {
            width: 28px;
            height: 28px;
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@endsection