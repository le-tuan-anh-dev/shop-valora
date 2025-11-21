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
                        <button type="button" class="btn btn-sm btn-warning mb-2" id="add-variant-group">+ Thêm nhóm thuộc tính</button>

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
                            <input type="number" min=0  name="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price') }}">
                            @error('cost_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giá bán (đ) *</label>
                            <input type="number"  name="base_price" class="form-control @error('base_price') is-invalid @enderror" value="{{ old('base_price') }}" id="base-price-input">
                            @error('base_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giá khuyến mãi (đ)</label>
                            <input type="number" min=0 name="discount_price" class="form-control @error('discount_price') is-invalid @enderror" value="{{ old('discount_price') }}">
                            @error('discount_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tồn kho *</label>
                            <input type="number"  name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" id="product-stock-input">
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card mb-3 p-3">
                        <h5 class="fw-semibold">Danh mục & Thương hiệu</h5>
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
                            <label class="form-label">Thương hiệu</label>
                            <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach ($brands as $b)
                                    <option value="{{ $b->id }}" {{ old('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
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
    const productStockInput = document.querySelector('#product-stock-input');
    const basePriceInput = document.querySelector('#base-price-input');

    // ========== Thêm nhóm thuộc tính ==========
    addVariantGroupBtn.addEventListener('click', () => {
        const groupDiv = document.createElement('div');
        groupDiv.classList.add('border', 'p-3', 'rounded', 'mb-3', 'bg-light');
        groupDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <select class="form-select attr-select">
                    <option value="">-- Chọn phân loại --</option>
                    ${attributes.map(a => `<option value="${a.id}" data-attr-name="${a.name}">${a.name}</option>`).join('')}
                </select>
                <button type="button" class="btn btn-danger btn-sm remove-group ms-2">X</button>
            </div>
            <div class="value-container"></div>
        `;
        variantGroupList.appendChild(groupDiv);
    });

    // ========== Xử lý chọn thuộc tính và checkbox ==========
    variantGroupList.addEventListener('change', function(e) {
        if (e.target.classList.contains('attr-select')) {
            const selectedAttrId = e.target.value;
            const selectedAttr = attributes.find(a => a.id == selectedAttrId);
            const container = e.target.closest('.border').querySelector('.value-container');
            
            if (selectedAttr && selectedAttr.values.length > 0) {
                container.innerHTML = `
                    <label class="fw-semibold">Tùy chọn ${selectedAttr.name}:</label>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        ${selectedAttr.values.map(v => `
                            <label class="form-check form-check-inline">
                                <input class="form-check-input value-checkbox" type="checkbox" value="${v.id}" data-name="${v.value}">
                                <span class="form-check-label">${v.value}</span>
                            </label>
                        `).join('')}
                    </div>
                `;
            } else {
                container.innerHTML = '<p class="text-muted">Không có tùy chọn nào</p>';
            }
            generateVariants();
        }

        if (e.target.classList.contains('value-checkbox')) {
            generateVariants();
        }
    });

    // ========== Xóa nhóm thuộc tính ==========
    variantGroupList.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-group')) {
            e.target.closest('.border').remove();
            generateVariants();
        }
    });

    // ========== Xóa biến thể ==========
    variantTableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variant')) {
            e.target.closest('tr').remove();
            updateProductStock();
        }
    });

    // ========== Lắng nghe thay đổi stock biến thể ==========
    variantTableBody.addEventListener('input', function(e) {
        if (e.target.classList.contains('variant-stock')) {
            updateProductStock();
        }
    });

    // ========== Cartesian product - Tạo tất cả combinations ==========
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
    function generateSKU() {
        // Tạo 2-3 chữ cái ngẫu nhiên in hoa
        const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const lettersLength = Math.random() < 0.5 ? 2 : 3; // Random 2 hoặc 3 chữ
        let randomLetters = '';
        
        for (let i = 0; i < lettersLength; i++) {
            randomLetters += letters.charAt(Math.floor(Math.random() * letters.length));
        }
        
        // Tạo số ngẫu nhiên (6 chữ số)
        const randomNumbers = Math.floor(Math.random() * 900) + 100;
        
        return `${randomLetters}-${randomNumbers}`;
    }
    // ========== Sinh biến thể từ nhóm thuộc tính ==========
    function generateVariants() {
        const selectedGroups = [];
        
        variantGroupList.querySelectorAll('.border').forEach(group => {
            const attrSelect = group.querySelector('.attr-select');
            const checkboxes = group.querySelectorAll('.value-checkbox:checked');
            
            if (attrSelect && attrSelect.value && checkboxes.length > 0) {
                selectedGroups.push({
                    attr_id: attrSelect.value,
                    attr_name: attrSelect.options[attrSelect.selectedIndex].text,
                    values: Array.from(checkboxes).map(c => ({ 
                        id: c.value, 
                        name: c.dataset.name 
                    }))
                });
            }
        });

        let combos = [];
        if (selectedGroups.length > 0) {
            combos = cartesian(selectedGroups.map(g => g.values));
        }

        // Xóa tất cả dòng cũ
        variantTableBody.innerHTML = '';

        if (combos.length === 0) {
            productStockInput.removeAttribute('readonly');
            return;
        }

        const basePrice = parseFloat(basePriceInput.value) || 0;

        // ✅ Tạo bảng biến thể
        combos.forEach((combo, idx) => {
            const values = Array.isArray(combo) ? combo : [combo];
            const label = values.map(v => v.name).join(' / ');
            const ids = values.map(v => v.id).join(',');
             const autoSKU = generateSKU();

            variantTableBody.innerHTML += `
                <tr>
                    <td>
                        <span class="badge bg-info">${label}</span>
                        <input type="hidden" name="variants[${idx}][value_ids]" value="${ids}">
                    </td>
                    <td>
                        <input type="text" name="variants[${idx}][sku]" class="form-control form-control-sm" value="${autoSKU}">
                    </td>
                    <td>
                        <input type="number" step="1" name="variants[${idx}][price]" class="form-control form-control-sm" value="${basePrice}">
                    </td>
                    <td>
                        <input type="number" name="variants[${idx}][stock]" class="form-control form-control-sm variant-stock" value="0">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-variant">Xóa</button>
                    </td>
                </tr>
            `;
        });

        updateProductStock();
    }

    // ========== Cập nhật stock sản phẩm tổng ==========
    function updateProductStock() {
        const variantRows = variantTableBody.querySelectorAll('tr');
        if (variantRows.length > 0) {
            let totalStock = 0;
            variantRows.forEach(row => {
                const stockInput = row.querySelector('.variant-stock');
                if (stockInput) {
                    totalStock += parseInt(stockInput.value) || 0;
                }
            });
            productStockInput.value = totalStock;
            productStockInput.setAttribute('readonly', true);
        } else {
            productStockInput.removeAttribute('readonly');
        }
    }

    // ========== Restore old variants khi có lỗi validation ==========
    const oldVariants = @json(old('variants', []));
    if (oldVariants && oldVariants.length > 0) {
        oldVariants.forEach((variant, idx) => {
            variantTableBody.innerHTML += `
                <tr>
                    <td>
                        <input type="hidden" name="variants[${idx}][value_ids]" value="${variant.value_ids || ''}">
                        <span class="text-muted">${variant.value_ids || 'N/A'}</span>
                    </td>
                    <td>
                        <input type="text" name="variants[${idx}][sku]" class="form-control form-control-sm" placeholder="SKU" value="${autoSKU}">
                    </td>
                    <td>
                        <input type="number" step="1" name="variants[${idx}][price]" class="form-control form-control-sm" value="${variant.price || 0}">
                    </td>
                    <td>
                        <input type="number" name="variants[${idx}][stock]" class="form-control form-control-sm variant-stock" value="${variant.stock || 0}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-variant">Xóa</button>
                    </td>
                </tr>
            `;
        });
        updateProductStock();
    }
});
</script>
@endpush