@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Sửa biến thể: {{ $variant->title }}</h2>
    <form action="{{ route('admin.product_variants.update', $variant->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Sản phẩm</label>
            <select name="product_id" class="form-select" required>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ $variant->product_id == $p->id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tên biến thể</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $variant->title) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">SKU</label>
            <input type="text" name="sku" class="form-control" value="{{ old('sku', $variant->sku) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $variant->price) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tồn kho</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock', $variant->stock) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh biến thể</label><br>
            @if($variant->image_url)
                <img src="{{ asset('storage/'.$variant->image_url) }}" width="100" class="mb-2 rounded"><br>
            @endif
            <input type="file" name="image_url" class="form-control">
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $variant->is_active ? 'checked' : '' }}>
            <label class="form-check-label">Hiển thị</label>
        </div>

        <button class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.product_variants.list') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
