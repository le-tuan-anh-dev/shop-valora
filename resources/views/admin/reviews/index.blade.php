@extends('admin.layouts.main_nav')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Tổng hợp Đánh giá theo Sản phẩm</h4>
                </div>
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-center">
                    <div class="col-auto">
                        <input type="text" name="search" class="form-control" placeholder="Tìm tên sản phẩm..." value="{{ request('search') }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Sản phẩm</th>
                                <th class="text-center">Số lượng đánh giá</th>
                                <th class="text-center">Điểm trung bình</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    
                                    {{-- CỘT TÊN SẢN PHẨM (ĐÃ THÊM ẢNH & THƯƠNG HIỆU) --}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{-- Ảnh Sản phẩm --}}
                                            @if($product->image_main)
                                                <img src="{{ asset('storage/'.$product->image_main) }}" 
                                                    class="rounded border me-2" width="40" height="40" style="object-fit:cover;">
                                            @endif
                                            <div>
                                                <h6 class="mb-0 text-primary">{{ $product->name }}</h6>
                                                {{-- Tên Thương hiệu --}}
                                                @if(optional($product->brand)->name)
<small class="text-muted fst-italic">Thương hiệu: {{ $product->brand->name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-info fs-6">{{ $product->reviews_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="text-warning">
                                            <span class="fw-bold text-dark me-1">{{ number_format($product->reviews_avg_rating, 1) }}</span>
                                            <i class="bx bxs-star"></i>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.reviews.show', $product->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bx bx-show-alt"></i> Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Chưa có sản phẩm nào có đánh giá.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- PHÂN TRANG --}}
                <div class="mt-3 d-flex justify-content-end">
                    {{ $products->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection