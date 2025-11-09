@extends('admin.layouts.main_nav')

@section('content')
<style>
.product-form-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 0;
}

.form-card {
    background: white;
    border-radius: 0;
    box-shadow: none;
    overflow: hidden;
}

.form-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    text-align: center;
}

.form-header h2 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.form-body {
    padding: 2.5rem;
}

.section-divider {
    border: none;
    height: 2px;
    background: linear-gradient(90deg, transparent, #667eea, transparent);
    margin: 2.5rem 0;
}

.section-title {
    color: #667eea;
    font-weight: 700;
    font-size: 1.4rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-control, .form-select {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.input-group-icon {
    position: relative;
}

.input-group-icon input {
    padding-left: 2.5rem;
}

.input-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    font-size: 1.1rem;
}

.variant-table {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.variant-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.variant-table thead th {
    border: none;
    padding: 1rem;
    font-weight: 600;
    text-align: center;
}

.variant-table tbody td {
    padding: 0.75rem;
    vertical-align: middle;
    border-color: #e2e8f0;
}

.variant-table tbody tr {
    transition: background 0.2s ease;
}

.variant-table tbody tr:hover {
    background: #f7fafc;
}

.btn-add-variant {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
}

.btn-add-variant:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(72, 187, 120, 0.4);
    color: white;
}

.btn-remove {
    background: linear-gradient(135deg, #fc8181 0%, #f56565 100%);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-remove:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(252, 129, 129, 0.4);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #e2e8f0;
}

.btn-cancel {
    background: #718096;
    color: white;
    border: none;
    padding: 0.75rem 2.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    background: #4a5568;
    transform: translateY(-2px);
    color: white;
}

.btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 0.75rem 2.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

.upload-file-wrapper {
    position: relative;
}

.upload-file-wrapper input[type="file"] {
    cursor: pointer;
}

.upload-file-wrapper input[type="file"]::file-selector-button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    margin-right: 1rem;
    transition: all 0.3s ease;
}

.upload-file-wrapper input[type="file"]::file-selector-button:hover {
    transform: scale(1.05);
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-active {
    background: #c6f6d5;
    color: #22543d;
}

.status-inactive {
    background: #fed7d7;
    color: #742a2a;
}

@media (max-width: 768px) {
    .form-body {
        padding: 1.5rem;
    }
    
    .variant-table {
        font-size: 0.85rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-cancel, .btn-submit {
        width: 100%;
    }
}
</style>

<div class="product-form-container">
    <div class="container-fluid p-0">
        <div class="form-card">
            <div class="form-header">
                <h2>
                    <span>✨</span>
                    <span>Thêm Sản Phẩm Mới</span>
                </h2>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- 📋 THÔNG TIN CƠ BẢN --}}
                    <div class="section-title">
                        <span>📋</span>
                        <span>Thông Tin Cơ Bản</span>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Tên sản phẩm</label>
                            <input type="text" name="name" class="form-control" placeholder="Nhập tên sản phẩm" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Danh mục</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $cate)
                                    <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Giá gốc (VNĐ)</label>
                            <input type="number" step="0.01" name="base_price" class="form-control" placeholder="0">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Giá giảm (VNĐ)</label>
                            <input type="number" step="0.01" name="discount_price" class="form-control" placeholder="0">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Số lượng tồn kho</label>
                            <input type="number" name="stock" class="form-control" placeholder="0">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Ảnh đại diện</label>
                            <div class="upload-file-wrapper">
                                <input type="file" name="image_main" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Mô tả sản phẩm</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Nhập mô tả chi tiết về sản phẩm..."></textarea>
                        </div>
                    </div>

                    {{-- ⚙️ BIẾN THỂ SẢN PHẨM --}}
                    <div class="section-divider"></div>
                    <div class="section-title">
                        <span>⚙️</span>
                        <span>Biến Thể Sản Phẩm</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table variant-table" id="variantTable">
                            <thead>
                                <tr>
                                    <th>Tên biến thể</th>
                                    <th>SKU</th>
                                    <th>Giá (VNĐ)</th>
                                    <th>Tồn kho</th>
                                    <th>Ảnh</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="variants[0][title]" class="form-control" placeholder="VD: Màu đỏ - Size M"></td>
                                    <td><input type="text" name="variants[0][sku]" class="form-control" placeholder="SKU001"></td>
                                    <td><input type="number" step="0.01" name="variants[0][price]" class="form-control" placeholder="0"></td>
                                    <td><input type="number" name="variants[0][stock]" class="form-control" placeholder="0"></td>
                                    <td><input type="file" name="variants[0][image_url]" class="form-control" accept="image/*"></td>
                                    <td>
                                        <select name="variants[0][is_active]" class="form-select">
                                            <option value="1" selected>✅ Còn bán</option>
                                            <option value="0">❌ Ngừng bán</option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-remove removeRow">🗑️ Xóa</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" id="addVariant" class="btn btn-add-variant mt-3">
                        <span>➕ Thêm biến thể mới</span>
                    </button>

                    {{-- FORM ACTIONS --}}
                    <div class="form-actions">
                        <a href="{{ route('admin.products.list') }}" class="btn btn-cancel">Hủy bỏ</a>
                        <button type="submit" class="btn btn-submit">💾 Lưu sản phẩm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- JS thêm hàng biến thể & kiểm tra giá --}}
