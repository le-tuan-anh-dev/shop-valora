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
                            <label for="image_main" class="form-label">Ảnh Chính <span class="text-danger">*</span></label>
                            <input type="file" 
                                class="form-control @error('image_main') is-invalid @enderror" 
                                id="image_main" 
                                name="image_main"
                                accept="image/*"
                                >
                            <div id="previewMainImage" class="mt-3"></div>
                            @error('image_main')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Ảnh Phụ --}}
                        <div class="mb-3">
                            <label for="product_images" class="form-label">Ảnh Phụ</label>
                            <input type="file" 
                                class="form-control @error('product_images.*') is-invalid @enderror" 
                                id="product_images" 
                                name="product_images[]"
                                multiple
                                accept="image/*">
                            <div id="previewImages" class="mt-3 d-flex flex-wrap gap-2"></div>
                            @error('product_images')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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
                <div class="col-lg-12">

                    <div class="card mb-3 p-3 dimensions-card">
    <h5 class="fw-semibold">Kích thước & Cân nặng sản phẩm chung</h5>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Chiều dài</label>
            <div class="input-group">
                <input type="number" step="0.01" min="0" name="length" 
                    class="form-control @error('length') is-invalid @enderror" 
                    value="{{ old('length') }}" placeholder="0.00">
                <span class="input-group-text">cm</span>
            </div>
            @error('length')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6 mb-3">
            <label class="form-label">Chiều rộng</label>
            <div class="input-group">
                <input type="number" step="0.01" min="0" name="width" 
                    class="form-control @error('width') is-invalid @enderror" 
                    value="{{ old('width') }}" placeholder="0.00">
                <span class="input-group-text">cm</span>
            </div>
            @error('width')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6 mb-3">
            <label class="form-label">Chiều cao</label>
            <div class="input-group">
                <input type="number" step="0.01" min="0" name="height" 
                    class="form-control @error('height') is-invalid @enderror" 
                    value="{{ old('height') }}" placeholder="0.00">
                <span class="input-group-text">cm</span>
            </div>
            @error('height')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-6 mb-3">
            <label class="form-label">Cân nặng</label>
            <div class="input-group">
                <input type="number" step="0.01" min="0" name="weight" 
                    class="form-control @error('weight') is-invalid @enderror" 
                    value="{{ old('weight') }}" placeholder="0.00">
                <span class="input-group-text">gr</span>
            </div>
            @error('weight')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
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
                                        <th>Chiều dài (cm)</th>
                                        <th>Chiều rộng (cm)</th>
                                        <th>Chiều cao (cm)</th>
                                        <th>Cân nặng (gr)</th>
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
    
    // ========== Hiển thị lỗi validation biến thể ==========
    function displayVariantErrors(errors) {
        if (!errors || Object.keys(errors).length === 0) return;
        
        // Xóa thông báo lỗi cũ
        document.querySelectorAll('.variant-error-msg').forEach(el => el.remove());
        document.querySelectorAll('.variant-error').forEach(el => el.classList.remove('variant-error'));
        
        for (const [field, messages] of Object.entries(errors)) {
            if (field.startsWith('variants.')) {
                const match = field.match(/variants\.(\d+)\.(\w+)/);
                if (match) {
                    const [, idx, fieldName] = match;
                    const input = document.querySelector(`input[name="variants[${idx}][${fieldName}]"]`);
                    
                    if (input) {
                        // Thêm class lỗi
                        input.classList.add('variant-error');
                        
                        // Tạo thông báo lỗi
                        const errorMsg = document.createElement('small');
                        errorMsg.className = 'variant-error-msg';
                        errorMsg.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${messages[0]}`;
                        
                        // Insert sau input
                        const cell = input.closest('td');
                        if (cell) {
                            const existingError = cell.querySelector('.variant-error-msg');
                            if (existingError) existingError.remove();
                            cell.appendChild(errorMsg);
                        }
                    }
                }
            }
        }
    }

    // ========== Highlight input có lỗi ==========
    function highlightErrorFields(errors) {
        document.querySelectorAll('input.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });

        if (!errors || Object.keys(errors).length === 0) return;

        for (const [field, messages] of Object.entries(errors)) {
            if (field.startsWith('variants.')) {
                const match = field.match(/variants\.(\d+)\.(\w+)/);
                if (match) {
                    const [, idx, fieldName] = match;
                    const input = document.querySelector(`input[name="variants[${idx}][${fieldName}]"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        input.title = messages.join('; ');
                    }
                }
            } else {
                const input = document.querySelector(`input[name="${field}"], select[name="${field}"], textarea[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                }
            }
        }
    }

    // ========== Lấy danh sách attr_id đã được chọn ==========
    function getSelectedAttrIds() {
        const selectedIds = [];
        variantGroupList.querySelectorAll('.attr-select').forEach(select => {
            if (select.value) {
                selectedIds.push(select.value);
            }
        });
        return selectedIds;
    }

    // ========== Tạo options cho select (loại bỏ đã chọn) ==========
    function createAttrOptions(excludeIds = []) {
        return attributes
            .filter(a => !excludeIds.includes(a.id.toString()))
            .map(a => `<option value="${a.id}" data-attr-name="${a.name}">${a.name}</option>`)
            .join('');
    }

    // ========== Cập nhật tất cả select (refresh options) ==========
    function refreshAllSelects() {
        const selectedIds = getSelectedAttrIds();
        
        variantGroupList.querySelectorAll('.border').forEach(group => {
            const select = group.querySelector('.attr-select');
            const currentValue = select.value;
            
            const excludeList = selectedIds.filter(id => id !== currentValue);
            select.innerHTML = `
                <option value="">-- Chọn phân loại --</option>
                ${attributes
                    .filter(a => !excludeList.includes(a.id.toString()))
                    .map(a => `<option value="${a.id}" data-attr-name="${a.name}" ${a.id == currentValue ? 'selected' : ''}>${a.name}</option>`)
                    .join('')}
            `;
        });
    }

    // ========== Thêm nhóm thuộc tính ==========
    addVariantGroupBtn.addEventListener('click', () => {
        const selectedIds = getSelectedAttrIds();
        
        const groupDiv = document.createElement('div');
        groupDiv.classList.add('border', 'p-3', 'rounded', 'mb-3', 'bg-light');
        groupDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <select class="form-select attr-select">
                    <option value="">-- Chọn phân loại --</option>
                    ${createAttrOptions(selectedIds)}
                </select>
                <button type="button" class="btn btn-danger btn-sm remove-group ms-2">X</button>
            </div>
            <div class="value-container"></div>
        `;
        variantGroupList.appendChild(groupDiv);
        toggleDimensionsSection();
    });

    // ========== Xử lý chọn thuộc tính và checkbox ==========
    variantGroupList.addEventListener('change', function(e) {
        if (e.target.classList.contains('attr-select')) {
            const selectedAttrId = e.target.value;
            const selectedAttr = attributes.find(a => a.id == selectedAttrId);
            const container = e.target.closest('.border').querySelector('.value-container');
            
            refreshAllSelects();
            
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
            refreshAllSelects();
            generateVariants();
            toggleDimensionsSection();
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

    // ========== Tạo SKU ngẫu nhiên ==========
    function generateSKU() {
        const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const lettersLength = Math.random() < 0.5 ? 2 : 3;
        let randomLetters = '';
        
        for (let i = 0; i < lettersLength; i++) {
            randomLetters += letters.charAt(Math.floor(Math.random() * letters.length));
        }
        
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

        variantTableBody.innerHTML = '';

        if (combos.length === 0) {
            productStockInput.removeAttribute('readonly');
            return;
        }

        const basePrice = parseFloat(basePriceInput.value) || 0;

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
                        <input type="number" step="0.01" min="0" name="variants[${idx}][length]" class="form-control form-control-sm">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" name="variants[${idx}][width]" class="form-control form-control-sm">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" name="variants[${idx}][height]" class="form-control form-control-sm">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" name="variants[${idx}][weight]" class="form-control form-control-sm">
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
    const validationErrors = @json($errors->getMessages() ?? []);

    if (oldVariants && oldVariants.length > 0) {
        oldVariants.forEach((variant, idx) => {
            const autoSKU = generateSKU();
            variantTableBody.innerHTML += `
                <tr>
                    <td>
                        <input type="hidden" name="variants[${idx}][value_ids]" value="${variant.value_ids || ''}">
                        <span class="text-muted">${variant.value_ids || 'N/A'}</span>
                    </td>
                    <td>
                        <input type="text" name="variants[${idx}][sku]" class="form-control form-control-sm" placeholder="SKU" value="${variant.sku || autoSKU}">
                    </td>
                    <td>
                        <input type="number" step="1" name="variants[${idx}][price]" class="form-control form-control-sm" value="${variant.price || ''}">
                    </td>
                    <td>
                        <input type="number" name="variants[${idx}][stock]" class="form-control form-control-sm variant-stock" value="${variant.stock || ''}">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" name="variants[${idx}][length]" class="form-control form-control-sm" value="${variant.length || ''}">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" name="variants[${idx}][width]" class="form-control form-control-sm" value="${variant.width || ''}">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" name="variants[${idx}][height]" class="form-control form-control-sm" value="${variant.height || ''}">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" name="variants[${idx}][weight]" class="form-control form-control-sm" value="${variant.weight || ''}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-variant">Xóa</button>
                    </td>
                </tr>
            `;
        });
        updateProductStock();
    }

    // Hiển thị lỗi validation
    highlightErrorFields(validationErrors);
    displayVariantErrors(validationErrors); // THÊM DÒNG NÀY
    toggleDimensionsSection();
});

// ========== Preview ảnh phụ ==========
document.getElementById('product_images').addEventListener('change', function(e) {
    const previewContainer = document.getElementById('previewImages');
    previewContainer.innerHTML = '';
    
    Array.from(this.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = document.createElement('img');
            img.src = event.target.result;
            img.style.cssText = 'width: 100px; height: 100px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;';
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});

// ========== Preview ảnh chính ==========
document.getElementById('image_main').addEventListener('change', function(e) {
    const previewContainer = document.getElementById('previewMainImage');
    previewContainer.innerHTML = '';
    
    if (this.files.length > 0) {
        const file = this.files[0];
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = document.createElement('img');
            img.src = event.target.result;
            img.style.cssText = 'width: 150px; height: 150px; object-fit: cover; border-radius: 4px;';
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});
</script>


@endpush