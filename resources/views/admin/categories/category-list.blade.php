@extends('admin.layouts.main_nav')
@section('title', 'Danh sách danh mục')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-semibold">Danh mục sản phẩm</h3>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Thêm danh mục</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Danh mục cha</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $c)
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td>{{ $c->name }}</td>
                            <td>{{ $c->parent->name ?? '—' }}</td>
                            <td>{!! $c->is_active ? '<span class="badge bg-success">Hiển thị</span>' : '<span class="badge bg-secondary">Ẩn</span>' !!}</td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $c->id) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                                <form action="{{ route('admin.categories.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa danh mục này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">Chưa có danh mục</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $categories->links('pagination::bootstrap-5') }}
</div>
@endsection
