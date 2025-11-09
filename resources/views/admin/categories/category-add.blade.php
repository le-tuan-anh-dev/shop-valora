@extends('admin.layouts.main_nav')
@section('title', 'Thêm danh mục')

@section('content')
<div class="container py-4">
    <h3 class="fw-semibold mb-3">Thêm danh mục mới</h3>

    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tên danh mục</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Slug (tuỳ chọn)</label>
            <input type="text" name="slug" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Danh mục cha</label>
            <select name="parent_id" class="form-select">
                <option value="">-- Không chọn --</option>
                @foreach($parents as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- ✅ Sửa checkbox để luôn gửi giá trị 0/1 --}}
        <div class="form-check mb-3">
            <input type="hidden" name="is_active" value="0">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
            <label class="form-check-label">Hiển thị</label>
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.categories.list') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
