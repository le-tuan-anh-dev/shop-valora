@extends('admin.layouts.main_nav')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Tạo Bài Viết Mới</h4>

    <!-- Hiển thị lỗi validation -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Có lỗi xảy ra!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <!-- QUAN TRỌNG: enctype="multipart/form-data" để upload file -->
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Cột bên trái: Tiêu đề, Nội dung, Ảnh Gallery Mới -->
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung</label>
                            <!-- ✅ Đây là textarea mà CKEditor sẽ thay thế -->
                            <textarea class="form-control" id="content" name="content" rows="15">{{ old('content') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="gallery_images" class="form-label">Ảnh Gallery</label>
                            <input class="form-control" type="file" id="gallery_images" name="gallery_images[]" multiple>
                            <small class="form-text text-muted">Có thể chọn nhiều ảnh.</small>
                        </div>
                    </div>

                    <!-- Cột bên phải: Ảnh đại diện, Trạng thái -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Ảnh đại diện (Thumbnail)</label>
                            <input class="form-control" type="file" id="thumbnail" name="thumbnail">
                        </div>

                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">Đăng bài (Published)</label>
                        </div>

                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Tạo Bài Viết</button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<!-- ✅ SCRIPT ĐỂ KÍCH HOẠT CKEDITOR -->
@push('scripts')
    <!-- 1. Nạp thư viện CKEditor 5 từ CDN -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>


<script>
tinymce.init({
    selector: '#content',
    height: 500,
    plugins: 'image code link media table lists autolink preview',
    toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media | code preview',

    automatic_uploads: true,
    images_upload_url: '{{ route("admin.tinymce.upload") }}',

    file_picker_types: 'image',
    images_upload_credentials: true,

    file_picker_callback: function (callback) {
        let input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        input.onchange = function () {
            let file = this.files[0];
            let formData = new FormData();
            formData.append('file', file); // 🔥 TinyMCE key phải là 'file'
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("admin.tinymce.upload") }}', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.location) {
                        callback(data.location);
                    } else {
                        alert("Lỗi upload ảnh");
                    }
                });
        };

        input.click();
    }
});
</script>
@endpush