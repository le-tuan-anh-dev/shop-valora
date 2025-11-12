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

        {{-- Success Message --}}
        @if(session('success'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <iconify-icon icon="solar:check-circle-bold-duotone" class="me-2"></iconify-icon>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Content --}}
        <div class="row">
            <div class="col-12">
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
                                @forelse($attributes as $key => $attribute)
                                    <tr>
                                        <td class="text-center text-muted fw-medium">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            <h6 class="fw-semibold mb-0">{{ $attribute->name }}</h6>
                                        </td>

                                        <td>
                                            <span class="badge bg-light-secondary text-secondary">{{ $attribute->slug }}</span>
                                        </td>

                                        <td>
                                            @if($attribute->values->count() > 0)
                                                <div class="d-flex gap-1 flex-wrap">
                                                    @foreach($attribute->values->take(3) as $value)
                                                        <span class="badge bg-light-info text-info">
                                                            {{ $value->value }}
                                                        </span>
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
                                                   data-bs-title="Chỉnh sửa">
                                                    <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon>
                                                </a>
                                                <form action="{{ route('admin.attributes.delete', $attribute->id) }}" 
                                                      method="POST" 
                                                      class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-icon btn-light" 
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-title="Xóa">
                                                        <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="text-danger"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="mb-3">
                                                <iconify-icon icon="solar:inbox-bold-duotone" style="font-size: 3rem; opacity: 0.5;"></iconify-icon>
                                            </div>
                                            <h5 class="text-muted">Không có thuộc tính nào</h5>
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

                    {{-- Chỉ hiển thị phân trang nếu $attributes là Paginator --}}
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
                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-end">
                                        {{ $attributes->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>

@push('styles')
<style>
    .page-content { padding: 20px 0; }
    .page-title-box { padding-bottom: 20px; border-bottom: 1px solid #e9ecef; margin-bottom: 1.5rem !important; }
    .card { border: none; box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15); margin-bottom: 24px; }
    .card-header { background-color: #f8f9fa; border-bottom: 1px solid #e9ecef; padding: 1rem 1.5rem; }
    .card-title { font-size: 1rem; font-weight: 600; }
    .card-footer { background-color: #f8f9fa; border-top: 1px solid #e9ecef; padding: 1rem 1.5rem; }
    .table > :not(caption) > * > * { padding: 0.85rem 0.75rem; vertical-align: middle; }
    .table-hover tbody tr:hover { background-color: rgba(0, 0, 0, 0.02); }
    .table-light { background-color: #f8f9fa; }
    .table-light th { background-color: #f8f9fa; font-weight: 600; border-bottom: 2px solid #e9ecef; }
    
    /* Badge colors */
    .badge.bg-light-info { background-color: #d1ecf1 !important; color: #0c5460 !important; }
    .badge.bg-light-secondary { background-color: #e2e3e5 !important; color: #383d41 !important; }
    .badge.bg-light-primary { background-color: #d1e7f7 !important; color: #084298 !important; }
    
    /* Button styles */
    .btn { font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem; }
    .btn-primary { background-color: #084298; border-color: #084298; }
    .btn-primary:hover { background-color: #0a3272; }
    .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
    .btn-light { background-color: #f8f9fa; border-color: #e9ecef; color: #495057; }
    .btn-light:hover { background-color: #e2e6ea; border-color: #dae0e5; color: #212529; }
    
    /* Alert */
    .alert { border: none; border-radius: 0.375rem; }
    .alert-success { background-color: rgba(10, 179, 156, 0.1); color: #0f7e4f; }
    
    /* Pagination */
    .pagination { margin: 0; }
    .pagination .page-link { color: #084298; border-color: #e9ecef; }
    .pagination .page-link:hover { background-color: #f8f9fa; border-color: #e9ecef; }
    .pagination .page-link.active { background-color: #084298; border-color: #084298; }
    
    @media (max-width: 576px) {
        .page-title-box { flex-direction: column; }
        .page-title-right { width: 100%; margin-top: 1rem; }
        .table { font-size: 0.875rem; }
        .btn { font-size: 0.9rem; padding: 0.5rem 0.75rem; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Delete confirmation
    document.body.addEventListener('submit', function(e) {
        if (e.target.classList.contains('delete-form')) {
            e.preventDefault();
            Swal.fire({
                title: 'Xóa thuộc tính?',
                text: "Thuộc tính này sẽ bị xóa vĩnh viễn!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="me-1"></iconify-icon> Xóa',
                cancelButtonText: '<iconify-icon icon="solar:close-circle-linear" class="me-1"></iconify-icon> Hủy',
                confirmButtonColor: '#f06548',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        }
    });
});
</script>
@endpush

@endsection