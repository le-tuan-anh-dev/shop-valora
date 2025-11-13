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
                    </div>

                    {{-- Biến thể --}}
                    <div class="card mb-3 p-3">
                        <h5 class="fw-semibold mb-3">Biến thể sản phẩm</h5>
                        
                        {{-- Container chứa tất cả nhóm phân loại --}}
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
                                    @foreach($product->variants as $i => $v)
                                    <tr data-variant-id="{{ $v->id }}">
                                        <td>
                                            {{ $v->title }}
                                            <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $v->id }}">
                                            <input type="hidden" name="variants[{{ $i }}][title]" value="{{ $v->title }}">
                                            <input type="hidden" name="variants[{{ $i }}][value_ids]" value="{{ $v->value_ids }}">
                                        </td>
                                        <td><input type="text" name="variants[{{ $i }}][sku]" class="form-control variant-sku" value="{{ $v->sku }}"></td>
                                        <td><input type="number" step="0.01" name="variants[{{ $i }}][price]" class="form-control variant-price" value="{{ $v->price }}"></td>
                                        <td><input type="number" name="variants[{{ $i }}][stock]" class="form-control variant-stock" value="{{ $v->stock }}"></td>
                                        <td><button type="button" class="btn btn-danger btn-sm remove-variant">X</button></td>
                                    </tr>
                                    @endforeach
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
                            <input type="number"  name="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price', $product->cost_price) }}">
                            @error('cost_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giá bán (₫) *</label>
                            <input type="number" name="base_price" class="form-control @error('base_price') is-invalid @enderror" value="{{ old('base_price', $product->base_price) }}">
                            @error('base_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giá khuyến mãi (₫)</label>
                            <input type="number"  name="discount_price" class="form-control @error('discount_price') is-invalid @enderror" value="{{ old('discount_price', $product->discount_price) }}">
                            @error('discount_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tồn kho *</label>
                            <input type="number" name="stock" class="form-control" id="product-stock" value="{{ old('stock', $product->stock) }}">
                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Danh mục & kho --}}
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
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                            <label class="form-check-label">Hiển thị sản phẩm</label>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Cập nhật sản phẩm</button>
                    </div>

                </div>

            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const attributes = @json($attributes);
    const existingVariantsData = @json($product->variants->toArray());
    
    const allGroupsContainer = document.getElementById('all-variant-groups');
    const variantTableBody = document.querySelector('#variant-table tbody');
    const addVariantGroupBtn = document.getElementById('add-variant-group');
    const productStockInput = document.getElementById('product-stock');

    // Khởi tạo các nhóm từ biến thể hiện có
    function initializeExistingGroups() {
        const existingGroups = {};
        
        existingVariantsData.forEach(variant => {
            if(variant.value_ids) {
                const valueIds = variant.value_ids.split(',');
                valueIds.forEach(valueId => {
                    attributes.forEach(attr => {
                        attr.values.forEach(val => {
                            if(val.id == valueId) {
                                if(!existingGroups[attr.id]) {
                                    existingGroups[attr.id] = {
                                        attr: attr,
                                        valueIds: []
                                    };
                                }
                                if(!existingGroups[attr.id].valueIds.includes(valueId.toString())) {
                                    existingGroups[attr.id].valueIds.push(valueId.toString());
                                }
                            }
                        });
                    });
                });
            }
        });

        // Tạo HTML cho các nhóm hiện có
        Object.keys(existingGroups).forEach(attrId => {
            const group = existingGroups[attrId];
            createVariantGroup(group.attr, group.valueIds);
        });
    }

    // Tạo một nhóm phân loại
    function createVariantGroup(attr = null, selectedValueIds = []) {
        const groupDiv = document.createElement('div');
        groupDiv.classList.add('border','p-3','rounded','mb-3','variant-group');
        
        const selectedAttrIds = getSelectedAttrIds();
        
        groupDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <select class="form-select attr-select">
                    <option value="">-- Chọn phân loại --</option>
                    ${attributes.map(a => {
                        if(attr && a.id === attr.id) {
                            return `<option value="${a.id}" selected>${a.name}</option>`;
                        } else if(!selectedAttrIds.includes(a.id.toString())) {
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
        
        // Nếu có attr được chọn sẵn, hiển thị values
        if(attr) {
            const container = groupDiv.querySelector('.value-container');
            container.innerHTML = `
                <label class="fw-semibold">Tùy chọn:</label>
                <div class="d-flex flex-wrap gap-2 mt-1">
                    ${attr.values.map(v => `
                        <label class="form-check form-check-inline">
                            <input class="form-check-input value-checkbox" 
                                   type="checkbox" 
                                   value="${v.id}" 
                                   data-name="${v.value}"
                                   ${selectedValueIds.includes(v.id.toString()) ? 'checked' : ''}>
                            <span class="form-check-label">${v.value}</span>
                        </label>
                    `).join('')}
                </div>
            `;
        }
    }

    // Lấy danh sách attr ID đã được chọn
    function getSelectedAttrIds() {
        const ids = [];
        allGroupsContainer.querySelectorAll('.attr-select').forEach(select => {
            if(select.value) ids.push(select.value);
        });
        return ids;
    }

    // Event: Thêm nhóm mới
    addVariantGroupBtn.addEventListener('click', () => {
        createVariantGroup();
    });

    // Event: Thay đổi trong container
    allGroupsContainer.addEventListener('change', e => {
        if(e.target.classList.contains('attr-select')) {
            const selectedAttr = attributes.find(a => a.id == e.target.value);
            const container = e.target.closest('.variant-group').querySelector('.value-container');
            
            if(selectedAttr) {
                container.innerHTML = `
                    <label class="fw-semibold">Tùy chọn:</label>
                    <div class="d-flex flex-wrap gap-2 mt-1">
                        ${selectedAttr.values.map(v => `
                            <label class="form-check form-check-inline">
                                <input class="form-check-input value-checkbox" 
                                       type="checkbox" 
                                       value="${v.id}" 
                                       data-name="${v.value}">
                                <span class="form-check-label">${v.value}</span>
                            </label>
                        `).join('')}
                    </div>
                `;
            } else {
                container.innerHTML = '';
            }
        }
        
        if(e.target.classList.contains('value-checkbox')) {
            generateVariants();
        }
    });

    // Event: Xóa nhóm
    allGroupsContainer.addEventListener('click', e => {
        if(e.target.classList.contains('remove-group')) {
            e.target.closest('.variant-group').remove();
            generateVariants();
        }
    });

    // Event: Xóa biến thể
    variantTableBody.addEventListener('click', e => {
        if(e.target.closest('.remove-variant')) {
            e.target.closest('tr').remove();
            updateProductStock();
        }
    });

    // Hàm cartesian
    function cartesian(arr) {
        if(arr.length === 0) return [];
        return arr.reduce((a, b) => {
            const ret = [];
            a.forEach(aElem => b.forEach(bElem => ret.push(aElem.concat ? aElem.concat([bElem]) : [aElem, bElem])));
            return ret;
        });
    }

    // Hàm sắp xếp value_ids để so sánh
    function sortValueIds(ids) {
        return ids.split(',').sort((a, b) => parseInt(a) - parseInt(b)).join(',');
    }

    // Generate variants - REBUILD toàn bộ nhưng giữ dữ liệu cũ
    function generateVariants() {
        // Lưu toàn bộ dữ liệu biến thể hiện có
        const existingVariantsMap = new Map();
        variantTableBody.querySelectorAll('tr').forEach(row => {
            const idInput = row.querySelector('input[name*="[id]"]');
            const titleInput = row.querySelector('input[name*="[title]"]');
            const valueIdsInput = row.querySelector('input[name*="[value_ids]"]');
            const skuInput = row.querySelector('input[name*="[sku]"]');
            const priceInput = row.querySelector('input[name*="[price]"]');
            const stockInput = row.querySelector('input[name*="[stock]"]');
            
            if(valueIdsInput && valueIdsInput.value) {
                const sortedIds = sortValueIds(valueIdsInput.value);
                existingVariantsMap.set(sortedIds, {
                    id: idInput ? idInput.value : '',
                    title: titleInput ? titleInput.value : '',
                    value_ids: valueIdsInput.value,
                    sku: skuInput ? skuInput.value : '',
                    price: priceInput ? priceInput.value : 0,
                    stock: stockInput ? stockInput.value : 0
                });
            }
        });

        // Lấy các nhóm đã chọn
        const selectedGroups = [];
        allGroupsContainer.querySelectorAll('.variant-group').forEach(group => {
            const attrSelect = group.querySelector('.attr-select');
            const checkboxes = group.querySelectorAll('.value-checkbox:checked');
            
            if(attrSelect && attrSelect.value && checkboxes.length) {
                selectedGroups.push({
                    attr_id: attrSelect.value,
                    attr_name: attrSelect.options[attrSelect.selectedIndex].text,
                    values: Array.from(checkboxes).map(c => ({id: c.value, name: c.dataset.name}))
                });
            }
        });

        if(selectedGroups.length === 0) {
            // Nếu không có nhóm nào, giữ nguyên
            return;
        }

        // Tạo combos mới
        const combos = cartesian(selectedGroups.map(g => g.values));
        let variantIndex = 0;
        
        // REBUILD toàn bộ bảng với tổ hợp mới
        variantTableBody.innerHTML = combos.map(combo => {
            const values = Array.isArray(combo) ? combo : [combo];
            const label = values.map(v => v.name).join(' / ');
            const ids = values.map(v => v.id).join(',');
            const sortedIds = sortValueIds(ids);
            
            // Tìm dữ liệu cũ dựa trên value_ids đã sắp xếp
            const existingVariant = existingVariantsMap.get(sortedIds);
            const idx = variantIndex++;
            
            return `
                <tr ${existingVariant && existingVariant.id ? 'data-variant-id="'+existingVariant.id+'"' : ''}>
                    <td>
                        ${label}
                        <input type="hidden" name="variants[${idx}][id]" value="${existingVariant ? existingVariant.id : ''}">
                        <input type="hidden" name="variants[${idx}][value_ids]" value="${ids}">
                        <input type="hidden" name="variants[${idx}][title]" value="${label}">
                    </td>
                    <td><input type="text" name="variants[${idx}][sku]" class="form-control" value="${existingVariant ? existingVariant.sku : ''}"></td>
                    <td><input type="number" step="0.01" name="variants[${idx}][price]" class="form-control" value="${existingVariant ? existingVariant.price : 0}"></td>
                    <td><input type="number" name="variants[${idx}][stock]" class="form-control variant-stock" value="${existingVariant ? existingVariant.stock : 0}"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-variant">X</button></td>
                </tr>
            `;
        }).join('');
        
        updateProductStock();
    }

    // Cập nhật tổng stock
    function updateProductStock() {
        const variantRows = variantTableBody.querySelectorAll('tr');
        if(variantRows.length > 0) {
            let totalStock = 0;
            variantRows.forEach(row => {
                const stockInput = row.querySelector('.variant-stock');
                if(stockInput) {
                    totalStock += parseInt(stockInput.value) || 0;
                    stockInput.removeEventListener('input', updateProductStock);
                    stockInput.addEventListener('input', updateProductStock);
                }
            });
            productStockInput.value = totalStock;
            productStockInput.setAttribute('readonly', true);
        } else {
            productStockInput.removeAttribute('readonly');
        }
    }

    // Khởi tạo
    initializeExistingGroups();
    updateProductStock();
});
</script>
@endpush
@endsection