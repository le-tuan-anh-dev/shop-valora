@extends('admin.layouts.main_nav')
@section('title', 'Cập nhật mã giảm giá')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        {{-- Header Section --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                    <h4 class="page-title">Cập nhật mã giảm giá</h4>
                    <div class="page-title-right">
                        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">
                            <iconify-icon icon="solar:arrow-left-bold-duotone" class="me-1"></iconify-icon>
                            Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin mã giảm giá</h5>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Có lỗi xảy ra!</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.vouchers.update', $voucher->id) }}" 
                              method="POST" 
                              novalidate
                              id="voucherForm">
                            @csrf
                            @method('PUT')

                            {{-- Mã Code --}}
                            <div class="mb-3">
                                <label for="code" class="form-label">Mã Code <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('code') is-invalid @enderror" 
                                       id="code" 
                                       name="code" 
                                       placeholder="VD: SUMMER20, SALE10"
                                       value="{{ old('code', $voucher->code) }}"
                                       required
                                       style="text-transform: uppercase;">
                                <small class="form-text text-muted">Mã sẽ được tự động chuyển thành chữ hoa</small>
                                @error('code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Loại Giảm Giá --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="type" class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                                        <select class="form-select @error('type') is-invalid @enderror" 
                                                id="type" 
                                                name="type" 
                                                required
                                                onchange="updateValueLabel()">
                                            <option value="">-- Chọn loại --</option>
                                            <option value="fixed" {{ old('type', $voucher->type) === 'fixed' ? 'selected' : '' }}>
                                                Cố định (₫)
                                            </option>
                                            <option value="percent" {{ old('type', $voucher->type) === 'percent' ? 'selected' : '' }}>
                                                Phần trăm (%)
                                            </option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Giá Trị --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="value" class="form-label">
                                            Giá trị <span id="valueLabel"></span> <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   class="form-control @error('value') is-invalid @enderror" 
                                                   id="value" 
                                                   name="value" 
                                                   placeholder="0"
                                                   value="{{ old('value', $voucher->value) }}"
                                                   step="0.01"
                                                   min="0"
                                                   required>
                                            <span class="input-group-text" id="valueUnit">₫</span>
                                        </div>
                                        @error('value')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="min_order_value" class="form-label">
                                            Đơn hàng tối thiểu (₫)
                                        </label>
                                        <input type="number"
                                            class="form-control @error('min_order_value') is-invalid @enderror"
                                            id="min_order_value"
                                            name="min_order_value"
                                            placeholder="Ví dụ: 500000"
                                            value="{{ old('min_order_value', $voucher->min_order_value) }}"
                                            min="0">
                                        @error('min_order_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_discount_value" class="form-label">
                                            Mức giảm tối đa (₫) - áp dụng với mã giảm %
                                        </label>
                                        <input type="number"
                                            class="form-control @error('max_discount_value') is-invalid @enderror"
                                            id="max_discount_value"
                                            name="max_discount_value"
                                            placeholder="Ví dụ: 30000"
                                            value="{{ old('max_discount_value', $voucher->max_discount_value) }}"
                                            min="0">
                                        @error('max_discount_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Tổng Lượt Dùng & Giới Hạn/Người --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_uses" class="form-label">Tổng lượt sử dụng <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               class="form-control @error('max_uses') is-invalid @enderror" 
                                               id="max_uses" 
                                               name="max_uses" 
                                               placeholder="Ví dụ: 100"
                                               value="{{ old('max_uses', $voucher->max_uses) }}"
                                               min="{{ $voucher->used_count }}"
                                               required>
                                        <small class="form-text text-muted">Đã sử dụng: <strong>{{ $voucher->used_count }}</strong></small>
                                        @error('max_uses')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="per_user_limit" class="form-label">Giới hạn dùng/người <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               class="form-control @error('per_user_limit') is-invalid @enderror" 
                                               id="per_user_limit" 
                                               name="per_user_limit" 
                                               placeholder="Ví dụ: 1"
                                               value="{{ old('per_user_limit', $voucher->per_user_limit) }}"
                                               min="1"
                                               required>
                                        @error('per_user_limit')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Ngày Bắt Đầu & Kết Thúc --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="starts_at" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                        <input type="datetime-local" 
                                               class="form-control @error('starts_at') is-invalid @enderror" 
                                               id="starts_at" 
                                               name="starts_at" 
                                               value="{{ old('starts_at', $voucher->starts_at?->format('Y-m-d\TH:i')) }}"
                                               required>
                                        @error('starts_at')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ends_at" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                        <input type="datetime-local" 
                                               class="form-control @error('ends_at') is-invalid @enderror" 
                                               id="ends_at" 
                                               name="ends_at" 
                                               value="{{ old('ends_at', $voucher->ends_at?->format('Y-m-d\TH:i')) }}"
                                               required>
                                        @error('ends_at')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Áp Dụng Cho Sản Phẩm --}}
                            <div class="mb-3">
                                <label class="form-label">Áp dụng cho sản phẩm</label>
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           id="variantTypeAll" 
                                           name="variant_type" 
                                           value="all"
                                           {{ old('variant_type', $voucher->apply_all_products == 1 ? 'all' : 'specific') === 'all' ? 'checked' : '' }}
                                           onchange="toggleVariantSelection()">
                                    <label class="form-check-label" for="variantTypeAll">
                                        Áp dụng cho tất cả sản phẩm
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           id="variantTypeSpecific" 
                                           name="variant_type" 
                                           value="specific"
                                           {{ old('variant_type', $voucher->apply_all_products == 1 ? 'all' : 'specific') === 'specific' ? 'checked' : '' }}
                                           onchange="toggleVariantSelection()">
                                    <label class="form-check-label" for="variantTypeSpecific">
                                        Chỉ áp dụng cho sản phẩm cụ thể
                                    </label>
                                </div>

                                {{-- CHỌN SẢN PHẨM --}}
                                <div id="variantSelectionContainer" style="display: none;" class="mt-3">
                                    <label class="form-label">Chọn sản phẩm áp dụng</label>
                                    
                                    <div class="position-relative">
                                        <input id="prd-search" 
                                               type="text" 
                                               placeholder="Tìm sản phẩm theo tên hoặc SKU..." 
                                               class="form-control">
                                        
                                        <div id="prd-results" 
                                             class="position-absolute w-100 mt-1 d-none border rounded bg-white shadow-sm" 
                                             style="max-height: 300px; overflow-y: auto; z-index: 1000;">
                                        </div>
                                    </div>

                                    <div id="prd-hidden"></div>

                                    <div id="prd-selected" class="mt-3 d-flex flex-wrap gap-2"></div>

                                    {{-- Dữ liệu sản phẩm --}}
                                    <ul id="prd-source" class="d-none">
                                        @foreach($variants as $p)
                                            <li data-id="{{ $p->id }}" 
                                                data-name="{{ $p->product->name ?? 'Sản phẩm' }}" 
                                                data-sku="{{ $p->sku }}">
                                                {{ $p->product->name ?? 'Sản phẩm' }} — {{ $p->sku }}
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="mt-2 d-flex gap-2">
                                       
                                        <span class="badge bg-secondary align-self-center">
                                            Đã chọn: <span id="prd-count">0</span> sản phẩm
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Trạng Thái --}}
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1"
                                           @if(old('is_active', $voucher->is_active)) checked @endif>
                                    <label class="form-check-label" for="is_active">
                                        Kích hoạt mã giảm giá này
                                    </label>
                                </div>
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="d-flex gap-2 pt-3 border-top">
                                <button type="submit" class="btn btn-primary">
                                    <iconify-icon icon="solar:check-circle-bold-duotone" class="me-1"></iconify-icon>
                                    Cập nhật
                                </button>
                                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light">
                                    <iconify-icon icon="solar:close-circle-bold-duotone" class="me-1"></iconify-icon>
                                    Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Styles --}}
