@extends('admin.layouts.main_nav')

@section('title', 'Chi tiết sản phẩm - ' . $product->name)

@section('content')
<div class="page-content">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold">Chi tiết sản phẩm</h4>
            <div>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Sửa
                </a>
                <a href="{{ route('admin.products.list') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Bên trái: Thông tin cơ bản --}}
            <div class="col-lg-7">
                {{-- Card Thông tin cơ bản --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin cơ bản</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="fw-semibold">Tên sản phẩm:</label>
                                <p class="text-dark">{{ $product->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Danh mục:</label>
                                <p class="text-dark">
                                    {{ $product->category?->name ?? 'Không có' }}
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="fw-semibold">Thương hiệu:</label>
                                <p class="text-dark">
                                    {{ $product->brand?->name ?? 'Không có' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Trạng thái:</label>
                                <p>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Hiển thị</span>
                                    @else
                                        <span class="badge bg-danger">Ẩn</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Mô tả:</label>
                            <p class="text-dark">{{ $product->description ?: 'Không có mô tả' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Card Hình ảnh --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Hình ảnh sản phẩm</h5>
                    </div>
                    <div class="card-body text-center">
                        @if($product->image_main)
                            <img src="{{ asset('storage/' . $product->image_main) }}" alt="{{ $product->name }}" class="img-fluid" style="max-width: 400px; max-height: 400px;">
                        @else
                            <p class="text-muted">Không có hình ảnh</p>
                        @endif
                    </div>
                </div>

                {{-- Card Biến thể sản phẩm --}}
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Biến thể sản phẩm ({{ $product->variants->count() }})</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($product->variants->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>SKU</th>
                                            <th>Thuộc tính</th>
                                            <th>Giá (đ)</th>
                                            <th>Tồn kho</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variants as $idx => $variant)
                                            <tr>
                                                <td><strong>{{ $idx + 1 }}</strong></td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $variant->sku ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    {{--  Hiển thị thuộc tính của variant --}}
                                                    @php
                                                        $variantAttrs = \App\Models\Admin\VariantAttributeValue::where('variant_id', $variant->id)
                                                            ->with(['attributeValue' => function($q) {
                                                                $q->with('attribute');
                                                            }])
                                                            ->get();
                                                    @endphp

                                                    @if($variantAttrs->count() > 0)
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @foreach($variantAttrs as $vav)
                                                                <span class="badge bg-info">
                                                                    {{ $vav->attributeValue->attribute->name }}: 
                                                                    <strong>{{ $vav->attributeValue->value }}</strong>
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Không có thuộc tính</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ number_format($variant->price, 0, ',', '.') }}</strong>
                                                </td>
                                                <td>
                                                    @if($variant->stock > 0)
                                                        <span class="badge bg-success">{{ $variant->stock }}</span>
                                                    @else
                                                        <span class="badge bg-danger">Hết (0)</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($variant->is_active)
                                                        <span class="badge bg-success">Hiển thị</span>
                                                    @else
                                                        <span class="badge bg-secondary">Ẩn</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#variantDetailModal{{ $variant->id }}" title="Xem chi tiết">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i> Sản phẩm này chưa có biến thể nào.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Bên phải: Giá & Tồn kho --}}
            <div class="col-lg-5">
                {{-- Card Giá --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin giá</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="fw-semibold">Giá nhập:</label>
                                <p class="text-danger h5">{{ number_format($product->cost_price, 0, ',', '.') }} đ</p>
                            </div>
                            <div class="col-6">
                                <label class="fw-semibold">Giá bán:</label>
                                <p class="text-success h5">{{ number_format($product->base_price, 0, ',', '.') }} đ</p>
                            </div>
                        </div>

                        @if($product->discount_price)
                            <div class="mb-3">
                                <label class="fw-semibold">Giá khuyến mãi:</label>
                                <p class="text-warning h5">{{ number_format($product->discount_price, 0, ',', '.') }} đ</p>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="fw-semibold">Lợi nhuận (%):</label>
                            @php
                                $profit = (($product->base_price - $product->cost_price) / $product->cost_price) * 100;
                            @endphp
                            <p class="text-info h5">{{ number_format($profit, 2, ',', '.') }}%</p>
                        </div>
                    </div>
                </div>

                {{-- Card Tồn kho --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin tồn kho</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="fw-semibold">Tồn kho sản phẩm:</label>
                            <p class="h5">
                                @if($product->stock > 0)
                                    <span class="badge bg-success fs-6">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-danger fs-6">Hết hàng (0)</span>
                                @endif
                            </p>
                        </div>

                        @if($product->variants->count() > 0)
                            <div class="mb-3">
                                <label class="fw-semibold">Tồn kho từ biến thể:</label>
                                <p class="h5">
                                    @if($totalVariantStock > 0)
                                        <span class="badge bg-info fs-6">{{ $totalVariantStock }}</span>
                                    @else
                                        <span class="badge bg-danger fs-6">Hết hàng (0)</span>
                                    @endif
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="fw-semibold">Số biến thể:</label>
                                <p class="h5"><span class="badge bg-secondary fs-6">{{ $product->variants->count() }}</span></p>
                            </div>
                        @endif

                        <div class="alert alert-info" role="alert">
                            <small>
                                <strong>Lưu ý:</strong> Khi sản phẩm có biến thể, tồn kho sản phẩm = tổng tồn kho các biến thể
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Card Thống kê --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Thống kê</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="fw-semibold">Đã bán:</label>
                            <p class="h5"><span class="badge bg-primary fs-6">{{ $product->sold_count ?? 0 }}</span></p>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Ngày tạo:</label>
                            <p class="text-muted">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Ngày cập nhật:</label>
                            <p class="text-muted">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--  Modals chi tiết variant --}}
@foreach($product->variants as $variant)
    <div class="modal fade" id="variantDetailModal{{ $variant->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết Biến thể #{{ $variant->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @php
                        $variantAttrs = \App\Models\Admin\VariantAttributeValue::where('variant_id', $variant->id)
                            ->with(['attributeValue' => function($q) {
                                $q->with('attribute');
                            }])
                            ->get();
                    @endphp

                    <div class="mb-3">
                        <label class="fw-semibold">SKU:</label>
                        <p>{{ $variant->sku ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-semibold">Giá:</label>
                        <p class="text-success h5">{{ number_format($variant->price, 0, ',', '.') }} đ</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-semibold">Tồn kho:</label>
                        <p class="h5">
                            @if($variant->stock > 0)
                                <span class="badge bg-success">{{ $variant->stock }}</span>
                            @else
                                <span class="badge bg-danger">Hết (0)</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-semibold">Thuộc tính:</label>
                        @if($variantAttrs->count() > 0)
                            <div class="d-flex flex-column gap-2">
                                @foreach($variantAttrs as $vav)
                                    <div class="p-2 bg-light rounded">
                                        <strong>{{ $vav->attributeValue->attribute->name }}:</strong>
                                        {{ $vav->attributeValue->value }}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Không có thuộc tính</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="fw-semibold">Trạng thái:</label>
                        <p>
                            @if($variant->is_active)
                                <span class="badge bg-success">Hiển thị</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-semibold">Ngày tạo:</label>
                        <p class="text-muted">{{ $variant->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection