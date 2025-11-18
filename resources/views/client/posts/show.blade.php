@extends('client.layouts.master')

@section('title', $post->title)

@section('content')

<div class="container py-4">

    <div class="card shadow-sm border-0">
        <div class="card-body px-4 py-4">

            <!-- 🟩 Tiêu đề căn giữa -->
            <h2 class="fw-bold text-center mb-3" style="line-height: 1.4;">
                {{ $post->title }}
            </h2>

            <!-- 🟩 Thông tin tác giả -->
            <p class="text-muted text-center mb-4">
                Tác giả: <b>{{ $post->author->name ?? 'Không rõ' }}</b> •
                {{ $post->created_at->format('d/m/Y H:i') }}
            </p>

            <!-- 🟩 Thumbnail -->
            @if($post->thumbnail)
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/'.$post->thumbnail) }}" 
                         class="img-fluid rounded" 
                         style="max-height: 350px; object-fit: cover;">
                </div>
            @endif

            <hr class="my-4">

            <!-- 🟩 Nội dung bài viết -->
            <div class="content-body" style="font-size: 1.1rem; line-height: 1.8;">
                {!! $post->content !!}
            </div>

        </div>
    </div>

</div>

@endsection