@push('styles')
<style>
    .page-content {
        padding: 20px 0;
    }

    .page-title-box {
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
        margin-bottom: 1.5rem !important;
    }

    .card {
        border: none;
        box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
        margin-bottom: 24px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 1rem 1.5rem;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 600;
    }

    .card-body {
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border: 1px solid #e9ecef;
        border-radius: 0.25rem;
        font-size: 0.95rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #084298;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc3545;
    }

    .form-select[multiple] {
        min-height: 200px;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
    }

    .btn {
        font-weight: 500;
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    .btn-light {
        color: #6c757d;
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    .btn-light:hover {
        color: #495057;
        background-color: #e2e6ea;
        border-color: #dae0e5;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .form-check-input {
        width: 1.25em;
        height: 1.25em;
        margin-top: 0.3125em;
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .form-check {
        margin-bottom: 1rem;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-color:  #e9ecef;
    }

    .alert {
        border-radius: 0.25rem;
    }

    #prd-results::-webkit-scrollbar {
        width: 8px;
    }

    #prd-results::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    #prd-results::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    #prd-results::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    #prd-selected .badge {
        padding: 8px 12px;
        margin-right: 8px;
        margin-bottom: 8px;
    }

    #variantSelectionContainer {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px ;
        
        margin-top: 15px;
    }

    @media (max-width: 768px) {
        .page-title-box {
            flex-direction: column;
        }

        .page-title-right {
            width: 100%;
            margin-top: 1rem;
        }

        .page-title-right .btn {
            width: 100%;
            
        }
    }
