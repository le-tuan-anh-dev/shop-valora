@extends('admin.layouts.main_nav')

@section('content')
<div class="container-fluid p-5 bg-white rounded-4 shadow-lg mt-4">
    <h2 class="mb-4 text-center fw-bold">✏️ Chỉnh sửa sản phẩm</h2>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- 🔹 THÔNG TIN CƠ BẢN --}}
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Tên sản phẩm</label>
                <input type="text" name="name" value="{{ $product->name }}" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Danh mục</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $cate)
                        <option value="{{ $cate->id }}" {{ $cate->id == $product->category_id ? 'selected' : '' }}>
                            {{ $cate->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Giá gốc</label>
                <input type="number" step="0.01" name="base_price" value="{{ $product->base_price }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Giá giảm (nếu có)</label>
                <input type="number" step="0.01" name="discount_price" value="{{ $product->discount_price }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Tồn kho</label>
                <input type="number" name="stock" value="{{ $product->stock }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Ảnh đại diện</label>
                <input type="file" name="image_main" class="form-control">
                @if($product->image_main)
                    <img src="{{ asset('storage/'.$product->image_main) }}" class="mt-2 rounded" width="80" height="80">
                @endif
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Mô tả</label>
                <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
            </div>
        </div>

        {{-- 🔹 BIẾN THỂ --}}
        <hr class="my-5">
        <h4 class="fw-bold mb-3">⚙️ Biến thể sản phẩm</h4>

        <table class="table table-bordered align-middle" id="variantTable">
            <thead class="table-light">
                <tr>
                    <th>Tên biến thể</th>
                    <th>SKU</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Ảnh</th>
                    <th>Trạng thái</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->variants as $i => $v)
                    <tr>
                        <td><input type="text" name="variants[{{ $i }}][title]" value="{{ $v->title }}" class="form-control"></td>
                        <td><input type="text" name="variants[{{ $i }}][sku]" value="{{ $v->sku }}" class="form-control"></td>
                        <td><input type="number" step="0.01" name="variants[{{ $i }}][price]" value="{{ $v->price }}" class="form-control"></td>
                        <td><input type="number" name="variants[{{ $i }}][stock]" value="{{ $v->stock }}" class="form-control"></td>
                        <td>
                            <input type="file" name="variants[{{ $i }}][image_url]" class="form-control">
                            @if($v->image_url)
                                <img src="{{ asset('storage/'.$v->image_url) }}" width="60" class="rounded mt-2">
                            @endif
                        </td>
                        <td>
                            <select name="variants[{{ $i }}][is_active]" class="form-select">
                                <option value="1" {{ $v->is_active ? 'selected' : '' }}>Còn bán</option>
                                <option value="0" {{ !$v->is_active ? 'selected' : '' }}>Ngừng bán</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm removeRow">Xóa</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="button" id="addVariant" class="btn btn-outline-primary mt-2">+ Thêm biến thể mới</button>

        <div class="text-end mt-4">
            <a href="{{ route('admin.products.list') }}" class="btn btn-secondary px-4">Hủy</a>
            <button type="submit" class="btn btn-success px-4">Cập nhật</button>
        </div>
    </form>
</div>

{{-- JS thêm biến thể --}}
<script>
let index = {{ count($product->variants) }};
document.getElementById('addVariant').addEventListener('click', function() {
    let table = document.querySelector('#variantTable tbody');
    let row = `
        <tr>
            <td><input type="text" name="variants[${index}][title]" class="form-control"></td>
            <td><input type="text" name="variants[${index}][sku]" class="form-control"></td>
            <td><input type="number" step="0.01" name="variants[${index}][price]" class="form-control"></td>
            <td><input type="number" name="variants[${index}][stock]" class="form-control"></td>
            <td><input type="file" name="variants[${index}][image_url]" class="form-control"></td>
            <td>
                <select name="variants[${index}][is_active]" class="form-select">
                    <option value="1" selected>Còn bán</option>
                    <option value="0">Ngừng bán</option>
                </select>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm removeRow">Xóa</button>
            </td>
        </tr>`;
    table.insertAdjacentHTML('beforeend', row);
    index++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('removeRow')) {
        e.target.closest('tr').remove();
    }
});
</script>
@endsection
