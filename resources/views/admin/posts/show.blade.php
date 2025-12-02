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

    {{-- PHẦN NỘI DUNG BÀI VIẾT --}}
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

    {{-- PHẦN HIỂN THỊ BÌNH LUẬN PHÂN CẤP --}}
    <div class="card shadow-sm border-0">
        <div class="card-body px-5 py-4">
            
            {{-- Lọc ra CHỈ các bình luận GỐC (parent_id = NULL) --}}
            @php
                $rootComments = $post->comments->whereNull('parent_id')->sortByDesc('created_at');
            @endphp
            
            <h4 class="mb-4">💬 Bình luận ({{ $post->comments_count ?? 0 }})</h4>
            
            @forelse($rootComments as $comment)
                <div class="border p-3 mb-4 rounded-3 bg-light">
                    
                    {{-- ========== HIỂN THỊ BÌNH LUẬN GỐC ========== --}}
                    <div class="d-flex align-items-center mb-2">
                        <i class="fa-solid fa-user-circle me-2 text-primary" style="font-size: 1.25rem;"></i>
                        <h6 class="mb-0 fw-bold me-2">{{ $comment->user->name ?? 'Khách' }}</h6>
                        <small class="text-muted">({{ $comment->created_at->diffForHumans() }})</small>
                    </div>
                    
                    <p class="mb-3">{{ $comment->content }}</p>
                    
                    {{-- Các nút hành động --}}
                    <div class="d-flex gap-2">
                        <a class="btn btn-sm btn-primary" data-bs-toggle="collapse" href="#replyForm-{{ $comment->id }}">
                            ✍️ Trả lời
                        </a>
                        
                        <form action="{{ route('admin.post_comments.delete', $comment) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa bình luận này? Tất cả phản hồi cũng sẽ bị xóa.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                🗑️ Xóa
                            </button>
                        </form>
                    </div>
                    
                    {{-- Form trả lời --}}
                    <form action="{{ route('admin.post_comments.reply', $comment) }}" method="POST" class="collapse mt-3" id="replyForm-{{ $comment->id }}">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="content" class="form-control" placeholder="Viết phản hồi..." required>
                            <button class="btn btn-success" type="submit">Gửi</button>
                        </div>
                    </form>
                    
                    {{-- ========== HIỂN THỊ CÁC REPLY CỦA COMMENT NÀY ========== --}}
                    @php
                        // Lọc các reply của comment này từ collection đã load
                        $replies = $post->comments->where('parent_id', $comment->id)->sortBy('created_at');
                    @endphp
                    
                    @if($replies->count() > 0)
                        <div class="mt-3 ps-4 border-start border-3 border-primary">
                            <h6 class="mb-2 text-primary">
                                <i class="fa-solid fa-reply me-1"></i> 
                                Phản hồi ({{ $replies->count() }}):
                            </h6>
                            
                            @foreach($replies as $reply)
                                <div class="p-3 mb-2 rounded-3" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                    
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-reply me-2 text-success" style="font-size: 1.1rem;"></i>
                                            <h6 class="mb-0 fw-bold me-2">{{ $reply->user->name ?? 'Khách' }}</h6>
                                            <small class="text-muted">({{ $reply->created_at->diffForHumans() }})</small>
                                        </div>
                                        
                                        {{-- Nút xóa reply --}}
                                        <form action="{{ route('admin.post_comments.delete', $reply) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa phản hồi này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <p class="mb-0">{{ $reply->content }}</p>
                                    
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                </div>
            @empty
                <div class="alert alert-info text-center">
                    <i class="fa-solid fa-comment-slash me-2"></i>
                    Bài viết này hiện chưa có bình luận nào.
                </div>
            @endforelse
            
        </div>
    </div>

</div>
@endsection