</style>
@endpush

{{-- Scripts --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('prd-search');
    const resultBox = document.getElementById('prd-results');
    const hiddenInputs = document.getElementById('prd-hidden');
    const selectedBox = document.getElementById('prd-selected');
    const source = document.getElementById('prd-source');
    const count = document.getElementById('prd-count');

    let selected = new Map(); // {id: {name, sku}}

    // Render danh sách sản phẩm
    function renderResults(query = '') {
        resultBox.innerHTML = '';
        const items = Array.from(source.querySelectorAll('li'));

        const filtered = items.filter(item => {
            const name = item.dataset.name.toLowerCase();
            const sku = (item.dataset.sku || '').toLowerCase();
            const q = query.toLowerCase();
            return name.includes(q) || sku.includes(q);
        });

        if (filtered.length === 0) {
            resultBox.innerHTML = '<div class="p-3 text-muted">Không tìm thấy sản phẩm</div>';
            resultBox.classList.remove('d-none');
            return;
        }

        filtered.forEach(item => {
            const id = item.dataset.id;
            const name = item.dataset.name;
            const sku = item.dataset.sku;

            const div = document.createElement('div');
            div.className = 'p-2 border-bottom d-flex align-items-center';
            div.style.cursor = 'pointer';

            div.innerHTML = `
                <div class="form-check w-100">
                    <input type="checkbox" 
                           class="form-check-input prd-checkbox" 
                           data-id="${id}" 
                           data-name="${name}" 
                           data-sku="${sku}"
                           ${selected.has(id) ? 'checked' : ''}
                           id="prd-check-${id}">
                    <label class="form-check-label w-100" for="prd-check-${id}" style="cursor: pointer;">
                        <strong>${name}</strong>
                        <small class="text-muted ms-1">${sku}</small>
                    </label>
                </div>
            `;

            const checkbox = div.querySelector('.prd-checkbox');

            // Ngăn sự kiện click lan truyền khi click vào checkbox
            checkbox.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            checkbox.addEventListener('change', function () {
                toggleProduct(id, name, sku, this.checked);
            });

            // Click vào div cũng toggle checkbox
            div.addEventListener('click', function(e) {
                if (!e.target.classList.contains('prd-checkbox')) {
                    checkbox.checked = !checkbox.checked;
                    toggleProduct(id, name, sku, checkbox.checked);
                }
            });
            // Hover effect
            div.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            div.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });

            resultBox.appendChild(div);
        });

        resultBox.classList.remove('d-none');
    }

    // Thêm / xóa sản phẩm
    function toggleProduct(id, name, sku, checked) {
        if (checked) {
            selected.set(id, { name, sku });
        } else {
            selected.delete(id);
        }
        updateUI();
    }

    // Update UI: chips + hidden + counter
    function updateUI() {
        selectedBox.innerHTML = '';
        hiddenInputs.innerHTML = '';

        selected.forEach((data, id) => {
            // Tạo chip hiển thị sản phẩm
            const chip = document.createElement('div');
            chip.style.cssText = `
                display: inline-block;
                color: black;
                padding: 6px 12px;
                border: 1px solid;
                border-radius: 5px;
                margin: 4px;
                font-size: 12px;    
            `;
            
            chip.innerHTML = `
                <span style="margin-right: 8px;">${data.sku}</span>
                <button type="button" 
                        data-id="${id}" 
                        style="background: none; border: none; color: black; font-size: 18px; line-height: 1; cursor: pointer; padding: 0; margin-left: 5px;"
                        class="remove-chip">×</button>
            `;
            
            selectedBox.appendChild(chip);

            chip.querySelector('.remove-chip').addEventListener('click', function (e) {
                e.preventDefault();
                selected.delete(id);
                updateUI();
                // Cập nhật lại checkbox trong kết quả tìm kiếm nếu đang mở
                const checkbox = resultBox.querySelector(`[data-id="${id}"]`);
                if (checkbox) {
                    checkbox.checked = false;
                }
            });

            // hidden input
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'variant_ids[]';
            inp.value = id;
            hiddenInputs.appendChild(inp);
        });

        count.textContent = selected.size;
    }

    // Focus vào search thì hiển thị kết quả
    searchInput.addEventListener('focus', function() {
        if (this.value.trim() !== '') {
            renderResults(this.value);
        }
    });

    // Search event
    searchInput.addEventListener('input', function () {
        renderResults(this.value);
    });

    // Click outside hide
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#prd-search') && !e.target.closest('#prd-results')) {
            resultBox.classList.add('d-none');
        }
    });

    // Update value label function
    function updateValueLabel() {
        const type = document.getElementById('type').value;
        const valueLabel = document.getElementById('valueLabel');
        const valueUnit = document.getElementById('valueUnit');
        const valueInput = document.getElementById('value');

        if (type === 'fixed') {
            valueLabel.textContent = '(₫)';
            valueUnit.textContent = '₫';
            valueInput.step = '1000';
            valueInput.removeAttribute('max');
        } else if (type === 'percent') {
            valueLabel.textContent = '(%)';
            valueUnit.textContent = '%';
            valueInput.step = '0.01';
            valueInput.max = '100';
        } else {
            valueLabel.textContent = '';
            valueUnit.textContent = '';
        }
    }

    // Toggle variant selection
    function toggleVariantSelection() {
        const container = document.getElementById('variantSelectionContainer');
        const typeSpecific = document.getElementById('variantTypeSpecific').checked;
        container.style.display = typeSpecific ? 'block' : 'none';
        
        // Clear selected products when switching to "all"
        if (!typeSpecific) {
            selected.clear();
            updateUI();
        }
    }

    // Bind functions to window for global access
    window.updateValueLabel = updateValueLabel;
    window.toggleVariantSelection = toggleVariantSelection;
    
    // Load selected variants on page load
    function loadSelectedVariants() {
        @if($voucher->apply_all_products == 0)
            const variantIds = @json($voucher->variants->pluck('id')->toArray());
            const sourceItems = Array.from(source.querySelectorAll('li'));
            
            sourceItems.forEach(item => {
                if (variantIds.includes(parseInt(item.dataset.id))) {
                    selected.set(item.dataset.id, {
                        name: item.dataset.name,
                        sku: item.dataset.sku
                    });
                }
            });
            updateUI();
        @endif
    }
    
    // Initial setup
    updateValueLabel();
    toggleVariantSelection();
    loadSelectedVariants();
});
</script>
@endpush

@endsection