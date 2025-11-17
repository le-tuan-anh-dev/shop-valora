@extends('admin.layouts.main_nav')

@section('content')
<div class="page-content">
<div class="container-fluid py-4">

  <h4 class="mb-4">Danh sách bình luận</h4>

  {{-- ✅ Quản lý từ cấm --}}
  <div class="card mb-4">
    <div class="card-header bg-dark text-white">Quản lý từ ngữ cấm</div>
    <div class="card-body">
      <form action="{{ route('admin.comments.banned.add') }}" method="POST" class="d-flex mb-3">
        @csrf
        <input type="text" name="word" class="form-control me-2" placeholder="Nhập từ cần cấm..." required>
        <button type="submit" class="btn btn-primary">Thêm</button>
      </form>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">
          @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif

      <table class="table table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th>ID</th>
      <th>Từ cấm</th>
      <th>Ngày tạo</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    @forelse($bannedWords as $word)
      <tr>
        <td>{{ $word->id }}</td>

        {{-- ✅ Form sửa từ cấm --}}
        <td>
          <form action="{{ route('admin.comments.banned.update', $word->id) }}" method="POST" class="d-flex">
            @csrf
            <input type="text" name="word" value="{{ $word->word }}" class="form-control me-2" required>
            <button type="submit" class="btn btn-warning btn-sm">Sửa</button>
          </form>
        </td>

        <td>{{ $word->created_at->format('d/m/Y H:i') }}</td>
        <td>
          <form action="{{ route('admin.comments.banned.delete', $word->id) }}" method="POST" onsubmit="return confirm('Xóa từ này?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="4" class="text-center text-muted">Chưa có từ cấm nào.</td></tr>
    @endforelse
  </tbody>
</table>

    </div>
  </div>

  {{-- ✅ Danh sách bình luận --}}
  <table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Người dùng</th>
        <th>Nội dung</th>
        <th>Bài viết</th>
        <th>Ngày tạo</th>
        <th>Hành động</th>
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
        <td>
          <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này không?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="d-flex justify-content-center mt-3">
    {{ $comments->links() }}
  </div>
</div>
</div>
@endsection