<script>
let index = 1;

document.getElementById('addVariant').addEventListener('click', function() {
    let table = document.querySelector('#variantTable tbody');
    let row = `
        <tr style="animation: fadeIn 0.3s ease;">
            <td><input type="text" name="variants[${index}][title]" class="form-control" placeholder="VD: Màu xanh - Size L"></td>
            <td><input type="text" name="variants[${index}][sku]" class="form-control" placeholder="SKU00${index}"></td>
            <td><input type="number" step="0.01" name="variants[${index}][price]" class="form-control" placeholder="0"></td>
            <td><input type="number" name="variants[${index}][stock]" class="form-control" placeholder="0"></td>
            <td><input type="file" name="variants[${index}][image_url]" class="form-control" accept="image/*"></td>
            <td>
                <select name="variants[${index}][is_active]" class="form-select">
                    <option value="1" selected>✅ Còn bán</option>
                    <option value="0">❌ Ngừng bán</option>
                </select>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-remove removeRow">🗑️ Xóa</button>
            </td>
        </tr>`;
    table.insertAdjacentHTML('beforeend', row);
    index++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('removeRow') || e.target.closest('.removeRow')) {
        const button = e.target.classList.contains('removeRow') ? e.target : e.target.closest('.removeRow');
        const row = button.closest('tr');
        row.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => row.remove(), 300);
    }
});

// ⚠️ KIỂM TRA GIÁ GIẢM < GIÁ GỐC
const basePriceInput = document.querySelector('input[name="base_price"]');
const discountPriceInput = document.querySelector('input[name="discount_price"]');

function validateDiscount() {
    const base = parseFloat(basePriceInput.value) || 0;
    const discount = parseFloat(discountPriceInput.value) || 0;
    if (discount >= base && base > 0) {
        discountPriceInput.style.borderColor = '#e53e3e';
        discountPriceInput.setCustomValidity("⚠️ Giá giảm phải nhỏ hơn giá gốc!");
    } else {
        discountPriceInput.style.borderColor = '';
        discountPriceInput.setCustomValidity('');
    }
}

basePriceInput.addEventListener('input', validateDiscount);
discountPriceInput.addEventListener('input', validateDiscount);

document.querySelector('form').addEventListener('submit', function(e) {
    const base = parseFloat(basePriceInput.value) || 0;
    const discount = parseFloat(discountPriceInput.value) || 0;
    if (discount >= base && base > 0) {
        e.preventDefault();
        alert("❌ Giá giảm phải nhỏ hơn giá gốc!");
        discountPriceInput.focus();
    }
});

// Animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeOut {
        from { opacity: 1; transform: scale(1); }
        to { opacity: 0; transform: scale(0.95); }
    }
`;
document.head.appendChild(style);
</script>
@endsection
