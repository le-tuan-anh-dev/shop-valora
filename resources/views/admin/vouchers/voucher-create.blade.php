@extends('admin.layouts.main_nav')
@section('title', 'Thêm mã giảm giá')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        {{-- Header Section --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                    <h4 class="page-title">Thêm mã giảm giá mới</h4>
                    <div class="page-title-right">
                        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">
                            <iconify-icon icon="solar:arrow-left-bold-duotone" class="me-1"></iconify-icon>
                            Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin mã giảm giá</h5>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Có lỗi xảy ra!</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.vouchers.store') }}" 
                              method="POST" 
                              novalidate
                              id="voucherForm">
                            @csrf

                            {{-- Mã Code --}}
                            <div class="mb-3">
                                <label for="code" class="form-label">Mã Code <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('code') is-invalid @enderror" 
                                       id="code" 
                                       name="code" 
                                       placeholder="VD: SUMMER20, SALE10"
                                       value="{{ old('code') }}"
                                       required
                                       style="text-transform: uppercase;">
                                <small class="form-text text-muted">Mã sẽ được tự động chuyển thành chữ hoa</small>
                                @error('code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Loại Giảm Giá --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="type" class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                                        <select class="form-select @error('type') is-invalid @enderror" 
                                                id="type" 
                                                name="type" 
                                                required
                                                onchange="updateValueLabel()">
                                            <option value="">-- Chọn loại --</option>
                                            <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>
                                                Cố định (₫)
                                            </option>
                                            <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>
                                                Phần trăm (%)
                                            </option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Giá Trị --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="value" class="form-label">
                                            Giá trị <span id="valueLabel"></span> <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   class="form-control @error('value') is-invalid @enderror" 
                                                   id="value" 
                                                   name="value" 
                                                   placeholder="0"
                                                   value="{{ old('value') }}"
                                                   step="0.01"
                                                   min="0"
                                                   required>
                                            <span class="input-group-text" id="valueUnit">₫</span>
                                        </div>
                                        @error('value')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Tổng Lượt Dùng & Giới Hạn/Người --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_uses" class="form-label">Tổng lượt sử dụng <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               class="form-control @error('max_uses') is-invalid @enderror" 
                                               id="max_uses" 
                                               name="max_uses" 
                                               placeholder="Ví dụ: 100"
                                               value="{{ old('max_uses') }}"
                                               min="1"
                                               required>
                                        @error('max_uses')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="per_user_limit" class="form-label">Giới hạn dùng/người <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               class="form-control @error('per_user_limit') is-invalid @enderror" 
                                               id="per_user_limit" 
                                               name="per_user_limit" 
                                               placeholder="Ví dụ: 1"
                                               value="{{ old('per_user_limit', 1) }}"
                                               min="1"
                                               required>
                                        @error('per_user_limit')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Ngày Bắt Đầu & Kết Thúc --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="starts_at" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                        <input type="datetime-local" 
                                               class="form-control @error('starts_at') is-invalid @enderror" 
                                               id="starts_at" 
                                               name="starts_at" 
                                               value="{{ old('starts_at') }}"
                                               required>
                                        @error('starts_at')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ends_at" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                        <input type="datetime-local" 
                                               class="form-control @error('ends_at') is-invalid @enderror" 
                                               id="ends_at" 
                                               name="ends_at" 
                                               value="{{ old('ends_at') }}"
                                               required>
                                        @error('ends_at')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Áp Dụng Cho Sản Phẩm --}}
                            <div class="mb-3">
                                <label for="applicable_variant_id" class="form-label">Áp dụng cho sản phẩm (Tùy chọn)</label>
                                <select class="form-select @error('applicable_variant_id') is-invalid @enderror" 
                                        id="applicable_variant_id" 
                                        name="applicable_variant_id">
                                    <option value="">-- Áp dụng cho tất cả sản phẩm --</option>
                                    @foreach($variants as $variant)
                                        <option value="{{ $variant->id }}" {{ old('applicable_variant_id') == $variant->id ? 'selected' : '' }}>
                                            {{ $variant->product->name ?? 'Sản phẩm' }} - {{ $variant->sku }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('applicable_variant_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Gán Cho Người Dùng --}}
                            <div class="mb-3">
                                <label for="assigned_user_id" class="form-label">Gán cho người dùng (Tùy chọn)</label>
                                <select class="form-select @error('assigned_user_id') is-invalid @enderror" 
                                        id="assigned_user_id" 
                                        name="assigned_user_id">
                                    <option value="">-- Không gán cho ai cả --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_user_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Trạng Thái --}}
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1"
                                           @if(old('is_active', true)) checked @endif>
                                    <label class="form-check-label" for="is_active">
                                        Kích hoạt mã giảm giá này
                                    </label>
                                </div>
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="d-flex gap-2 pt-3 border-top">
                                <button type="submit" class="btn btn-primary">
                                    <iconify-icon icon="solar:check-circle-bold-duotone" class="me-1"></iconify-icon>
                                    Tạo mới
                                </button>
                                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light">
                                    <iconify-icon icon="solar:close-circle-bold-duotone" class="me-1"></iconify-icon>
                                    Hủy
                                </a>
                            </div>
                        </form>
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

    .card-body {
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border: 1px solid #e9ecef;
        border-radius: 0.25rem;
        font-size: 0.95rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #084298;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
    }

    .btn {
        font-weight: 500;
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
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

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .form-check-input {
        width: 1.25em;
        height: 1.25em;
        margin-top: 0.3125em;
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    .alert {
        border-radius: 0.25rem;
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
    }
</style>
@endpush

{{-- Scripts --}}
@push('scripts')
<script>
    function updateValueLabel() {
        const type = document.getElementById('type').value;
        const valueLabel = document.getElementById('valueLabel');
        const valueUnit = document.getElementById('valueUnit');
        const valueInput = document.getElementById('value');

        if (type === 'fixed') {
            valueLabel.textContent = '(₫)';
            valueUnit.textContent = '₫';
            valueInput.step = '1000';
        } else if (type === 'percent') {
            valueLabel.textContent = '(%)';
            valueUnit.textContent = '%';
            valueInput.step = '0.01';
            valueInput.max = '100';
        } else {
            valueLabel.textContent = '';
            valueUnit.textContent = '';
        }
    }

    // Khởi tạo label khi load trang
    document.addEventListener('DOMContentLoaded', function() {
        updateValueLabel();
    });
</script>
@endpush

@endsection