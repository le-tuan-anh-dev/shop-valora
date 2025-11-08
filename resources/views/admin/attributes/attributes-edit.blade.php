@extends('admin.layouts.main_nav')

@section('content')
<div class="page-content">
    <div class="container-xxl">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Chỉnh sửa thuộc tính</h4>
                        <a href="{{ route('admin.attributes.list') }}" class="btn btn-secondary btn-sm">← Quay lại</a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.attributes.update', $attribute->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- Tên thuộc tính --}}
                                <div class="col-lg-12 mb-3">
                                    <label for="name" class="form-label text-dark">Tên thuộc tính</label>
                                    <input 
                                        type="text" 
                                        name="name" 
                                        id="name" 
                                        class="form-control @error('name') is-invalid @enderror" 
                                        value="{{ old('name', $attribute->name) }}"
                                        placeholder="VD: Màu sắc, Kích cỡ, Thương hiệu...">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Giá trị thuộc tính --}}
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label text-dark">Giá trị thuộc tính</label>
                                    <div id="value-container">
                                        @foreach ($attribute->values as $value)
                                            <div class="input-group mb-2">
                                                {{-- ID ẩn để Laravel biết đây là giá trị nào --}}
                                                <input type="hidden" name="values[{{ $loop->index }}][id]" value="{{ $value->id }}">
                                                <input type="text" name="values[{{ $loop->index }}][value]" value="{{ $value->value }}" class="form-control">
                                                <button type="button" class="btn btn-danger remove-value">−</button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" id="add-value" class="btn btn-sm btn-success">+ Thêm giá trị</button>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>

{{-- JavaScript thêm/xóa giá trị --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('value-container');
    const addBtn = document.getElementById('add-value');
    let index = {{ $attribute->values->count() }}; // Đếm sẵn số value hiện có

    addBtn.addEventListener('click', function() {
        const div = document.createElement('div');
        div.classList.add('input-group', 'mb-2');
        div.innerHTML = `
            <input type="hidden" name="values[${index}][id]" value="">
            <input type="text" name="values[${index}][value]" class="form-control" placeholder="Nhập giá trị mới">
            <button type="button" class="btn btn-danger remove-value">−</button>
        `;
        container.appendChild(div);
        index++;
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-value')) {
            e.target.closest('.input-group').remove();
        }
    });
});
</script>
@endsection
