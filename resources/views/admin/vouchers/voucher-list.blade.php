@extends('admin.layouts.main_nav')
@section('title', 'Danh sách mã giảm giá')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        {{-- Header Section --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                    <h4 class="page-title">Mã giảm giá</h4>
                    <div class="page-title-right">
                        <a href="{{route('admin.vouchers.create')}}" class="btn btn-primary">
                            <iconify-icon icon="solar:add-circle-bold-duotone" class="me-1"></iconify-icon>
                            Thêm mã giảm giá
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
                        <h5 class="card-title mb-0">Danh sách mã giảm giá</h5>
                        <div class="card-toolbar">
                            <span class="badge bg-light-primary text-primary">
                                Tổng: {{ $vouchers->total() ?? count($vouchers) }}
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">STT</th>
                                    <th style="width: 120px;">Mã code</th>
                                    <th style="width: 100px;">Loại</th>
                                    <th style="width: 100px;">Giá trị</th>
                                    <th style="width: 120px;">Lượt dùng</th>
                                    <th style="width: 150px;">Thời gian</th>
                                    <th style="width: 100px;">Trạng thái</th>
                                    <th style="width: 160px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vouchers as $index => $voucher)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">#{{ $voucher->id }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-info text-info fw-semibold">
                                                {{ $voucher->code }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($voucher->type === 'fixed')
                                                <span class="badge bg-light-warning text-warning">
                                                    <iconify-icon icon="solar:wallet-money-bold-duotone" class="me-1"></iconify-icon>
                                                    Cố định
                                                </span>
                                            @else
                                                <span class="badge bg-light-success text-success">
                                                    <iconify-icon icon="solar:percent-bold-duotone" class="me-1"></iconify-icon>
                                                    Phần trăm
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold">
                                                @if($voucher->type === 'fixed')
                                                    {{ number_format($voucher->value, 0, ',', '.') }}₫
                                                @else
                                                    {{ $voucher->value }}%
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress" style="width: 100px; height: 20px;">
                                                    @php
                                                        $percentage = $voucher->max_uses > 0 
                                                            ? ($voucher->used_count / $voucher->max_uses) * 100 
                                                            : 0;
                                                    @endphp
                                                    <div class="progress-bar {{ $percentage >= 80 ? 'bg-danger' : ($percentage >= 50 ? 'bg-warning' : 'bg-success') }}" 
                                                         role="progressbar" 
                                                         style="width: {{ $percentage }}%"
                                                         aria-valuenow="{{ $percentage }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $voucher->used_count }}/{{ $voucher->max_uses }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $voucher->starts_at->format('d/m/Y') }} - 
                                                {{ $voucher->ends_at->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($voucher->is_active && now()->between($voucher->starts_at, $voucher->ends_at))
                                                <span class="badge bg-light-success text-success">
                                                    <iconify-icon icon="solar:check-circle-bold-duotone" class="me-1"></iconify-icon>
                                                    Hoạt động
                                                </span>
                                            @elseif($voucher->is_active && now()->isBefore($voucher->starts_at))
                                                <span class="badge bg-light-info text-info">
                                                    <iconify-icon icon="solar:clock-circle-bold-duotone" class="me-1"></iconify-icon>
                                                    Sắp tới
                                                </span>
                                            @elseif($voucher->is_active && now()->isAfter($voucher->ends_at))
                                                <span class="badge bg-light-secondary text-secondary">
                                                    <iconify-icon icon="solar:close-circle-bold-duotone" class="me-1"></iconify-icon>
                                                    Hết hạn
                                                </span>
                                            @else
                                                <span class="badge bg-light-danger text-danger">
                                                    <iconify-icon icon="solar:eye-closed-bold-duotone" class="me-1"></iconify-icon>
                                                    Tắt
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.vouchers.show', $voucher->id) }}" 
                                                   class="btn btn-sm btn-icon btn-light" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-title="Xem chi tiết">
                                                    <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                                </a>
                                                <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" 
                                                   class="btn btn-sm btn-icon btn-light" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-title="Chỉnh sửa">
                                                    <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon>
                                                </a>
                                                <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" 
                                                      method="POST" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('Bạn chắc chắn muốn xóa mã này?')">
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
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <iconify-icon icon="solar:inbox-bold-duotone" style="font-size: 3rem; opacity: 0.5;"></iconify-icon>
                                                <p class="mt-3 mb-0">Chưa có mã giảm giá nào</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($vouchers->hasPages())
                        <div class="card-footer">
                            {{ $vouchers->links('pagination::bootstrap-5') }}
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

    /* Badge colors */
    .badge.bg-light-success {
        background-color: #d1f8ea !important;
        color: #0f7e4f !important;
    }

    .badge.bg-light-danger {
        background-color: #f8d7da !important;
        color: #721c24 !important;
    }

    .badge.bg-light-warning {
        background-color: #fff3cd !important;
        color: #856404 !important;
    }

    .badge.bg-light-info {
        background-color: #d1ecf1 !important;
        color: #0c5460 !important;
    }

    .badge.bg-light-secondary {
        background-color: #e2e3e5 !important;
        color: #383d41 !important;
    }

    .badge.bg-light-primary {
        background-color: #cfe2ff !important;
        color: #084298 !important;
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

    .progress {
        background-color: #e9ecef;
        border-radius: 0.25rem;
        overflow: hidden;
    }

    .progress-bar {
        transition: width 0.6s ease;
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

        .table {
            font-size: 0.875rem;
        }

        .d-flex.gap-2 {
            flex-wrap: wrap;
        }
    }

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