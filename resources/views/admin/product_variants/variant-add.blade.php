@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Thêm biến thể sản phẩm</h2>
    <form action="{{ route('admin.product_variants.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Sản phẩm</label>
            <select name="product_id" class="form-select" required>
                <option value="">-- Chọn sản phẩm --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tên biến thể</label>
            <input type="text" name="title" class="form-control" placeholder="Ví dụ: Màu đỏ - Size M" required>
        </div>

        <div class="mb-3">
            <label class="form-label">SKU</label>
            <input type="text" name="sku" class="form-control" placeholder="Mã SKU riêng cho biến thể">
        </div>

        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tồn kho</label>
            <input type="number" name="stock" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh biến thể</label>
            <input type="file" name="image_url" class="form-control">
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
            <label class="form-check-label">Hiển thị</label>
        </div>

        <button class="btn btn-primary">Thêm biến thể</button>
        <a href="{{ route('admin.product_variants.list') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
