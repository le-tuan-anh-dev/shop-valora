@extends('admin.layouts.main_nav')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
<div class="page-content">
    <div class="container py-4">
        <h4 class="fw-semibold mb-4">Chỉnh sửa sản phẩm</h4>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">

                {{-- Left: Thông tin cơ bản & hình ảnh & biến thể --}}
                <div class="col-lg-7">

                    {{-- Thông tin cơ bản --}}
                    <div class="card mb-3 p-3">
                        <h5 class="fw-semibold">Thông tin cơ bản</h5>
                        <div class="mb-3">
                            <label class="form-label">Tên sản phẩm *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả sản phẩm</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $product->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Hình ảnh --}}
                    <div class="card mb-3 p-3">
                        <h5 class="fw-semibold">Hình ảnh sản phẩm</h5>
                        <div class="mb-3">
                            <label class="form-label">Ảnh đại diện</label>
                            <input type="file" name="image_main" class="form-control @error('image_main') is-invalid @enderror">
                            @if($product->image_main)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$product->image_main) }}" alt="Ảnh" style="max-width:150px;height:150px;object-fit:cover;border:1px solid #e9ecef;">
                                </div>
                            @endif
                            @error('image_main')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        {{-- Ảnh Phụ Hiện Tại --}}
 {{-- Thêm Ảnh Phụ --}}
<div class="mb-3">
    <label for="product_images" class="form-label">Thêm Ảnh Phụ</label>
    <input type="file" 
           class="form-control @error('product_images.*') is-invalid @enderror" 
           id="product_images" 
           name="product_images[]"
           multiple
           accept="image/*">
    <div id="previewNewImages" class="mt-3 d-flex flex-wrap gap-2"></div>
    @error('product_images')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

