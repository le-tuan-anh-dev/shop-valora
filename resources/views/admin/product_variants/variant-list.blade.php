@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Danh sách biến thể sản phẩm</h2>
    <a href="{{ route('admin.product_variants.create') }}" class="btn btn-success mb-3">+ Thêm biến thể</a>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Tên biến thể</th>
                <th>SKU</th>
                <th>Giá</th>
                <th>Tồn kho</th>
                <th>Ảnh</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($variants as $v)
                <tr>
                    <td>{{ $v->id }}</td>
                    <td>{{ $v->product->name ?? '—' }}</td>
                    <td>{{ $v->title }}</td>
                    <td>{{ $v->sku }}</td>
                    <td>{{ number_format($v->price) }} đ</td>
                    <td>{{ $v->stock }}</td>
                    <td>
                        @if($v->image_url)
                            <img src="{{ asset('storage/'.$v->image_url) }}" width="50" height="50" class="rounded">
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($v->is_active)
                            <span class="badge bg-success">Hiển thị</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.product_variants.edit', $v->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('admin.product_variants.destroy', $v->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Xóa biến thể này?')" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center text-muted py-3">Chưa có biến thể nào</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
