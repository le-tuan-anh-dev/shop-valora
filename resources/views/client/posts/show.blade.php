@extends('client.layouts.master')

@section('title', $post->title)

@section('content')

<div class="container py-4">

    {{-- =============================================== --}}
    {{-- 1. NỘI DUNG BÀI VIẾT CHÍNH                      --}}
    {{-- =============================================== --}}
    <div class="card shadow-sm border-0">
        <div class="card-body px-4 py-4">

            <h2 class="fw-bold text-center mb-3" style="line-height: 1.4;">
                {{ $post->title }}
            </h2>

            <p class="text-muted text-center mb-4">
                Tác giả: <b>{{ $post->author->name ?? 'Admin' }}</b> •
                {{ $post->created_at->format('d/m/Y H:i') }} •
                Lượt xem: <b>{{ number_format($post->views ?? 0) }}</b>
            </p>

            <hr class="my-4">

            {{-- Nội dung bài viết --}}
            <div class="content-body" style="font-size: 1.1rem; line-height: 1.8;">
                {!! $post->content !!}
            </div>

        </div>
    </div>

    {{-- =============================================== --}}
    {{-- 2. KHUNG BÌNH LUẬN (COMMENT SECTION)            --}}
    {{-- =============================================== --}}
    <div class="card shadow-sm border-0 mt-4" id="comment-section">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4">Bình luận ({{ $post->comments->count() }})</h4>

            {{-- A. HIỂN THỊ THÔNG BÁO (LỖI TỪ CẤM HOẶC THÀNH CÔNG) --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- B. FORM NHẬP BÌNH LUẬN --}}
            @auth
                <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-5">
                    @csrf
                    <div class="d-flex align-items-start gap-3">
                        {{-- Avatar User đang login --}}
                        <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random' }}" 
                             class="rounded-circle" 
                             style="width: 50px; height: 50px; object-fit: cover;" 
                             alt="My Avatar">
                        
                        <div class="w-100">
                            <textarea name="content" 
                                      class="form-control @error('content') is-invalid @enderror" 
                                      rows="3" 
                                      placeholder="Chia sẻ suy nghĩ của bạn (Vui lòng dùng từ ngữ lịch sự)...">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div class="mt-2 text-end">
                                <button type="submit" class="btn btn-primary px-4">Gửi bình luận</button>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <div class="alert alert-secondary text-center mb-5 py-3">
                    Bạn cần <a href="{{ route('login') }}" class="fw-bold text-decoration-underline">Đăng nhập</a> để tham gia bình luận.
                </div>
            @endauth

            {{-- C. DANH SÁCH BÌNH LUẬN --}}
            <div class="comment-list">
                @forelse ($post->comments as $comment)
                    <div class="d-flex gap-3 mb-4">
                        {{-- Avatar người comment --}}
                        <div class="flex-shrink-0">
                            <img src="{{ $comment->user->avatar ? asset('storage/'.$comment->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name ?? 'User').'&background=random' }}" 
                                 alt="{{ $comment->user->name }}" 
                                 class="rounded-circle" 
                                 style="width: 50px; height: 50px; object-fit: cover;">
                        </div>
                        
                        {{-- Nội dung comment --}}
                        <div class="flex-grow-1">
                            <div class="bg-light p-3 rounded-3 position-relative">
                                
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0">{{ $comment->user->name ?? 'Người dùng ẩn danh' }}</h6>
                                    <small class="text-muted" style="font-size: 0.85rem;">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </small>
                                </div>

                                <p class="mb-0 text-secondary" style="white-space: pre-line;">{{ $comment->content }}</p>

                                {{-- 🔥 NÚT XÓA: Chỉ hiện nếu User đang login là chủ comment --}}
                                @if(Auth::check() && Auth::id() == $comment->user_id)
                                    <div class="position-absolute top-0 end-0 mt-2 me-2">
                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" 
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa bình luận này không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-danger p-0 border-0" title="Xóa bình luận">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                {{-- Kết thúc nút xóa --}}

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <p class="text-muted fst-italic mb-0">Chưa có bình luận nào. Hãy là người đầu tiên!</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- =============================================== --}}
    {{-- 3. BÀI VIẾT LIÊN QUAN KHÁC                      --}}
    {{-- =============================================== --}}
    <div class="mt-5">
        <h3 class="fw-bold mb-4 border-bottom pb-2">Bài viết liên quan khác</h3>
        
        @if(isset($relatedPosts) && $relatedPosts->isNotEmpty())
            <div class="row g-4">
                @foreach ($relatedPosts as $rPost)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm blog-card">
                            <a href="{{ route('posts.show', $rPost->id) }}" style="overflow: hidden; height: 180px;">
                                @if($rPost->thumbnail)
                                    <img src="{{ asset('storage/'.$rPost->thumbnail) }}" 
                                         class="card-img-top w-100 h-100" 
                                         alt="{{ $rPost->title }}" 
                                         style="object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/400x180?text=No+Image" 
                                         class="card-img-top w-100 h-100" 
                                         alt="no-image" 
                                         style="object-fit: cover;">
                                @endif
                            </a>
                            <div class="card-body">
                                <a href="{{ route('posts.show', $rPost->id) }}" class="text-decoration-none">
                                    <h5 class="card-title fw-bold" style="font-size: 1.1rem; line-height: 1.4;">
                                        {{ Str::limit($rPost->title, 50) }}
                                    </h5>
                                </a>
                                <p class="card-text mt-3 text-muted" style="font-size: 0.9rem;">
                                    <i class="fa-regular fa-calendar-alt"></i> {{ $rPost->created_at->format('d/m/Y') }} 
                                    <span class="ms-3"><i class="fa-regular fa-eye"></i> {{ number_format($rPost->views) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="alert alert-info text-center">Hiện chưa có bài viết liên quan nào khác.</p>
        @endif
    </div>
    
</div>

@endsection

@push('styles')
<style>
    /* CSS định dạng ảnh trong bài viết */
    .content-body img {
        max-width: 100% !important;
        max-height: 400px !important;
        width: auto !important;
        height: auto !important;
        object-fit: contain !important;
        border-radius: 8px !important;
        margin: 15px auto !important;
        display: block !important;
    }
    
    /* Hiệu ứng hover cho Related Post */
    .blog-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #eee;
    }
    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }
    .blog-card img {
        transition: transform 0.5s;
    }
    .blog-card:hover img {
        transform: scale(1.05);
    }

    /* Button xóa comment */
    .btn-link:hover {
        text-decoration: underline;
    }
</style>
@endpush