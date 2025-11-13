@extends('admin.layouts.main_nav')
@section('title', 'Thêm danh mục')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        {{-- Header Section --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                    <h4 class="page-title">
                        <iconify-icon icon="solar:folder-plus-bold-duotone" class="me-2"></iconify-icon>
                        Thêm danh mục mới
                    </h4>
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
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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

                    <form method="POST" action="{{ route('admin.categories.store') }}" novalidate>
                        @csrf

                        <div class="card-body">
                            {{-- Tên danh mục --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Tên danh mục
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}"
                                       placeholder="VD: Quần áo, Giày dép, Phụ kiện"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Slug --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Slug
                                </label>
                                <input type="text" 
                                       name="slug" 
                                       class="form-control @error('slug') is-invalid @enderror" 
                                       value="{{ old('slug') }}"
                                       placeholder="Để trống để tự động sinh">
                                @error('slug')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">
                                    <iconify-icon icon="solar:info-circle-linear" class="me-1"></iconify-icon>
                                    Dùng trong URL, chỉ chứa chữ thường, số và dấu gạch
                                </small>
                            </div>

                            {{-- Danh mục cha --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Danh mục cha
                                </label>
                                <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                    <option value="">-- Danh mục chính (không có cha) --</option>
                                    @foreach($parents as $p)
                                        <option value="{{ $p->id }}" {{ old('parent_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Trạng thái --}}
                            <div class="mb-0">
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
                                           {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">
                                        Hiển thị danh mục
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Form Footer --}}
                        <div class="card-footer bg-light d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.categories.list') }}" class="btn btn-light">
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
        </div>

    </div>
</div>

@push('styles')
<style>
    .page-content { padding: 20px 0; }
    .page-title-box { padding-bottom: 20px; border-bottom: 1px solid #e9ecef; margin-bottom: 1.5rem !important; }
    .card { border: none; box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15); margin-bottom: 24px; }
    .card-header { background-color: #f8f9fa; border-bottom: 1px solid #e9ecef; padding: 1rem 1.5rem; }
    .card-title { font-size: 1rem; font-weight: 600; }
    .card-body { padding: 1.5rem; }
    .card-footer { border-top: 1px solid #e9ecef; padding: 1rem 1.5rem; }
    .form-label { margin-bottom: 0.5rem; font-weight: 500; }
    .form-control, .form-select { border: 1px solid #e9ecef; padding: 0.625rem 0.875rem; }
    .form-control:focus, .form-select:focus { border-color: #084298; box-shadow: 0 0 0 0.25rem rgba(8, 66, 152, 0.1); }
    .form-control.is-invalid, .form-select.is-invalid { border-color: #f06548; }
    .form-control.is-invalid:focus, .form-select.is-invalid:focus { border-color: #f06548; box-shadow: 0 0 0 0.25rem rgba(240, 101, 72, 0.1); }
    .invalid-feedback { color: #f06548; font-size: 0.875rem; margin-top: 0.25rem; display: block; }
    .btn { font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem; }
    .btn-primary { background-color: #084298; border-color: #084298; }
    .btn-primary:hover { background-color: #0a3272; }
    .btn-light { background-color: #f8f9fa; border-color: #e9ecef; color: #495057; }
    .btn-light:hover { background-color: #e2e6ea; }
    .btn-secondary { background-color: #6c757d; border-color: #6c757d; }
    .btn-secondary:hover { background-color: #5c636a; }
    .alert { border: none; border-radius: 0.375rem; }
    .alert-danger { background-color: rgba(240, 101, 72, 0.1); color: #f06548; }
    .form-switch .form-check-input { width: 3rem; height: 1.5rem; margin-left: -2.5rem; cursor: pointer; }
    .form-switch .form-check-input:checked { background-color: #084298; border-color: #084298; }
    @media (max-width: 576px) {
        .page-title-box { flex-direction: column; }
        .card-footer { flex-direction: column; gap: 0.5rem; }
        .card-footer .btn { width: 100%; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.querySelector('input[name="name"]');
    const slugInput = document.querySelector('input[name="slug"]');

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            if (slugInput.value === '') {
                slugInput.value = this.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });
    }
});
</script>
@endpush

@endsection