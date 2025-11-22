@extends('admin.layouts.main_nav')
@section('title', 'Thêm thương hiệu')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        {{-- Header Section --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                    <h4 class="page-title">Thêm thương hiệu mới</h4>
                    <div class="page-title-right">
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
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
                        <h5 class="card-title mb-0">Thông tin thương hiệu</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.brands.store') }}" 
                              method="POST" 
                              enctype="multipart/form-data"
                              novalidate>
                            @csrf

                            {{-- Name Field --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       placeholder="Nhập tên thương hiệu"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Slug Field --}}
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" 
                                       name="slug" 
                                       placeholder="Slug sẽ tự động tạo từ tên"
                                       value="{{ old('slug') }}"
                                       required>
                                <small class="form-text text-muted">Slug được tạo tự động từ tên thương hiệu</small>
                                @error('slug')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Description Field --}}
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Nhập mô tả thương hiệu">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Logo Upload --}}
                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo</label>
                                <div class="mb-2">
                                    <input type="file" 
                                           class="form-control @error('logo') is-invalid @enderror" 
                                           id="logo" 
                                           name="logo" 
                                           accept="image/*">
                                    <small class="form-text text-muted">Định dạng: JPG, PNG, GIF (Tối đa 2MB)</small>
                                    @error('logo')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Status Field --}}
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
                                        Hiển thị thương hiệu này
                                    </label>
                                </div>
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="d-flex gap-2 pt-3">
                                <button type="submit" class="btn btn-primary">
                                    <iconify-icon icon="solar:check-circle-bold-duotone" class="me-1"></iconify-icon>
                                    Tạo mới
                                </button>
                                <a href="{{ route('admin.brands.index') }}" class="btn btn-light">
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

    .form-control {
        border: 1px solid #e9ecef;
        border-radius: 0.25rem;
        font-size: 0.95rem;
    }

    .form-control:focus {
        border-color: #084298;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
    }

    .img-thumbnail {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
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

@endsection