@extends('admin.layouts.main_nav')
@section('title', 'Danh sách thuộc tính')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        {{-- Header --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                    <h4 class="page-title">Danh sách thuộc tính</h4>
                    <div class="page-title-right">
                        <a href="{{ route('admin.attributes.add') }}" class="btn btn-primary">
                            <iconify-icon icon="solar:add-circle-bold-duotone" class="me-1"></iconify-icon>
                            Thêm thuộc tính
                        </a>
                    </div>
                </div>
            </div>
        </div>


@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <iconify-icon icon="solar:danger-circle-bold-duotone" class="me-2"></iconify-icon>
        {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <iconify-icon icon="solar:check-circle-bold-duotone" class="me-2"></iconify-icon>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Main Content --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <iconify-icon icon="solar:tag-bold-duotone" class="me-2"></iconify-icon>
                    Danh sách thuộc tính & giá trị
                </h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle table-centered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>Tên thuộc tính</th>
                            <th style="width: 150px;">Slug</th>
                            <th>Giá trị</th>
                            <th class="text-center" style="width: 120px;">Ngày tạo</th>
                            <th class="text-center" style="width: 140px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attributes as $attribute)
                            <tr>
                                <td class="text-center text-muted fw-medium">{{ $loop->iteration }}</td>
                                <td><h6 class="fw-semibold mb-0">{{ $attribute->name }}</h6></td>
                                <td><span class="badge bg-light-secondary text-secondary">{{ $attribute->slug }}</span></td>
                                <td>
                                    @if($attribute->values->count() > 0)
                                        <div class="d-flex gap-1 flex-wrap">
                                            @foreach($attribute->values->take(3) as $value)
                                                <span class="badge bg-light-info text-info">{{ $value->value }}</span>
                                            @endforeach
                                            @if($attribute->values->count() > 3)
                                                <span class="badge bg-light-primary text-primary">
                                                    +{{ $attribute->values->count() - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <small class="text-muted">
                                        {{ $attribute->created_at ? $attribute->created_at->format('d/m/Y') : '—' }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admin.attributes.edit', $attribute->id) }}" 
                                           class="btn btn-sm btn-icon btn-light" 
                                           data-bs-toggle="tooltip" 
                                           title="Chỉnh sửa">
                                            <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon>
                                        </a>
                                        <form action="{{ route('admin.attributes.delete', $attribute->id) }}" 
                                              method="POST" 
                                              class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-icon btn-light text-danger" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Xóa">
                                                <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <iconify-icon icon="solar:inbox-bold-duotone" style="font-size: 3rem; opacity: 0.5;"></iconify-icon>
                                    <h5 class="text-muted mt-2">Không có thuộc tính nào</h5>
                                    <p class="text-muted mb-3">Bắt đầu thêm thuộc tính mới vào hệ thống</p>
                                    <a href="{{ route('admin.attributes.add') }}" class="btn btn-primary">
                                        <iconify-icon icon="solar:add-circle-bold-duotone" class="me-1"></iconify-icon>
                                        Thêm thuộc tính mới
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attributes instanceof \Illuminate\Pagination\AbstractPaginator && $attributes->hasPages())
                <div class="card-footer bg-light">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <div class="text-muted">
                                Hiển thị <span class="fw-semibold">{{ $attributes->firstItem() }}</span> 
                                đến <span class="fw-semibold">{{ $attributes->lastItem() }}</span> 
                                trong tổng <span class="fw-semibold">{{ $attributes->total() }}</span> thuộc tính
                            </div>
                        </div>
                        <div class="col-sm-6 d-flex justify-content-end">
                            {{ $attributes->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hiện tooltip
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });

    // Xác nhận xóa
    document.body.addEventListener('submit', function(e) {
        if (e.target.classList.contains('delete-form')) {
            if (!confirm('Bạn có chắc chắn muốn xóa thuộc tính này không?\nHành động này không thể hoàn tác!')) {
                e.preventDefault();
            }
        }
    });
});
</script>
@endpush

@endsection
