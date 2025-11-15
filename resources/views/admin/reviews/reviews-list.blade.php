@extends('admin.layouts.main_nav')

@section('content')
<div class="page-content">

    <div class="container-xxl">
        <h4 class="mb-4">Danh sách đánh giá</h4>

      
        <form method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <select name="rating" class="form-select">
                        <option value="">-- Lọc theo số sao --</option>
                        @for($i=1;$i<=5;$i++)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected':'' }}>
                                {{ $i }} sao
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Lọc</button>
                </div>
            </div>
        </form>

        <div class="row">
            @foreach ($reviews as $review)
                <div class="col-xl-3 col-md-6">
                    <div class="card overflow-hidden">

                        <div class="card-body">

                            <p class="mb-2 text-dark fw-semibold fs-15">
    Sản phẩm: <strong>{{ $review->product->name }}</strong><br>
   Đánh giá vào {{ $review->created_at->translatedFormat('d F Y') }}

</p>


                            <p class="mb-0">"{{ $review->content }}"</p>

                            {{-- IMAGES --}}
                            @if($review->images->count())
                                <div class="mt-3 d-flex flex-wrap gap-2">
                                    @foreach ($review->images as $img)
                                        <img src="{{ asset('storage/'.$img->image_path) }}" class="rounded"
                                             width="120" height="120" style="object-fit:cover;">
                                    @endforeach
                                </div>
                            @endif

                            {{-- STAR --}}
                            <div class="d-flex align-items-center gap-2 mt-3 mb-1">
                                <ul class="d-flex text-warning m-0 fs-20 list-unstyled">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <li>
                                            @if($i <= $review->rating)
                                                <i class="bx bxs-star"></i>
                                            @else
                                                <i class="bx bx-star"></i>
                                            @endif
                                        </li>
                                    @endfor
                                </ul>
                            </div>

                            {{-- Replies --}}
                            @if($review->replies->count())
                                <div class="mt-2 ps-2 border-start border-3 border-primary">
                                    @foreach($review->replies as $reply)
                                       <p class="mb-1">
    <strong>Admin:</strong> {{ $reply->content }}

    
    <button class="btn btn-warning btn-sm py-0 px-1"
            data-bs-toggle="modal"
            data-bs-target="#editModal{{ $reply->id }}">
        Sửa
    </button>

  
    <form method="POST" action="{{ route('admin.reviews.destroy', $reply->id) }}" class="d-inline">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger btn-sm py-0 px-1"
                onclick="return confirm('Bạn có chắc muốn xóa phản hồi này?');">
            Xóa
        </button>
    </form>

  
    <div class="modal fade" id="editModal{{ $reply->id }}">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.reviews.update', $reply->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Sửa phản hồi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="content" class="form-control">{{ $reply->content }}</textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Cập nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</p>

                                    @endforeach
                                </div>
                            @endif

                        </div>

                      
                        <div class="card-footer bg-primary position-relative mt-3">

                            <div class="mt-4">
                                <h4 class="text-white mb-1">{{ $review->user->name }}</h4>
                                
                            </div>

                           
                            <div class="mt-3 d-flex flex-column gap-2">

                               
                                <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Xóa bình luận</button>
                                </form>

                                

                            
                                <form method="POST" action="{{ route('admin.reviews.store') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $review->product_id }}">
                                    <input type="hidden" name="parent_id" value="{{ $review->id }}">
                                    <div class="input-group mt-2">
                                        <input type="text" name="content" class="form-control form-control-sm"
                                               placeholder="Viết phản hồi...">
                                        <button class="btn btn-success btn-sm">Gửi</button>
                                    </div>
                                </form>

                            </div>

                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $reviews->links() }}
        </div>

    </div>

</div>
@endsection
