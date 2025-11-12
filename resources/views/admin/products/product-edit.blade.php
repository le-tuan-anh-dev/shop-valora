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
                        <h5 class="fw-semibold">Biến thể sản phẩm</h5>
                        <button type="button" class="btn btn-sm btn-warning mb-2" id="add-variant-group">Thêm biến thể</button>

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
                                    @foreach($product->variants as $i => $v)
                                    <tr>
                                        <td>
                                            {{ $v->title }}
                                            <input type="hidden" name="variants[{{ $i }}][title]" value="{{ $v->title }}">
                                            <input type="hidden" name="variants[{{ $i }}][value_ids]" value="{{ $v->value_ids }}">
                                        </td>
                                        <td><input type="text" name="variants[{{ $i }}][sku]" class="form-control variant-sku" value="{{ $v->sku }}"></td>
                                        <td><input type="number" name="variants[{{ $i }}][price]" class="form-control variant-price" value="{{ $v->price }}"></td>
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
                            <input type="number" step="0.01" name="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price', $product->cost_price) }}">
                            @error('cost_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giá gốc (₫) *</label>
                            <input type="number" step="0.01" name="base_price" class="form-control @error('base_price') is-invalid @enderror" value="{{ old('base_price', $product->base_price) }}">
                            @error('base_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giá khuyến mãi (₫)</label>
                            <input type="number" step="0.01" name="discount_price" class="form-control @error('discount_price') is-invalid @enderror" value="{{ old('discount_price', $product->discount_price) }}">
                            @error('discount_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Danh mục & kho --}}
                    <div class="card mb-3 p-3">
                        <h5 class="fw-semibold">Danh mục & Kho</h5>
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
                            <label class="form-label">Tồn kho *</label>
                            <input type="number" name="stock" class="form-control" id="product-stock" value="{{ old('stock', $product->stock) }}">
                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
    const variantGroupList = document.querySelector('.variant-group-list');
    const variantTableBody = document.querySelector('#variant-table tbody');
    const addVariantGroupBtn = document.getElementById('add-variant-group');
    const productStockInput = document.getElementById('product-stock');

    addVariantGroupBtn.addEventListener('click', ()=>{
        const groupDiv = document.createElement('div');
        groupDiv.classList.add('border','p-3','rounded','mb-3');
        groupDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <select class="form-select attr-select">
                    <option value="">-- Chọn phân loại --</option>
                    ${attributes.map(a=>`<option value="${a.id}">${a.name}</option>`).join('')}
                </select>
                <button type="button" class="btn btn-danger btn-sm remove-group ms-2">X</button>
            </div>
            <div class="value-container"></div>
        `;
        variantGroupList.appendChild(groupDiv);
    });

    variantGroupList.addEventListener('change', e=>{
        if(e.target.classList.contains('attr-select')){
            const selectedAttr = attributes.find(a=>a.id==e.target.value);
            const container = e.target.closest('.border').querySelector('.value-container');
            container.innerHTML = selectedAttr ? `
                <label class="fw-semibold">Tùy chọn:</label>
                <div class="d-flex flex-wrap gap-2 mt-1">
                    ${selectedAttr.values.map(v=>`
                        <label class="form-check form-check-inline">
                            <input class="form-check-input value-checkbox" type="checkbox" value="${v.id}" data-name="${v.value}">
                            <span class="form-check-label">${v.value}</span>
                        </label>
                    `).join('')}
                </div>
            `:'';
            generateVariants();
        }
        if(e.target.classList.contains('value-checkbox')) generateVariants();
    });

    variantGroupList.addEventListener('click', e=>{
        if(e.target.classList.contains('remove-group')){
            e.target.closest('.border').remove();
            generateVariants();
        }
    });

    variantTableBody.addEventListener('click', e=>{
        if(e.target.closest('.remove-variant')){
            e.target.closest('tr').remove();
            updateProductStock();
        }
    });

    function cartesian(arr){
        if(arr.length===0) return [];
        return arr.reduce((a,b)=>{
            const ret=[];
            a.forEach(aElem=>b.forEach(bElem=>ret.push(aElem.concat?aElem.concat([bElem]):[aElem,bElem])));
            return ret;
        });
    }

    function generateVariants(){
        const selectedGroups=[];
        variantGroupList.querySelectorAll('.border').forEach(group=>{
            const attrSelect=group.querySelector('.attr-select');
            const checkboxes=group.querySelectorAll('.value-checkbox:checked');
            if(attrSelect && attrSelect.value && checkboxes.length){
                selectedGroups.push({
                    attr_id:attrSelect.value,
                    attr_name:attrSelect.options[attrSelect.selectedIndex].text,
                    values:Array.from(checkboxes).map(c=>({id:c.value,name:c.dataset.name}))
                });
            }
        });

        let combos=[];
        if(selectedGroups.length>0) combos=cartesian(selectedGroups.map(g=>g.values));

        let variantIndex=0;
        variantTableBody.innerHTML = combos.map(combo=>{
            const values = Array.isArray(combo)?combo:[combo];
            const label = values.map(v=>v.name).join(' / ');
            const ids = values.map(v=>v.id).join(',');
            const idx = variantIndex++;
            return `
                <tr>
                    <td>
                        ${label}
                        <input type="hidden" name="variants[${idx}][value_ids]" value="${ids}">
                        <input type="hidden" name="variants[${idx}][title]" value="${label}">
                    </td>
                    <td><input type="text" name="variants[${idx}][sku]" class="form-control"></td>
                    <td><input type="number" name="variants[${idx}][price]" class="form-control" value="0"></td>
                    <td><input type="number" name="variants[${idx}][stock]" class="form-control variant-stock" value="0"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-variant">X</button></td>
                </tr>
            `;
        }).join('');
        updateProductStock();
    }

    function updateProductStock(){
        const variantRows = variantTableBody.querySelectorAll('tr');
        if(variantRows.length>0){
            let totalStock=0;
            variantRows.forEach(row=>{
                const stockInput=row.querySelector('.variant-stock');
                totalStock += parseInt(stockInput.value)||0;
                stockInput.addEventListener('input', ()=>updateProductStock());
            });
            productStockInput.value=totalStock;
            productStockInput.setAttribute('readonly', true);
        }else{
            productStockInput.removeAttribute('readonly');
        }
    }

    // Khởi tạo tổng stock từ các biến thể hiện tại
    updateProductStock();
});
</script>
@endpush
@endsection
