@extends('admin.layouts.main_nav')
@section('title', 'Sửa danh mục')

@section('content')
<div class="container py-4">
    <h3 class="fw-semibold mb-3">Sửa danh mục</h3>

    <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
        @csrf 
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tên danh mục</label>
            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control" value="{{ $category->slug }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Danh mục cha</label>
            <select name="parent_id" class="form-select">
                <option value="">-- Không chọn --</option>
                @foreach($parents as $p)
                    <option value="{{ $p->id }}" {{ $category->parent_id == $p->id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- ✅ Checkbox sửa đúng chuẩn --}}
        <div class="form-check mb-3">
            <input type="hidden" name="is_active" value="0">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
            <label class="form-check-label">Hiển thị</label>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.categories.list') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
