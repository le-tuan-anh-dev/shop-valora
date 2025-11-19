@extends('admin.layouts.main_nav')
@section('title', 'Thêm người dùng')

@section('content')
<div class="page-content">
    <div class="container-xxl">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                    <h4 class="page-title">
                        <iconify-icon icon="solar:user-plus-bold-duotone" class="me-2"></iconify-icon>
                        Thêm người dùng mới
                    </h4>
                    <div class="page-title-right">
                        <a href="{{ route('admin.users.list') }}" class="btn btn-secondary">
                            <iconify-icon icon="solar:arrow-left-linear" class="me-1"></iconify-icon>
                            Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="me-2"></iconify-icon>
                        <strong>Có lỗi xảy ra!</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin người dùng</h5>
                    </div>
                    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Họ tên <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}" required>
                                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="password" 
                                               id="password-input"
                                               class="form-control @error('password') is-invalid @enderror" 
                                               placeholder="Nhập mật khẩu"
                                               required>
                                        <button type="button" 
                                                class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3" 
                                                id="toggle-password"
                                                style="border: none; background: none; cursor: pointer; z-index: 10;">
                                            <iconify-icon icon="solar:eye-bold-duotone" id="password-eye-icon"></iconify-icon>
                                        </button>
                                    </div>
                                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="password_confirmation" 
                                               id="password-confirmation-input"
                                               class="form-control"
                                               placeholder="Xác nhận mật khẩu"
                                               required>
                                        <button type="button" 
                                                class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3" 
                                                id="toggle-password-confirmation"
                                                style="border: none; background: none; cursor: pointer; z-index: 10;">
                                            <iconify-icon icon="solar:eye-bold-duotone" id="password-confirmation-eye-icon"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone') }}">
                                    @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Giới tính</label>
                                    <select name="gender" class="form-select">
                                        <option value="">-- Chọn --</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Ngày sinh</label>
                                    <input type="date" name="date_birth" class="form-control" value="{{ old('date_birth') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Địa chỉ</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Vai trò <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('role')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="locked" {{ old('status') == 'locked' ? 'selected' : '' }}>Khóa</option>
                                        <option value="banned" {{ old('status') == 'banned' ? 'selected' : '' }}>Cấm</option>
                                    </select>
                                    @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-semibold">Ảnh đại diện</label>
                                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                                           accept="image/*">
                                    @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.users.list') }}" class="btn btn-light">Hủy</a>
                            <button type="submit" class="btn btn-primary">
                                <iconify-icon icon="solar:check-circle-bold-duotone" class="me-1"></iconify-icon>
                                Thêm người dùng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('toggle-password')?.addEventListener('click', function() {
        const passwordInput = document.getElementById('password-input');
        const eyeIcon = document.getElementById('password-eye-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.setAttribute('icon', 'solar:eye-closed-bold-duotone');
        } else {
            passwordInput.type = 'password';
            eyeIcon.setAttribute('icon', 'solar:eye-bold-duotone');
        }
    });

    document.getElementById('toggle-password-confirmation')?.addEventListener('click', function() {
        const passwordConfirmationInput = document.getElementById('password-confirmation-input');
        const eyeIcon = document.getElementById('password-confirmation-eye-icon');
        
        if (passwordConfirmationInput.type === 'password') {
            passwordConfirmationInput.type = 'text';
            eyeIcon.setAttribute('icon', 'solar:eye-closed-bold-duotone');
        } else {
            passwordConfirmationInput.type = 'password';
            eyeIcon.setAttribute('icon', 'solar:eye-bold-duotone');
        }
    });
</script>
@endpush
@endsection