{{-- Hidden input để xóa tất cả ảnh cũ --}}
<input type="hidden" id="deleteAllImages" name="delete_all_images" value="0">
                    </div>

                    {{-- Biến thể --}}
                    <div class="card mb-3 p-3">
                        <h5 class="fw-semibold mb-3">Biến thể sản phẩm</h5>
                        
                        <div id="all-variant-groups" class="mb-3"></div>

                        <button type="button" class="btn btn-sm btn-warning mb-3" id="add-variant-group">
                            <i class="fas fa-plus me-1"></i> Thêm nhóm phân loại
                        </button>

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
                                    {{-- Variants sẽ được sinh bởi JS --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                {{-- Right: Giá & danh mục --}}
                <div class="col-lg-5">

                    {{-- Giá sản phẩm --}}
                    <div class="card mb-3 p-3">
                        <h5 class="fw-semibold">Giá sản phẩm</h5>
                        <div class="mb-3">
                            <label class="form-label">Giá nhập (₫) *</label>
                            <input type="number" step="0.01" name="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price', $product->cost_price) }}">
                            @error('cost_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giá bán (₫) *</label>
                            <input type="number" step="0.01" name="base_price" class="form-control @error('base_price') is-invalid @enderror" value="{{ old('base_price', $product->base_price) }}" id="base-price-input">
                            @error('base_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giá khuyến mãi (₫)</label>
                            <input type="number" step="0.01" name="discount_price" class="form-control @error('discount_price') is-invalid @enderror" value="{{ old('discount_price', $product->discount_price) }}">
                            @error('discount_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tồn kho *</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" id="product-stock-input" value="{{ old('stock', $product->stock) }}">
                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Danh mục & thương hiệu --}}
                    <div class="card mb-3 p-3">
                        <h5 class="fw-semibold">Danh mục & Thương hiệu</h5>
                        <div class="mb-3">
                            <label class="form-label">Danh mục *</label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" {{ old('category_id', $product->category_id)==$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Thương hiệu</label>
                            <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" {{ old('brand_id', $product->brand_id) == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Hiển thị sản phẩm</label>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Cập nhật sản phẩm</button>
                    </div>

                </div>

            </div>

            {{-- Container ẩn để lưu các variant cần xóa --}}
            <div id="delete-variants-container"></div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const attributes = @json($attributes);
    const existingVariantsData = @json($product->variants->toArray());
    const variantAttributesMap = {};
    @foreach($product->variants as $v)
        variantAttributesMap[{{ $v->id }}] = '{{ $v->attributeValues()->pluck('attribute_value_id')->join(",") }}';
    @endforeach
    
    const productId = {{ $product->id }};
    const productName = "{{ Str::slug($product->name) }}";

    // Tạo map từ value_id đến name
    const valueMap = {};
    attributes.forEach(attr => {
        attr.values.forEach(val => {
            valueMap[val.id] = val.value;
        });
    });

    const allGroupsContainer = document.getElementById('all-variant-groups');
    const variantTableBody = document.querySelector('#variant-table tbody');
    const deleteVariantsContainer = document.getElementById('delete-variants-container');
    const addVariantGroupBtn = document.getElementById('add-variant-group');
    const productStockInput = document.getElementById('product-stock-input');
    const basePriceInput = document.getElementById('base-price-input');

    let hasAddedNewGroup = false;

    // Hàm tạo SKU ngẫu nhiên
    function generateSKU() {
        // Tạo 2-3 chữ cái ngẫu nhiên in hoa
        const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const lettersLength = Math.random() < 0.5 ? 2 : 3; // Random 2 hoặc 3 chữ
        let randomLetters = '';
        
        for (let i = 0; i < lettersLength; i++) {
            randomLetters += letters.charAt(Math.floor(Math.random() * letters.length));
        }
        
        // Tạo số ngẫu nhiên (3 chữ số: 100-999)
        const randomNumbers = Math.floor(Math.random() * 900) + 100;
        
        return `${randomLetters}-${randomNumbers}`;
    }

    // Hàm lấy label cho variant từ value_ids
    function getVariantLabel(valueIdsStr) {
        if (!valueIdsStr) return 'Biến thể mặc định';
        const ids = valueIdsStr.split(',').map(id => parseInt(id));
        const names = ids.map(id => valueMap[id] || 'Unknown').join(' / ');
        return names || 'Biến thể mặc định';
    }

    // Hàm sort value_ids
    function sortValueIds(idsStr) {
        if (!idsStr) return '';
        return idsStr.split(',').map(id => parseInt(id)).sort((a, b) => a - b).join(',');
    }

    // Hiển thị các biến thể hiện có
    function displayExistingVariants() {
        variantTableBody.innerHTML = '';
        
        existingVariantsData.forEach((variant) => {
            const valueIdsStr = variantAttributesMap[variant.id];
            const label = getVariantLabel(valueIdsStr);

            variantTableBody.insertAdjacentHTML('beforeend', `
                <tr data-variant-type="existing" data-key="existing_${variant.id}">
                    <td>
                        <span class="badge bg-success">${label}</span>
                        <input type="hidden" name="variants[${variant.id}][id]" value="${variant.id}">
                        <input type="hidden" name="variants[${variant.id}][value_ids]" value="${valueIdsStr}">
                        <input type="hidden" name="variants[${variant.id}][type]" value="existing">
                    </td>
                    <td>
                        <input type="text" 
                               name="variants[${variant.id}][sku]" 
                               class="form-control form-control-sm" 
                               value="${variant.sku || ''}" 
                               readonly>
                    </td>
                    <td>
                        <input type="number" 
                               name="variants[${variant.id}][price]" 
                               class="form-control form-control-sm" 
                               value="${variant.price || ''}">
                    </td>
                    <td>
                        <input type="number" 
                               name="variants[${variant.id}][stock]" 
                               class="form-control form-control-sm variant-stock" 
                               value="${variant.stock || ''}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-existing-variant" data-id="${variant.id}">Xóa</button>
                    </td>
                </tr>
            `);
        });
        
        updateProductStock();
    }

    // Hàm tạo variant group
    function createVariantGroup() {
        hasAddedNewGroup = true;
        
        const groupDiv = document.createElement('div');
        groupDiv.classList.add('border', 'p-3', 'rounded', 'mb-3', 'bg-light', 'variant-group');
        const selectedAttrIds = Array.from(allGroupsContainer.querySelectorAll('.attr-select')).map(s => s.value);

        groupDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <select class="form-select attr-select">
                    <option value="">-- Chọn phân loại --</option>
                    ${attributes.map(a => {
                        if (!selectedAttrIds.includes(a.id.toString())) {
                            return `<option value="${a.id}">${a.name}</option>`;
                        }
                        return '';
                    }).join('')}
                </select>
                <button type="button" class="btn btn-danger btn-sm remove-group ms-2">X</button>
            </div>
            <div class="value-container"></div>
        `;
        allGroupsContainer.appendChild(groupDiv);
    }

    // Hàm render values cho attribute
    function renderValues(groupDiv, attr) {
        const container = groupDiv.querySelector('.value-container');
        container.innerHTML = `
            <label class="fw-semibold">Tùy chọn ${attr.name}:</label>
            <div class="d-flex flex-wrap gap-2 mt-2">
                ${attr.values.map(v => `
                    <label class="form-check form-check-inline">
                        <input class="form-check-input value-checkbox" type="checkbox" value="${v.id}" data-name="${v.value}">
                        <span class="form-check-label">${v.value}</span>
                    </label>
                `).join('')}
            </div>
        `;
    }

    // Lấy các nhóm đã chọn
    function getSelectedGroups() {
        const groups = [];
        allGroupsContainer.querySelectorAll('.variant-group').forEach(group => {
            const attrSelect = group.querySelector('.attr-select');
            const checkboxes = group.querySelectorAll('.value-checkbox:checked');
            if (attrSelect && attrSelect.value && checkboxes.length) {
                groups.push({
                    attr_id: attrSelect.value,
                    attr_name: attrSelect.options[attrSelect.selectedIndex].text,
                    values: Array.from(checkboxes).map(c => ({ id: c.value, name: c.dataset.name }))
                });
            }
        });
        return groups;
    }

    // Cartesian product
    function cartesian(arrays) {
        return arrays.reduce((acc, curr) => {
            const res = [];
            acc.forEach(a => {
                curr.forEach(c => {
                    res.push([...a, c]);
                });
            });
            return res;
        }, [[]]);
    }

    // Tạo variants mới hoàn toàn
    function generateNewVariants() {
        // Xóa tất cả variants (cũ + mới)
        variantTableBody.innerHTML = '';
        
        // Đánh dấu xóa tất cả variants cũ
        deleteVariantsContainer.innerHTML = '';
        existingVariantsData.forEach(variant => {
            deleteVariantsContainer.innerHTML += `<input type="hidden" name="delete_variants[]" value="${variant.id}">`;
        });

        const basePrice = parseFloat(basePriceInput.value) || 0;
        const selectedGroups = getSelectedGroups();

        // Nếu không có group nào được chọn
        if (selectedGroups.length === 0) {
            productStockInput.removeAttribute('readonly');
            productStockInput.value = 0;
            return;
        }

        // Tạo tất cả combinations
        const combos = cartesian(selectedGroups.map(g => g.values));
        
        combos.forEach((combo, index) => {
            const values = Array.isArray(combo) ? combo : [combo];
            const valueIds = values.map(v => v.id);
            const label = values.map(v => v.name).join(' / ');
            const sku = generateSKU(); // Tạo SKU ngẫu nhiên
            const newKey = `new_${index}`;

            variantTableBody.insertAdjacentHTML('beforeend', `
                <tr data-variant-type="new" data-key="${newKey}">
                    <td>
                        <span class="badge bg-primary">${label}</span>
                        <input type="hidden" name="variants[${newKey}][id]" value="">
                        <input type="hidden" name="variants[${newKey}][value_ids]" value="${valueIds.join(',')}">
                        <input type="hidden" name="variants[${newKey}][type]" value="new">
                    </td>
                    <td>
                        <input type="text" 
                               name="variants[${newKey}][sku]" 
                               class="form-control form-control-sm sku-input" 
                               value="${sku}">
                    </td>
                    <td>
                        <input type="number" 
                               name="variants[${newKey}][price]" 
                               class="form-control form-control-sm" 
                               value="${basePrice}">
                    </td>
                    <td>
                        <input type="number" 
                               name="variants[${newKey}][stock]" 
                               class="form-control form-control-sm variant-stock" 
                               >
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-variant">Xóa</button>
                    </td>
                </tr>
            `);
        });

        updateProductStock();
    }

    // Cập nhật tổng stock
    function updateProductStock() {
        const variantRows = variantTableBody.querySelectorAll('tr');
        if (variantRows.length > 0) {
            let totalStock = 0;
            variantRows.forEach(row => {
                const stockInput = row.querySelector('.variant-stock');
                if (stockInput) {
                    totalStock += parseInt(stockInput.value) || 0;
                    stockInput.removeEventListener('input', updateProductStock);
                    stockInput.addEventListener('input', updateProductStock);
                }
            });
            productStockInput.value = totalStock;
            productStockInput.setAttribute('readonly', true);
        } else {
            productStockInput.removeAttribute('readonly');
            productStockInput.value = 0;
        }
    }

    // Sự kiện: Thêm nhóm phân loại
    addVariantGroupBtn.addEventListener('click', () => {
        createVariantGroup();
    });

    // Sự kiện: Thay đổi attribute hoặc value
    allGroupsContainer.addEventListener('change', e => {
        if (e.target.classList.contains('attr-select')) {
            const selectedAttr = attributes.find(a => a.id == e.target.value);
            const container = e.target.closest('.variant-group').querySelector('.value-container');
            if (selectedAttr) {
                renderValues(e.target.closest('.variant-group'), selectedAttr);
            } else {
                container.innerHTML = '';
            }
            if (hasAddedNewGroup) {
                generateNewVariants();
            }
        }
        if (e.target.classList.contains('value-checkbox')) {
            if (hasAddedNewGroup) {
                generateNewVariants();
            }
        }
    });

    // Sự kiện: Xóa nhóm phân loại
    allGroupsContainer.addEventListener('click', e => {
        if (e.target.classList.contains('remove-group')) {
            e.target.closest('.variant-group').remove();
            
            if (allGroupsContainer.querySelectorAll('.variant-group').length === 0) {
                hasAddedNewGroup = false;
                displayExistingVariants();
            } else if (hasAddedNewGroup) {
                generateNewVariants();
            }
        }
    });

    // Sự kiện: Xóa variant
    variantTableBody.addEventListener('click', e => {
        if (e.target.closest('.remove-existing-variant')) {
            const btn = e.target.closest('.remove-existing-variant');
            const variantId = btn.dataset.id;
            const tr = btn.closest('tr');
            
            deleteVariantsContainer.innerHTML += `<input type="hidden" name="delete_variants[]" value="${variantId}">`;
            tr.remove();
            updateProductStock();
        }
        
        if (e.target.closest('.remove-variant')) {
            const tr = e.target.closest('tr');
            tr.remove();
            updateProductStock();
        }
    });

    // Cập nhật giá base cho tất cả variants mới
    basePriceInput.addEventListener('change', () => {
        const basePrice = parseFloat(basePriceInput.value) || 0;
        variantTableBody.querySelectorAll('tr[data-variant-type="new"] input[name*="[price]"]').forEach(input => {
            input.value = basePrice;
        });
    });

    // Khởi tạo
    displayExistingVariants();
});

// Preview ảnh mới
document.getElementById('product_images').addEventListener('change', function(e) {
    if (this.files.length > 0) {
        document.getElementById('deleteAllImages').value = '1';
        
        const currentImages = document.getElementById('currentImages');
        if (currentImages) {
            currentImages.style.display = 'none';
        }
    }
    
    const previewContainer = document.getElementById('previewNewImages');
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
</script>
@endpush

@endsection