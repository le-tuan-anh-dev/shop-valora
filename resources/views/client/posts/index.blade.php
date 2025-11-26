@extends('client.layouts.master')

@section('title', 'Katie - Blog')

@section('content')

{{-- Phần Breadcrumb --}}
<section class="section-b-space pt-0">
    <div class="heading-banner">
        <div class="custom-container container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4>{{ request()->has('keyword') ? 'Tìm kiếm: ' . request()->keyword : 'Danh sách bài viết' }}</h4>
                </div>
                <div class="col-sm-6">
                    <ul class="breadcrumb float-end">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active"><a href="#">Blog</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Phần Nội dung chính --}}
<section class="section-b-space pt-0">
    <div class="custom-container container blog-page">
        <div class="row gy-4">

            {{-- Cột bên phải: Danh sách bài viết (Main Content) --}}
            <div class="col-xl-9 col-lg-8 order-lg-last">
                <div class="row gy-4">
                    @forelse ($posts as $post)
                        <div class="col-xl-4 col-sm-6">
                            <div class="blog-main-box">
                                <div class="blog-img">
                                    <a href="{{ route('posts.show', $post->id) }}">
                                        @if($post->thumbnail)
                                            <img class="img-fluid"
                                                src="{{ asset('storage/'.$post->thumbnail) }}"
                                                alt="{{ $post->title }}"
                                                style="width: 100%; height: 220px; object-fit: cover;">
                                        @else
                                            <img class="img-fluid"
                                                src="https://via.placeholder.com/350x220"
                                                alt="no-image"
                                                style="width: 100%; height: 220px; object-fit: cover;">
                                        @endif
                                    </a>
                                </div>
                                <div class="blog-content">
                                    <span class="blog-date">{{ $post->created_at->format('d/m/Y') }}</span>
                                    <a href="{{ route('posts.show', $post->id) }}">
                                        <h4 title="{{ $post->title }}">{{ Str::limit($post->title, 50) }}</h4>
                                    </a>
                                    <p>{{ Str::limit(strip_tags($post->content), 90) }}</p>
                                    <div class="share-box">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fa-regular fa-user"></i>
                                            <h6 class="mb-0">{{ $post->author->name ?? 'Admin' }}</h6>
                                        </div>
                                        <div class="d-flex align-items-center gap-3 ms-auto" style="font-size: 12px; color: #777;">
                                            <span><i class="fa-regular fa-eye"></i> {{ number_format($post->views) }}</span>
                                            {{-- Hiển thị số lượng Comment --}}
                                            {{-- Giả định model Post có quan hệ 'comments' --}}
                                            <span><i class="fa-regular fa-comment-dots"></i> {{ $post->comments_count ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                Không tìm thấy bài viết nào phù hợp với từ khóa "{{ request()->keyword }}".
                            </div>
                        </div>
                    @endforelse

                    {{-- Phân trang --}}
                    <div class="col-12">
                        <div class="pagination-wrap mt-0">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột bên trái: Sidebar --}}
            <div class="col-xl-3 col-lg-4 order-lg-first">
                <div class="blog-sidebar">

                    <div class="row gy-4">
                        {{-- 1. Search Form (Đã cập nhật action form) --}}
                        <div class="col-12">
                            {{-- Form gửi method GET về route index --}}
                            <form action="{{ route('posts.index') }}" method="GET">
                                <div class="blog-search">
                                    <input type="search"
                                            name="keyword"
                                            value="{{ request('keyword') }}"
                                            placeholder="Tìm kiếm bài viết...">
                                    <button type="submit" style="border:none; background:transparent; position:absolute; right:15px; top:50%; transform:translateY(-50%);">
                                        <i class="iconsax" data-icon="search-normal-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- 2. Bài viết nổi bật (Top Views) --}}
                        <div class="col-12">
                            <div class="sidebar-box">
                                <div class="sidebar-title">
                                    <div class="loader-line"></div>
                                    <h5>Nổi bật (Xem nhiều)</h5>
                                </div>
                                
                                {{-- Loop hiển thị Top Posts --}}
                                <ul class="recent-post">
                                    @foreach($topPosts as $topPost)
                                        <li class="d-flex align-items-center gap-3 mb-3">
                                            <div class="recent-img" style="width: 80px; height: 80px; flex-shrink: 0; border-radius: 5px; overflow: hidden;">
                                                <a href="{{ route('posts.show', $topPost->id) }}">
                                                    @if($topPost->thumbnail)
                                                         <img src="{{ asset('storage/'.$topPost->thumbnail) }}" 
                                                               class="img-fluid w-100 h-100" 
                                                               style="object-fit: cover;" 
                                                               alt="{{ $topPost->title }}">
                                                    @else
                                                         <img src="https://via.placeholder.com/80" class="img-fluid w-100 h-100" alt="thumb">
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="recent-content">
                                                <a href="{{ route('posts.show', $topPost->id) }}">
                                                    <h6 class="mb-1" style="font-size: 14px; line-height: 1.4;">
                                                        {{ Str::limit($topPost->title, 40) }}
                                                    </h6>
                                                </a>
                                                <span style="font-size: 12px; color: #888;">
                                                    <i class="fa-regular fa-eye"></i> {{ number_format($topPost->views) }} views
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        {{-- Bổ sung: 3. Bài viết mới nhất (Recent Posts) --}}
                        {{-- Hiển thị các bài viết khác từ biến $recentPosts --}}
                        <div class="col-12">
                            <div class="sidebar-box">
                                <div class="sidebar-title">
                                    <div class="loader-line"></div>
                                    <h5>Bài viết mới nhất</h5>
                                </div>
                                <ul class="recent-post">
                                    @foreach($recentPosts as $recentPost)
                                        <li class="d-flex align-items-center gap-3 mb-3">
                                            <div class="recent-img" style="width: 80px; height: 80px; flex-shrink: 0; border-radius: 5px; overflow: hidden;">
                                                <a href="{{ route('posts.show', $recentPost->id) }}">
                                                    @if($recentPost->thumbnail)
                                                         <img src="{{ asset('storage/'.$recentPost->thumbnail) }}" 
                                                               class="img-fluid w-100 h-100" 
                                                               style="object-fit: cover;" 
                                                               alt="{{ $recentPost->title }}">
                                                    @else
                                                         <img src="https://via.placeholder.com/80" class="img-fluid w-100 h-100" alt="thumb">
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="recent-content">
                                                <a href="{{ route('posts.show', $recentPost->id) }}">
                                                    <h6 class="mb-1" style="font-size: 14px; line-height: 1.4;">
                                                        {{ Str::limit($recentPost->title, 40) }}
                                                    </h6>
                                                </a>
                                                <span style="font-size: 12px; color: #888;">
                                                    {{ $recentPost->created_at->format('d/m/Y') }}
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>


                       

                        {{-- 5. Social (Giữ nguyên) --}}
                        <div class="col-12">
                            <div class="sidebar-box">
                                <div class="sidebar-title">
                                    <div class="loader-line"></div>
                                    <h5>Theo dõi</h5>
                                </div>
                                <ul class="social-icon">
                                    <li><a href="#"><div class="icon"><i class="fa-brands fa-facebook-f"></i></div><h6>Facebook</h6></a></li>
                                    <li><a href="#"><div class="icon"><i class="fa-brands fa-instagram"></i></div><h6>Instagram</h6></a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


@endsection

@push('styles')
<style>
    /* Custom styles for sidebar recent/top posts */
    .recent-post li:last-child { margin-bottom: 0 !important; }
    .recent-content h6:hover { color: var(--theme-color, #0da487); }
    
    .blog-img {
        height: 220px;
        overflow: hidden;
    }
    .pagination-wrap nav svg {
        width: 20px;
        height: 20px;
    }
    .pagination-wrap .pagination {
        justify-content: center;
    }
</style>
@endpush