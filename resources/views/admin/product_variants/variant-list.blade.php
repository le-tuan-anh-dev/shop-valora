@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Danh sách biến thể sản phẩm</h3>
        <a href="{{ route('admin.product_variants.create') }}" class="btn btn-success">
            + Thêm biến thể
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">ID</th>
                        <th width="150">Sản phẩm</th>
                        <th>Tên biến thể</th>
                        <th>SKU</th>
                        <th width="120">Giá</th>
                        <th width="80">Kho</th>
                        <th width="80">Ảnh</th>
                        <th width="100">Trạng thái</th>
                        <th width="130">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($variants as $v)
                        <tr>
                            <td class="align-middle">{{ $v->id }}</td>
                            <td class="align-middle">{{ $v->product->name ?? '—' }}</td>
                            <td class="align-middle">{{ $v->title }}</td>
                            <td class="align-middle">{{ $v->sku }}</td>
                            <td class="align-middle">{{ number_format($v->price) }} đ</td>
                            <td class="align-middle">{{ $v->stock }}</td>

                            <td class="align-middle">
                                @if($v->image_url)
                                    <img src="{{ asset('storage/' . $v->image_url) }}" 
                                         class="rounded" width="45" height="45">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td class="align-middle">
                                <span class="badge {{ $v->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $v->is_active ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>

                            <td class="align-middle">
                                <a href="{{ route('admin.product_variants.edit', $v->id) }}" 
                                   class="btn btn-warning btn-sm me-1">
                                    Sửa
                                </a>

                                <form action="{{ route('admin.product_variants.destroy', $v->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Xóa biến thể này?')" 
                                            class="btn btn-danger btn-sm">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                Chưa có biến thể nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
