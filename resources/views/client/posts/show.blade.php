@extends('client.layouts.master')

@section('title', $post->title)

@section('styles')
<style>
    .content-body img {
        max-width: 100%;
        height: auto;
        display: block;
        margin-left: auto;
        margin-right: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .content-body iframe {
        max-width: 100%;
        height: auto;
    }
</style>
@endsection

@section('content')

<div class="container py-4">
    <div class="row">

        {{-- ============================ --}}
        {{-- LEFT SIDE: CONTENT --}}
        {{-- ============================ --}}
        <div class="col-lg-8">

            {{-- SUCCESS / ERROR --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- MAIN CONTENT --}}
            <div class="card shadow-sm border-0 mb-5">
                <div class="card-body px-5 py-4">

                    <h2 class="fw-bold text-center mb-3">{{ $post->title }}</h2>

                    <p class="text-muted text-center mb-4">
                        Tác giả: <b>{{ $post->author->name ?? 'N/A' }}</b> •
                        {{ $post->created_at->format('d/m/Y H:i') }}
                        
                        <span class="ms-3"><i class="fa-solid fa-heart me-1 text-danger"></i> {{ number_format($post->likes) }}</span>
                    </p>

                    <hr>

                    <div class="content-body" style="font-size: 1.05rem; line-height: 1.75;">
                        {!! $post->content !!}
                    </div>

                </div>
            </div>

            {{-- COMMENT SECTION --}}
            <div class="card shadow-sm border-0 mb-5">
                <div class="card-body px-5 py-4">

                    @php
                        $rootComments = $post->comments->whereNull('parent_id')->sortByDesc('created_at');
                    @endphp

                    <h4 class="mb-4">💬 Bình luận ({{ $post->comments_count }})</h4>

                    @auth
    <div class="mb-4 p-3 border rounded bg-light">
        <h6 class="fw-bold mb-3">Bạn đang bình luận với tên: {{ Auth::user()->name }}</h6>
        {{-- SỬA LỖI TẠI ĐÂY: Thêm thẻ <form> và sử dụng route 'comments.store' --}}
        <form action="{{ route('comments.store', ['id' => $post->id]) }}" method="POST"> 
            @csrf
            <textarea name="content" class="form-control mb-3 @error('content') is-invalid @enderror" rows="3" placeholder="Viết bình luận của bạn...">{{ old('content') }}</textarea>
            @error('content')
                <div class="invalid-feedback mb-2">{{ $message }}</div>
            @enderror
            <button type="submit" class="btn btn-primary">Gửi bình luận</button>
        </form>
    </div>
@else
                        <div class="alert alert-warning text-center mb-4">
                            Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận.
                        </div>
                    @endauth

                    <hr>

                    @forelse($rootComments as $comment)
                        <div class="border p-3 mb-4 rounded bg-light">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa-solid fa-user-circle me-2 text-primary"></i>
                                <h6 class="mb-0 fw-bold me-2">{{ $comment->user->name ?? 'Khách' }}</h6>
                                <small class="text-muted">({{ $comment->created_at->diffForHumans() }})</small>

                                @auth
                                    @if(Auth::id() === $comment->user_id)
                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="ms-auto">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa bình luận này không?')"><i class="fa-solid fa-trash"></i> Xóa</button>
        </form>
                                    @endif
                                @endauth
                            </div>

                            <p>{{ $comment->content }}</p>

                            {{-- Replies --}}
                            @if($comment->replies->count())
                                <div class="mt-3 ps-4 border-start border-3 border-primary">
                                    @foreach($comment->replies as $reply)
                                        <div class="p-3 mb-2 rounded bg-white border">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fa-solid fa-reply me-2 text-success"></i>
                                                <h6 class="mb-0 fw-bold me-2">{{ $reply->user->name }} (Phản hồi)</h6>
                                                <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p>{{ $reply->content }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    @empty
                        <p class="alert alert-info text-center">Chưa có bình luận nào.</p>
                    @endforelse

                </div>
            </div>

        </div>

        {{-- ============================ --}}
        {{-- RIGHT SIDE: SIDEBAR --}}
        {{-- ============================ --}}
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 20px;">
                @if($topPosts->count())
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">⭐ Bài viết nổi bật</h5>
                        <ul class="list-unstyled m-0 p-0">
                            @foreach($topPosts as $topPost)
                                <li class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex">
                                        <img src="{{ asset('storage/' . $topPost->image) }}" class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-1 fw-bold">
                                                <a href="{{ route('posts.show', $topPost->id) }}" class="text-dark text-decoration-none">
                                                    {{ Str::limit($topPost->title, 45) }}
                                                </a>
                                            </h6>
                                            <small class="text-muted"><i class="fa-solid fa-heart me-1 text-danger"></i>{{ number_format($topPost->likes) }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- ================================================= --}}
{{-- RELATED POSTS — TÁCH RA KHỎI LAYOUT & ĐẢM BẢO LUÔN CUỐI --}}
{{-- ================================================= --}}
@if ($relatedPosts->count())
<div class="container mb-5">
    <h4 class="fw-bold mb-4">🔥 Bài viết liên quan</h4>

    <div class="row">
        @foreach($relatedPosts as $relatedPost)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('storage/' . $relatedPost->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-bold">
                            <a href="{{ route('posts.show', $relatedPost->id) }}" class="text-dark text-decoration-none">
                                {{ Str::limit($relatedPost->title, 50) }}
                            </a>
                        </h6>
                        <small class="text-muted mt-auto">
                            <i class="fa-solid fa-clock me-1"></i>
                            {{ $relatedPost->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

@endsection
