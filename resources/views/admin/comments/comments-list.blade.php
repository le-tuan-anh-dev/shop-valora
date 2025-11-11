@extends('admin.layouts.main_nav')

@section('content')
<div class="page-content">
<div class="container-fluid py-4">
  <h4 class="mb-4">Danh sách bình luận</h4>

  <table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Người dùng</th>
        <th>Nội dung</th>
        <th>Bài viết</th>
        <th>Ngày tạo</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($comments as $comment)
      <tr>
        <td>{{ $comment->id }}</td>
        <td>
          @if($comment->user)
            <img src="{{ $comment->user->image }}" alt="" width="35" height="35" class="rounded-circle me-2">
            {{ $comment->user->name }}
          @else
            <em>Không xác định</em>
          @endif
        </td>
        <td>{{ $comment->content }}</td>
        <td>#{{ $comment->post_id }}</td>
        <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="d-flex justify-content-center mt-3">
    {{ $comments->links() }}
  </div>
</div>
@endsection
