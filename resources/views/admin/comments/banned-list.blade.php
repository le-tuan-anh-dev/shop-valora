@extends('admin.layouts.main_nav')

@section('content')
<div class="page-content">
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Danh sách bình luận</h4>

            <form method="GET" class="d-flex align-items-center">
                <select name="post_id" class="form-select me-2">
                    <option value="">-- Lọc theo bài viết --</option>
                    @foreach($posts as $post)
                        <option value="{{ $post->id }}" {{ request('post_id') == $post->id ? 'selected' : '' }}>
                            {{ $post->title }}
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-primary">Lọc</button>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                {{-- ... Phần đầu giữ nguyên ... --}}

{{-- Phần Table --}}
<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>#</th> {{-- Cursor không tính được STT tuyệt đối dễ dàng --}}
            <th>Người dùng</th>
            <th>Nội dung</th>
            <th>Bài viết</th>
            <th>Ngày tạo</th>
            <th width="80">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($comments as $comment)
        <tr>
            {{-- Chỉ hiển thị ID hoặc STT reset theo từng trang --}}
            <td>{{ $loop->iteration }}</td> 
            <td>
                @if($comment->user)
                    <img src="{{ $comment->user->image }}" width="35" height="35" class="rounded-circle me-2">
                    {{ $comment->user->name }}
                @else
                    <em>Không xác định</em>
                @endif
            </td>
            <td>{{ $comment->content }}</td>
            <td>{{ $comment->post->title ?? 'Không xác định' }}</td>
            <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
            <td>
                <form action="{{ route('admin.comments.destroy', $comment->id) }}"
                        method="POST"
                        onsubmit="return confirm('Xóa bình luận này?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm">Xóa</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted">Không có bình luận nào.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Phần nút bấm Next / Prev cho Cursor Pagination --}}
<div class="d-flex justify-content-between mt-3">
    {{-- Nút Previous --}}
    @if($comments->previousPageUrl())
        <a href="{{ $comments->previousPageUrl() }}" class="btn btn-primary">
            &laquo; Trước
        </a>
    @else
        <button class="btn btn-secondary" disabled>&laquo; Trước</button>
    @endif

    {{-- Nút Next --}}
    @if($comments->nextPageUrl())
        <a href="{{ $comments->nextPageUrl() }}" class="btn btn-primary">
            Sau &raquo;
        </a>
    @else
        <button class="btn btn-secondary" disabled>Sau &raquo;</button>
    @endif
</div>

            </div>
        </div>
    </div>
</div>
@endsection
