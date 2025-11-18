@extends('admin.layouts.main_nav')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">
        <h4 class="mb-0">👁 Xem bài viết</h4>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">← Quay lại</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body px-5 py-4">

            <!-- ❌ Bỏ hiển thị thumbnail -->

            <!-- 🟩 Tiêu đề căn giữa, spacing đẹp -->
            <h2 class="fw-bold text-center mb-3" style="line-height: 1.4;">
                {{ $post->title }}
            </h2>

            <!-- 🟩 Thông tin tác giả căn giữa -->
            <p class="text-muted text-center mb-4">
                Tác giả: <b>{{ $post->author->name ?? 'N/A' }}</b> • 
                {{ optional($post->created_at)->format('d/m/Y H:i') ?? 'Không rõ ngày' }}

            </p>

            <hr class="my-4">

            <!-- 🟩 Nội dung -->
            <div class="content-body" style="font-size: 1.05rem; line-height: 1.75;">
                {!! $post->content !!}
            </div>

        </div>
    </div>

</div>
@endsection
