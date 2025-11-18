@extends('client.layouts.master')

@section('title', 'Katie - Blog')

@section('content')



<div class="row g-4">
    @foreach ($posts as $post)
        <div class="col-md-4">
            <a href="{{ route('posts.show', $post->id) }}" class="post-card">
                
                @if($post->thumbnail)
                <div class="thumb-wrapper">
                    <img src="{{ asset('storage/'.$post->thumbnail) }}" class="thumb-img" alt="">
                </div>
                @endif

                <div class="post-body">
                    <h3 class="post-title">{{ $post->title }}</h3>

                    <p class="post-meta">
                        <i class="bx bx-user"></i> {{ $post->author->name ?? 'N/A' }} • 
                        {{ $post->created_at->format('d/m/Y') }}
                    </p>

                    <div class="post-stats">
                        <span><i class="bx bx-show"></i> {{ number_format($post->views) }}</span>
                        <span><i class="bx bx-like"></i> {{ number_format($post->likes) }}</span>
                    </div>
                </div>

            </a>
        </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $posts->links() }}
</div>

@endsection

@push('styles')
<style>
    .page-title {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 25px;
    }

    .post-card {
        display: block;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #eee;
        text-decoration: none;
        transition: .25s;
        height: 100%;
    }

    .post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.07);
    }

    .thumb-wrapper {
        width: 100%;
        height: 180px;
        overflow: hidden;
    }

    .thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .post-body {
        padding: 15px 18px;
    }

    .post-title {
        font-size: 20px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .post-meta {
        color: #777;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .post-stats {
        font-size: 14px;
        display: flex;
        gap: 15px;
        color: #555;
    }

    .post-stats span i {
        margin-right: 5px;
    }
</style>
@endpush
