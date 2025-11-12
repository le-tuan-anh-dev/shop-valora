@extends('admin.layouts.main_nav')
@section('title', 'Thêm danh mục')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        {{-- Header Section --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                    <h4 class="page-title">Thêm danh mục mới</h4>
                    <div class="page-title-right">
                        <a href="{{ route('admin.categories.list') }}" class="btn btn-secondary">
                            <iconify-icon icon="solar:arrow-left-linear" class="me-1"></iconify-icon>
                            Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if($errors->any())
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="me-2"></iconify-icon>
                        <strong>Có lỗi xảy ra!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Form Card --}}
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <iconify-icon icon="solar:add-circle-bold-duotone" class="me-2"></iconify-icon>
                            Thông tin danh mục
                        </h5>
                    </div>

                    <form method="POST" action="{{ route('admin.categories.store') }}" class="needs-validation" novalidate>
                        @csrf

                        <div class="card-body">
                            {{-- Tên danh mục --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <iconify-icon icon="solar:tag-bold-duotone" class="me-1"></iconify-icon>
                                    Tên danh mục
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}"
                                       placeholder="Nhập tên danh mục (vd: Quần Áo, Giày Dép)"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted d-block mt-2">
                                    <iconify-icon icon="solar:info-circle-linear" class="me-1"></iconify-icon>
                                    Tên danh mục phải có ít nhất 3 ký tự
                                </small>
                            </div>

                            {{-- Slug --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <iconify-icon icon="solar:link-bold-duotone" class="me-1"></iconify-icon>
                                    Slug
                                </label>
                                <input type="text" 
                                       name="slug" 
                                       class="form-control @error('slug') is-invalid @enderror" 
                                       value="{{ old('slug') }}"
                                       placeholder="Để trống để tự động sinh (vd: quan-ao)">
                                @error('slug')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted d-block mt-2">
                                    <iconify-icon icon="solar:info-circle-linear" class="me-1"></iconify-icon>
                                    Slug được dùng trong URL, chỉ dùng chữ cái thường, số và dấu gạch
                                </small>
                            </div>

                            {{-- Danh mục cha --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <iconify-icon icon="solar:hierarchy-square-bold-duotone" class="me-1"></iconify-icon>
                                    Danh mục cha
                                </label>
                                <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                    <option value="">-- Không có danh mục cha (Danh mục chính) --</option>
                                    @foreach($parents as $p)
                                        <option value="{{ $p->id }}" {{ old('parent_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted d-block mt-2">
                                    <iconify-icon icon="solar:info-circle-linear" class="me-1"></iconify-icon>
                                    Chọn một danh mục cha để tạo danh mục con. Để trống nếu đây là danh mục chính
                                </small>
                            </div>

                            {{-- Trạng thái --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-2">
                                    <iconify-icon icon="solar-eye-bold-duotone" class="me-1"></iconify-icon>
                                    Trạng thái
                                </label>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="is_active" 
                                           value="1" 
                                           id="isActive"
                                           {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">
                                        Hiển thị danh mục này trên website
                                    </label>
                                </div>
                                <small class="form-text text-muted d-block mt-2">
                                    <iconify-icon icon="solar:info-circle-linear" class="me-1"></iconify-icon>
                                    Tắt để ẩn danh mục khỏi trang web
                                </small>
                            </div>
                        </div>

                        {{-- Form Footer --}}
                        <div class="card-footer bg-light d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.categories.list') }}" class="btn btn-secondary">
                                <iconify-icon icon="solar:close-circle-linear" class="me-1"></iconify-icon>
                                Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <iconify-icon icon="solar:check-circle-bold-duotone" class="me-1"></iconify-icon>
                                Thêm danh mục
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Side Info --}}
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <iconify-icon icon="solar:bulb-bold-duotone" class="me-2"></iconify-icon>
                            Hướng dẫn
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-2">
                                <iconify-icon icon="solar:tag-bold-duotone" class="me-1"></iconify-icon>
                                Tên danh mục
                            </h6>
                            <p class="text-muted small">
                                Đây là tên sẽ hiển thị cho khách hàng. Nên đặt tên ngắn gọn, dễ hiểu.
                            </p>
                            <div class="bg-light p-2 rounded small">
                                <strong>Ví dụ:</strong> Quần áo nam, Giày nữ, Phụ kiện...
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <h6 class="fw-semibold mb-2">
                                <iconify-icon icon="solar:hierarchy-square-bold-duotone" class="me-1"></iconify-icon>
                                Danh mục cha
                            </h6>
                            <p class="text-muted small">
                                Dùng để tạo cấu trúc phân loại. Nếu chọn danh mục cha, danh mục này sẽ trở thành danh mục con.
                            </p>
                            <div class="bg-light p-2 rounded small">
                                <strong>Ví dụ:</strong><br>
                                Cha: Quần áo<br>
                                Con: Quần áo nam
                            </div>
                        </div>

                        <hr>

                        <div>
                            <h6 class="fw-semibold mb-2">
                                <iconify-icon icon="solar:eye-bold-duotone" class="me-1"></iconify-icon>
                                Trạng thái
                            </h6>
                            <p class="text-muted small">
                                Chỉ các danh mục được bật sẽ hiển thị trên website. Bạn có thể ẩn danh mục mà không cần xóa nó.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Quick Tips --}}
                <div class="card border-info">
                    <div class="card-header bg-info-light">
                        <h5 class="card-title mb-0 text-info">
                            <iconify-icon icon="solar:lightbulb-bold-duotone" class="me-2"></iconify-icon>
                            Mẹo
                        </h5>
                    </div>
                    <div class="card-body small">
                        <ul class="mb-0 ps-3">
                            <li class="mb-2">Slug sẽ tự động sinh từ tên nếu bạn không nhập</li>
                            <li class="mb-2">Bạn có thể chỉnh sửa danh mục sau khi tạo</li>
                            <li class="mb-2">Tạo danh mục cha trước, rồi tạo danh mục con</li>
                            <li>Nên tắt danh mục thay vì xóa để giữ dữ liệu sản phẩm</li>
                        </ul>
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

    .card-footer {
        border-top: 1px solid #e9ecef;
        padding: 1rem 1.5rem;
    }

    .form-label {
        margin-bottom: 0.5rem;
        color: #212529;
        font-size: 0.95rem;
    }

    .form-control,
    .form-select {
        border: 1px solid #e9ecef;
        border-radius: 0.375rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #084298;
        box-shadow: 0 0 0 0.25rem rgba(8, 66, 152, 0.1);
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #f06548;
    }

    .form-control.is-invalid:focus,
    .form-select.is-invalid:focus {
        border-color: #f06548;
        box-shadow: 0 0 0 0.25rem rgba(240, 101, 72, 0.1);
    }

    .invalid-feedback {
        color: #f06548;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }

    .form-check-label {
        margin-bottom: 0;
        cursor: pointer;
        font-weight: 500;
        color: #495057;
        user-select: none;
    }

    .form-text {
        font-size: 0.85rem;
        color: #6c757d;
        display: block;
        margin-top: 0.25rem;
    }

    /* Badge & Alert Colors */
    .badge.bg-light-success {
        background-color: #d1f8ea !important;
        color: #0f7e4f !important;
    }

    .badge.bg-light-primary {
        background-color: #cfe2ff !important;
        color: #084298 !important;
    }

    .bg-info-light {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }

    .border-info {
        border-color: #0d6efd !important;
    }

    .text-info {
        color: #0d6efd !important;
    }

    /* Button styles */
    .btn {
        font-weight: 500;
        padding: 0.625rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #084298;
        border-color: #084298;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0a3272;
        border-color: #0a3272;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(8, 66, 152, 0.15);
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5c636a;
        border-color: #5c636a;
    }

    /* Form switch */
    .form-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
        margin-left: -2.5rem;
        margin-top: 0.25rem;
        cursor: pointer;
    }

    .form-switch .form-check-input:checked {
        background-color: #084298;
        border-color: #084298;
    }

    /* Alert */
    .alert {
        border: none;
        border-radius: 0.375rem;
        font-size: 0.95rem;
    }

    .alert-danger {
        background-color: rgba(240, 101, 72, 0.1);
        color: #f06548;
    }

    .alert-danger .btn-close {
        filter: invert(1) brightness(1.5);
    }

    .alert ul {
        margin-bottom: 0;
    }

    .alert li {
        margin-bottom: 0.25rem;
    }

    /* Side card styles */
    .card.border-info {
        box-shadow: 0 0 20px rgba(13, 110, 253, 0.1);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .row > .col-lg-4,
        .row > .col-lg-8 {
            margin-bottom: 1rem;
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

        .card-body {
            padding: 1rem;
        }

        .card-footer {
            flex-direction: column;
        }

        .card-footer .btn {
            width: 100%;
        }

        .form-label {
            font-size: 0.9rem;
        }

        .btn {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }

        .form-control,
        .form-select {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }
    }
</style>
@endpush

{{-- Scripts --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);

    // Auto generate slug from name
    const nameInput = document.querySelector('input[name="name"]');
    const slugInput = document.querySelector('input[name="slug"]');

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            if (slugInput.value === '') {
                slugInput.value = nameInput.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });
    }

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add animation to form inputs on focus
    document.querySelectorAll('.form-control, .form-select').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});
</script>
@endpush

@endsection