@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">Sửa biến thể: {{ $variant->title }}</h2>
        <a href="{{ route('admin.product_variants.list') }}" class="btn btn-secondary btn-sm">Quay lại</a>
    </div>

    <form action="{{ route('admin.product_variants.update', $variant->id) }}" 
          method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">

        @csrf
        @method('PUT')

        {{-- Sản phẩm --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Sản phẩm</label>
            <select name="product_id" class="form-select" required>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ $variant->product_id == $p->id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tên biến thể / SKU --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Tên biến thể</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title', $variant->title) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">SKU</label>
                <input type="text" name="sku" class="form-control"
                       value="{{ old('sku', $variant->sku) }}">
            </div>
        </div>

        {{-- Giá / Stock --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Giá</label>
                <input type="number" step="0.01" name="price" class="form-control"
                       value="{{ old('price', $variant->price) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Tồn kho</label>
                <input type="number" name="stock" class="form-control"
                       value="{{ old('stock', $variant->stock) }}" required>
            </div>
        </div>

        {{-- Ảnh --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Ảnh biến thể</label>

            @if($variant->image_url)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$variant->image_url) }}" 
                         width="120" class="rounded border">
                </div>
            @endif

            <input type="file" name="image_url" class="form-control">
        </div>

        {{-- Hiển thị --}}
        <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   {{ $variant->is_active ? 'checked' : '' }}>
            <label class="form-check-label">Hiển thị</label>
        </div>

        <button class="btn btn-primary px-4">Cập nhật</button>

    </form>
</div>
@endsection
