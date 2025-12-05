@extends('admin.layouts.main_nav')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-sm-0">📝 Quản lý Bài Viết</h4>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Tạo Bài Viết
        </a>
    </div>

    <div class="card">
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- FORM LỌC --}}
            <form method="GET" class="row g-2 mb-4">

    <div class="col-md-3">
        <input name="title" value="{{ request('title') }}" class="form-control" placeholder="Tiêu đề">
    </div>

    <div class="col-md-2">
        <select name="status" class="form-control">
            <option value="">-- Trạng thái --</option>
            <option value="1" {{ request('status')=='1'?'selected':'' }}>Đã đăng</option>
            <option value="0" {{ request('status')=='0'?'selected':'' }}>Nháp</option>
        </select>
    </div>

    <div class="col-md-2">
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
    </div>

    <div class="col-md-2">
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
    </div>

    {{-- SORT --}}
    <div class="col-md-2">
        <select name="sort" class="form-control">
            <option value="">-- Sắp xếp --</option>

            

            <option value="likes_asc"  {{ request('sort')=='likes_asc'?'selected':'' }}>Likes ↑</option>
            <option value="likes_desc" {{ request('sort')=='likes_desc'?'selected':'' }}>Likes ↓</option>
        </select>
    </div>

    <div class="col-md-12 text-end mt-2">
        <button class="btn btn-primary">Lọc</button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Reset</a>
    </div>

</form>


            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Ảnh</th>
                            <th>Tiêu đề</th>
                            <th>Tác giả</th>
                          
                            <th>Likes</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>

                    <tbody>

                        @php $i = 1; @endphp

                        @forelse ($posts as $post)
                            <tr>
                                <td>{{ $i++ }}</td>

                                <td>
                                    @if($post->thumbnail)
                                        <img src="{{ asset('storage/' . $post->thumbnail) }}"
                                             style="width:60px;height:40px;object-fit:cover;"
                                             class="rounded">
                                    @else
                                        <span class="text-muted small">Không ảnh</span>
                                    @endif
                                </td>

                                <td>{{ $post->title }}</td>
                                <td>{{ $post->author->name ?? 'N/A' }}</td>
                                
                                <td>{{ number_format($post->likes) }}</td>

                                <td>{{ $post->created_at->format('d/m/Y') }}</td>

                                <td>
                                    <span class="badge bg-{{ $post->is_published ? 'success' : 'warning' }}">
                                        {{ $post->is_published ? 'Đã đăng' : 'Nháp' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="bx bx-show"></i>
                                    </a>

                                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm btn-info">
                                        <i class="bx bx-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.posts.destroy', $post->id) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Xóa bài viết này?')">

                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    Chưa có bài viết nào.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- PHÂN TRANG CURSOR --}}
            <div class="mt-3">
                {{ $posts->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
