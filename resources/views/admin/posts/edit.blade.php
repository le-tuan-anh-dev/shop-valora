@extends('admin.layouts.main_nav')

@section('content')
<div class="page-content">
<div class="container-fluid">
    <h4 class="mb-4"> Chỉnh sửa bài viết</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Có lỗi xảy ra!</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- LEFT -->
                    <div class="col-md-8">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tiêu đề</label>
                            <input type="text" class="form-control form-control-lg" name="title" value="{{ old('title', $post->title) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nội dung bài viết</label>
                            <textarea id="content" name="content" class="form-control" rows="18">{{ old('content', $post->content) }}</textarea>
                        </div>
                    </div>

                    <!-- RIGHT -->
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <label class="form-label fw-semibold">Ảnh đại diện</label>
                                <input type="file" name="thumbnail" class="form-control">
                                <small class="text-muted">Bỏ trống nếu không thay đổi.</small>

                                @if($post->thumbnail)
                                    <div class="mt-3 text-center">
                                        <img src="{{ asset('storage/' . $post->thumbnail) }}" class="img-fluid rounded shadow-sm" style="max-height: 180px; object-fit: cover;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <label class="form-check-label fw-semibold mb-2">Trạng thái bài viết</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                                    <label for="is_published" class="form-check-label">Đăng bài</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-primary px-4"> Lưu thay đổi</button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary px-4">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#content',
    height: 500,
    plugins: 'image link media table lists preview code autolink',
    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media | preview code',
    automatic_uploads: true,
    images_upload_url: '{{ route("admin.tinymce.upload") }}',
    file_picker_types: 'image',

    file_picker_callback: function (callback) {
        let input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';

        input.onchange = function () {
            let file = this.files[0];
            let form = new FormData();
            form.append('file', file);
            form.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("admin.tinymce.upload") }}', { method: 'POST', body: form })
                .then(r => r.json())
                .then(data => callback(data.location));
        };

        input.click();
    }
});
</script>
@endpush

@endsection
