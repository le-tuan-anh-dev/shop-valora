@extends('admin.layouts.main_nav')

@section('content')

<div class="container-fluid">

<div class="d-flex justify-content-between mb-4">
    <h4 class="mb-0">👁 Xem bài viết chi tiết</h4>
    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">← Quay lại danh sách</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- ============================================== --}}
{{-- PHẦN NỘI DUNG BÀI VIẾT --}}
{{-- ============================================== --}}
<div class="card shadow-sm border-0 mb-5">
    <div class="card-body px-5 py-4">

        <h2 class="fw-bold text-center mb-3" style="line-height: 1.4;">
            {{ $post->title }}
        </h2>

        <p class="text-muted text-center mb-4">
            Tác giả: <b>{{ $post->author->name ?? 'N/A' }}</b> • 
            {{ optional($post->created_at)->format('d/m/Y H:i') ?? 'Không rõ ngày' }}
        </p>

        <hr class="my-4">

        <div class="content-body" style="font-size: 1.05rem; line-height: 1.75;">
            {!! $post->content !!}
        </div>

    </div>
</div>

{{-- ============================================== --}}
{{-- PHẦN QUẢN LÝ BÌNH LUẬN CỦA ADMIN --}}
{{-- ============================================== --}}
<div class="card shadow-sm border-0">
    <div class="card-body px-5 py-4">
        
        {{-- Lọc ra chỉ các bình luận gốc (parent_id là NULL) để bắt đầu vòng lặp --}}
        @php
            // Lọc bình luận gốc từ collection đã được eager load, sắp xếp giảm dần theo thời gian tạo
            $rootComments = $post->comments->whereNull('parent_id')->sortByDesc('created_at');
        @endphp
        
        {{-- Dùng comments_count (tổng tất cả) để hiển thị --}}
        <h4 class="mb-4">💬 Bình luận ({{ $post->comments_count ?? 0 }})</h4>
        
        @forelse($rootComments as $comment)
            <div class="border p-3 mb-4 rounded-3 bg-light">
                
                {{-- BẮT ĐẦU HIỂN THỊ BÌNH LUẬN GỐC --}}
                <div class="d-flex align-items-center mb-2">
                    <i class="fa-solid fa-user-circle me-2 text-primary" style="font-size: 1.25rem;"></i>
                    <h6 class="mb-0 fw-bold me-2">{{ $comment->user->name ?? 'Khách' }}</h6>
                    <small class="text-muted">({{ optional($comment->created_at)->diffForHumans() }})</small>
                </div>
                
                <p class="mb-3">{{ $comment->content }}</p>
                {{-- KẾT THÚC HIỂN THỊ BÌNH LUẬN GỐC --}}
                
                
                <a class="btn btn-sm btn-primary" data-bs-toggle="collapse" href="#replyForm-{{ $comment->id }}" role="button" aria-expanded="false" aria-controls="replyForm-{{ $comment->id }}">
                    ✍️ Trả lời
                </a>
                
                {{-- Form trả lời cho Admin --}}
                <form action="{{ route('admin.post_comments.reply', $comment) }}" method="POST" class="collapse mt-3" id="replyForm-{{ $comment->id }}">
                     @csrf
                     <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                     <div class="input-group">
                         <input type="text" name="content" class="form-control" placeholder="Viết phản hồi...">
                         <button class="btn btn-success" type="submit">Gửi</button>
                     </div>
                </form>
                
                
                @if($comment->replies->count())
                    <div class="mt-3 ps-4 border-start border-3 border-primary">
                        <h6 class="mb-2 text-primary">Phản hồi:</h6>
                        {{-- Lặp qua replies đã được eager load, sắp xếp theo thời gian tạo TĂNG DẦN --}}
                        @foreach($comment->replies->sortBy('created_at') as $reply)
                            <div class="p-3 mb-2 rounded-3" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                {{-- BẮT ĐẦU HIỂN THỊ REPLY --}}
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa-solid fa-reply me-2 text-success" style="font-size: 1.1rem;"></i>
                                    <h6 class="mb-0 fw-bold me-2">{{ $reply->user->name ?? 'Khách' }}</h6>
                                    <small class="text-muted">({{ optional($reply->created_at)->diffForHumans() }})</small>
                                </div>
                                <p class="mb-0">{{ $reply->content }}</p>
                                {{-- KẾT THÚC HIỂN THỊ REPLY --}}
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            {{-- Chỉ hiện khi KHÔNG có bình luận gốc nào --}}
            <p class="alert alert-info text-center">
                Bài viết này hiện chưa có **bình luận gốc** nào.
                (Tổng số bình luận: **{{ $post->comments_count ?? 0 }}**)
            </p>
        @endforelse
        
    </div>
</div>


</div>
@endsection