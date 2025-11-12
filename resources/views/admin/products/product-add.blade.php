@extends('admin.layouts.main_nav')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="page-content">
    <div class="container py-4">
        <h4 class="fw-semibold mb-4">Thêm sản phẩm mới</h4>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                {{-- Bên trái: thông tin cơ bản --}}
                <div class="col-lg-7">
                    <div class="card mb-3 p-3">
                        <h5 class="fw-semibold">Thông tin cơ bản</h5>
                        <div class="mb-3">
                            <label class="form-label">Tên sản phẩm *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả sản phẩm</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card mb-3 p-3">
                        <h5 class="fw-semibold">Hình ảnh sản phẩm</h5>
                        <div class="mb-3">
                            <label class="form-label">Ảnh đại diện</label>
                            <input type="file" name="image_main" class="form-control @error('image_main') is-invalid @enderror">
                            @error('image_main')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Biến thể sản phẩm --}}
                    <div class="card mb-3 p-3">
                        <h5 class="fw-semibold">Biến thể sản phẩm</h5>
                        <button type="button" class="btn btn-sm btn-warning mb-2" id="add-variant-group">Thêm biến thể</button>

                        {{-- Nơi hiển thị nhóm thuộc tính --}}
                        <div class="variant-group-list mb-3"></div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="variant-table">
                                <thead>
                                    <tr>
                                        <th>Tên biến thể</th>
                                        <th>SKU</th>
                                        <th>Giá bán (đ)</th>
                                        <th>Tồn kho</th>
                                        <th>Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Dữ liệu biến thể sẽ được sinh ở đây --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Bên phải: giá & danh mục --}}
                <div class="col-lg-5">
                    <div class="card mb-3 p-3">
                        <h5 class="fw-semibold">Giá sản phẩm</h5>
                        <div class="mb-3">
                            <label class="form-label">Giá nhập (đ) *</label>
                            <input type="number" step="0.01" name="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price') }}">
                            @error('cost_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giá gốc (đ) *</label>
                            <input type="number" step="0.01" name="base_price" class="form-control @error('base_price') is-invalid @enderror" value="{{ old('base_price') }}">
                            @error('base_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giá khuyến mãi (đ)</label>
                            <input type="number" step="0.01" name="discount_price" class="form-control @error('discount_price') is-invalid @enderror" value="{{ old('discount_price') }}">
                            @error('discount_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card mb-3 p-3">
                        <h5 class="fw-semibold">Danh mục & Kho</h5>
                        <div class="mb-3">
                            <label class="form-label">Danh mục *</label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tồn kho *</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}">
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Hiển thị sản phẩm</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success px-4">Lưu sản phẩm</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const attributes = @json($attributes);
    const variantGroupList = document.querySelector('.variant-group-list');
    const variantTableBody = document.querySelector('#variant-table tbody');
    const addVariantGroupBtn = document.querySelector('#add-variant-group');
    const productStockInput = document.querySelector('input[name="stock"]');

    // Thêm nhóm thuộc tính
    addVariantGroupBtn.addEventListener('click', () => {
        const groupDiv = document.createElement('div');
        groupDiv.classList.add('border', 'p-3', 'rounded', 'mb-3');
        groupDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <select class="form-select attr-select">
                    <option value="">-- Chọn phân loại --</option>
                    ${attributes.map(a => `<option value="${a.id}">${a.name}</option>`).join('')}
                </select>
                <button type="button" class="btn btn-danger btn-sm remove-group ms-2">X</button>
            </div>
            <div class="value-container"></div>
        `;
        variantGroupList.appendChild(groupDiv);
    });

    // Xử lý chọn thuộc tính và checkbox
    variantGroupList.addEventListener('change', function(e) {
        if (e.target.classList.contains('attr-select')) {
            const selectedAttr = attributes.find(a => a.id == e.target.value);
            const container = e.target.closest('.border').querySelector('.value-container');
            container.innerHTML = selectedAttr ? `
                <label class="fw-semibold">Tùy chọn:</label>
                <div class="d-flex flex-wrap gap-2 mt-1">
                    ${selectedAttr.values.map(v => `
                        <label class="form-check form-check-inline">
                            <input class="form-check-input value-checkbox" type="checkbox" value="${v.id}" data-name="${v.value}">
                            <span class="form-check-label">${v.value}</span>
                        </label>
                    `).join('')}
                </div>
            ` : '';
            generateVariants();
        }

        if (e.target.classList.contains('value-checkbox')) {
            generateVariants();
        }
    });

    // Xóa nhóm thuộc tính
    variantGroupList.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-group')) {
            e.target.closest('.border').remove();
            generateVariants();
        }
    });

    // Xóa biến thể
    variantTableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variant')) {
            e.target.closest('tr').remove();
            updateProductStock();
        }
    });

    // Cartesian product
    function cartesian(arr) {
        if (arr.length === 0) return [];
        return arr.reduce((a, b) => {
            const ret = [];
            a.forEach(aElem => {
                b.forEach(bElem => {
                    ret.push(aElem.concat ? aElem.concat([bElem]) : [aElem, bElem]);
                });
            });
            return ret;
        });
    }

    // Sinh biến thể
    function generateVariants() {
        const selectedGroups = [];
        variantGroupList.querySelectorAll('.border').forEach(group => {
            const attrSelect = group.querySelector('.attr-select');
            const checkboxes = group.querySelectorAll('.value-checkbox:checked');
            if (attrSelect && attrSelect.value && checkboxes.length) {
                selectedGroups.push({
                    attr_id: attrSelect.value,
                    attr_name: attrSelect.options[attrSelect.selectedIndex].text,
                    values: Array.from(checkboxes).map(c => ({ id: c.value, name: c.dataset.name }))
                });
            }
        });

        let combos = [];
        if (selectedGroups.length > 0) {
            combos = cartesian(selectedGroups.map(g => g.values));
        }

        let variantIndex = 0;
        variantTableBody.innerHTML = combos.map(combo => {
            const values = Array.isArray(combo) ? combo : [combo];
            const label = values.map(v => v.name).join(' / ');
            const ids = values.map(v => v.id).join(',');

            const idx = variantIndex++;
            return `
                <tr>
                    <td>
                        ${label}
                        <input type="hidden" name="variants[${idx}][value_ids]" value="${ids}">
                        <input type="hidden" name="variants[${idx}][title]" value="${label}">
                    </td>
                    <td><input type="text" name="variants[${idx}][sku]" class="form-control"></td>
                    <td><input type="number" step="0.01" name="variants[${idx}][price]" class="form-control" value="0"></td>
                    <td><input type="number" name="variants[${idx}][stock]" class="form-control variant-stock" value="0"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-variant">X</button></td>
                </tr>
            `;
        }).join('');

        updateProductStock();
    }

    // Cập nhật stock sản phẩm tổng
    function updateProductStock() {
        const variantRows = variantTableBody.querySelectorAll('tr');
        if (variantRows.length > 0) {
            let totalStock = 0;
            variantRows.forEach(row => {
                const stockInput = row.querySelector('.variant-stock');
                totalStock += parseInt(stockInput.value) || 0;
                stockInput.addEventListener('input', () => updateProductStock());
            });
            productStockInput.value = totalStock;
            productStockInput.setAttribute('readonly', true);
        } else {
            productStockInput.removeAttribute('readonly');
        }
    }

});
</script>
@endpush
