@extends('admin.layouts.main_nav')

@section('content')
<div class="page-content">

    <!-- Start Container Fluid -->
    <div class="container-xxl">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Thêm thuộc tính mới</h4>
                        <a href="{{ route('admin.attributes.list') }}" class="btn btn-secondary btn-sm">← Quay lại</a>
                    </div>

                    <div class="card-body">
                        {{-- FORM --}}
                        <form action="{{ route('admin.attributes.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                {{-- Tên thuộc tính --}}
                                <div class="col-lg-12  mb-3">
                                    <label for="variant-name" class="form-label text-dark">Tên thuộc tính</label>
                                    <input 
                                        type="text" 
                                        id="variant-name" 
                                        name="name" 
                                        value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror" 
                                        placeholder="Nhập tên thuộc tính (VD: Màu sắc, Kích cỡ)">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- Giá trị thuộc tính --}}
                              <div class="col-lg-12 mb-3">
                                <label class="form-label text-dark">Giá trị </label>
                                <div id="value-container">
                                    <div class="input-group mb-2">
                                        <input type="text" name="values[]" class="form-control" placeholder="Nhập giá trị thuộc tính(VD: Đen, trăng , L, M)">
                                        <button type="button" class="btn btn-danger remove-value">−</button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-success" id="add-value">+ Thêm giá trị</button>
                            </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary"> Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('value-container');
    const addBtn = document.getElementById('add-value');

    addBtn.addEventListener('click', function() {
        const div = document.createElement('div');
        div.classList.add('input-group', 'mb-2');
        div.innerHTML = `
            <input type="text" name="values[]" class="form-control" placeholder="Enter value">
            <button type="button" class="btn btn-danger remove-value">−</button>
        `;
        container.appendChild(div);
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-value')) {
            e.target.parentElement.remove();
        }
    });
});
</script>
@endsection
