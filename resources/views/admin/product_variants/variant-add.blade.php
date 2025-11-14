@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    {{-- Tiêu đề --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">Thêm Biến Thể Sản Phẩm</h2>
        <a href="{{ route('admin.product_variants.list') }}" class="btn btn-secondary btn-sm">Quay lại</a>
    </div>

    {{-- Hiển thị lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi!</strong> Vui lòng kiểm tra lại dữ liệu.<br>
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('admin.product_variants.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Sản phẩm --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Sản phẩm</label>
                    <select name="product_id" class="form-select" required>
                        <option value="">-- Chọn sản phẩm --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tên biến thể --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tên biến thể</label>
                    <input type="text" name="title" class="form-control" placeholder="Ví dụ: Màu đỏ - Size M" required>
                </div>

                {{-- SKU --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">SKU (tuỳ chọn)</label>
                    <input type="text" name="sku" class="form-control" placeholder="Nếu để trống sẽ tự tạo">
                </div>

                <div class="row">
                    {{-- Giá --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Giá</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>

                    {{-- Tồn kho --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tồn kho</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                </div>

                {{-- Ảnh biến thể --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Ảnh biến thể (tuỳ chọn)</label>
                    <input type="file" name="image_url" class="form-control">
                </div>

                {{-- Trạng thái --}}
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                    <label class="form-check-label fw-semibold">Hiển thị</label>
                </div>

                {{-- Buttons --}}
                <div class="mt-3">
                    <button class="btn btn-primary">Thêm biến thể</button>
                    <a href="{{ route('admin.product_variants.list') }}" class="btn btn-light border">Hủy</a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
