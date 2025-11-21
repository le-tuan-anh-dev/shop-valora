@extends('admin.layouts.main_nav')
@section('title', 'Sửa danh mục')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        {{-- Header Section --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                    <h4 class="page-title">Sửa danh mục</h4>
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                           
                            Thông tin danh mục
                        </h5>
                    </div>

                    <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" class="needs-validation" novalidate>
                        @csrf 
                        @method('PUT')

                        <div class="card-body">
                            {{-- Tên danh mục --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    
                                    Tên danh mục
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $category->name) }}" 
                                       placeholder="Nhập tên danh mục"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Slug --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    
                                    Slug
                                </label>
                                <input type="text" 
                                       name="slug" 
                                       class="form-control @error('slug') is-invalid @enderror" 
                                       value="{{ old('slug', $category->slug) }}"
                                       placeholder="Slug sẽ tự động sinh nếu để trống">
                                <small class="form-text text-muted">Slug được dùng cho URL, chỉ gồm chữ cái, số và dấu gạch</small>
                                @error('slug')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Danh mục cha --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    
                                    Danh mục cha
                                </label>
                                <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                    <option value="">-- Không có danh mục cha --</option>
                                    @foreach($parents as $p)
                                        @if($p->id !== $category->id)
                                            <option value="{{ $p->id }}" 
                                                    {{ old('parent_id', $category->parent_id) == $p->id ? 'selected' : '' }}>
                                                {{ $p->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Chọn danh mục cha để tạo danh mục con</small>
                                @error('parent_id')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Trạng thái --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-2">
                                  
                                    Trạng thái
                                </label>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="is_active" 
                                           value="1" 
                                           id="isActive"
                                           {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">
                                        Hiển thị danh mục này
                                    </label>
                                </div>
                                <small class="form-text text-muted d-block mt-2">Tắt để ẩn danh mục khỏi trang web</small>
                            </div>
                        </div>

                        {{-- Form Footer --}}
                        <div class="card-footer bg-light d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary">
                               
                                Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Side Info --}}
           

            </div>
        </div>

    </div>
</div>

{{-- Hidden Delete Form --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

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
    }

    .form-check-label {
        margin-bottom: 0;
        cursor: pointer;
        font-weight: 500;
        color: #495057;
    }

    .form-text {
        font-size: 0.85rem;
        color: #6c757d;
        display: block;
        margin-top: 0.25rem;
    }

    /* Badge colors */
    .badge.bg-light-success {
        background-color: #d1f8ea !important;
        color: #0f7e4f !important;
    }

    .badge.bg-light-primary {
        background-color: #cfe2ff !important;
        color: #084298 !important;
    }

    .badge.bg-light-secondary {
        background-color: #e2e3e5 !important;
        color: #383d41 !important;
    }

    .bg-danger-light {
        background-color: rgba(240, 101, 72, 0.1) !important;
    }

    .border-danger {
        border-color: #f06548 !important;
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
    }

    .btn-primary {
        background-color: #084298;
        border-color: #084298;
    }

    .btn-primary:hover {
        background-color: #0a3272;
        border-color: #0a3272;
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

    .btn-danger {
        background-color: #f06548;
        border-color: #f06548;
        color: white;
    }

    .btn-danger:hover {
        background-color: #da543e;
        border-color: #da543e;
    }

    /* Form switch */
    .form-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
        margin-left: -2.5rem;
        margin-top: 0.25rem;
    }

    .form-switch .form-check-input:checked {
        background-color: #084298;
        border-color: #084298;
    }

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
    }
</style>
@endpush

{{-- Scripts --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(categoryId, categoryName) {
    Swal.fire({
        title: 'Xóa danh mục?',
        html: `Bạn chắc chắn muốn xóa danh mục <strong>"${categoryName}"</strong>?<br><small class="text-danger">Hành động này không thể hoàn tác!</small>`,
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
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/categories/${categoryId}`;
            form.submit();
        }
    });
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush

@endsection