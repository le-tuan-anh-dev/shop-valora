@extends('admin.layouts.main_nav')
@section('title', 'Chỉnh sửa người dùng')

@section('content')
<div class="page-content">
    <div class="container-xxl">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                    <h4 class="page-title">
                        <iconify-icon icon="solar:user-pen-bold-duotone" class="me-2"></iconify-icon>
                        Chỉnh sửa người dùng
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
            @if($user->role === 'customer')
            <div class="col-lg-8">
                @if($orderStats)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <iconify-icon icon="solar:chart-2-bold-duotone" class="me-2"></iconify-icon>
                            Thống kê đơn hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-6">
                                <div class="card border border-primary">
                                    <div class="card-body text-center">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <iconify-icon icon="solar:bag-heart-bold-duotone" class="fs-2 text-primary"></iconify-icon>
                                        </div>
                                        <h3 class="mb-1">{{ $orderStats['total_orders'] }}</h3>
                                        <p class="text-muted mb-0 small">Tổng đơn hàng</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="card border border-success">
                                    <div class="card-body text-center">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <iconify-icon icon="solar:wallet-money-bold-duotone" class="fs-2 text-success"></iconify-icon>
                                        </div>
                                        <h3 class="mb-1">{{ number_format($orderStats['total_spent'], 0, ',', '.') }}₫</h3>
                                        <p class="text-muted mb-0 small">Tổng đã chi</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="card border border-info">
                                    <div class="card-body text-center">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-2 text-info"></iconify-icon>
                                        </div>
                                        <h3 class="mb-1">{{ $orderStats['completed_orders'] }}</h3>
                                        <p class="text-muted mb-0 small">Đơn hoàn thành</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="card border border-warning">
                                    <div class="card-body text-center">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-2 text-warning"></iconify-icon>
                                        </div>
                                        <h3 class="mb-1">{{ $orderStats['pending_orders'] }}</h3>
                                        <p class="text-muted mb-0 small">Đơn chờ xử lý</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="card border border-danger">
                                    <div class="card-body text-center">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <iconify-icon icon="solar:close-circle-bold-duotone" class="fs-2 text-danger"></iconify-icon>
                                        </div>
                                        <h3 class="mb-1">{{ $orderStats['cancelled_orders'] }}</h3>
                                        <p class="text-muted mb-0 small">Đơn đã hủy</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="card border border-success">
                                    <div class="card-body text-center">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <iconify-icon icon="solar:card-bold-duotone" class="fs-2 text-success"></iconify-icon>
                                        </div>
                                        <h3 class="mb-1">{{ $orderStats['paid_orders'] }}</h3>
                                        <p class="text-muted mb-0 small">Đơn đã thanh toán</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="card border border-secondary">
                                    <div class="card-body text-center">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <iconify-icon icon="solar:card-send-bold-duotone" class="fs-2 text-secondary"></iconify-icon>
                                        </div>
                                        <h3 class="mb-1">{{ $orderStats['unpaid_orders'] }}</h3>
                                        <p class="text-muted mb-0 small">Đơn chưa thanh toán</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($user->role === 'customer' && $orders->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <iconify-icon icon="solar:bag-4-bold-duotone" class="me-2"></iconify-icon>
                            Danh sách đơn hàng ({{ $orders->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Tổng tiền</th>
                                        <th>Thanh toán</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">#{{ $order->order_number }}</span>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="fw-semibold">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                                        </td>
                                        <td>
                                            @php
                                                $paymentStatusColors = [
                                                    'unpaid' => ['bg' => 'bg-light', 'text' => 'text-dark', 'label' => 'Chưa thanh toán'],
                                                    'paid' => ['bg' => 'bg-success', 'text' => 'text-light', 'label' => 'Đã thanh toán'],
                                                    'failed' => ['bg' => 'bg-danger', 'text' => 'text-light', 'label' => 'Thanh toán thất bại']
                                                ];
                                                $paymentStatus = $paymentStatusColors[$order->payment_status] ?? ['bg' => 'bg-light', 'text' => 'text-dark', 'label' => ucfirst($order->payment_status)];
                                            @endphp
                                            <span class="badge {{ $paymentStatus['bg'] }} {{ $paymentStatus['text'] }} px-2 py-1 fs-13">
                                                {{ $paymentStatus['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => ['border' => 'border-warning', 'text' => 'text-warning', 'label' => 'Chờ xử lý'],
                                                    'confirmed' => ['border' => 'border-info', 'text' => 'text-info', 'label' => 'Đã xác nhận'],
                                                    'awaiting_pickup' => ['border' => 'border-primary', 'text' => 'text-primary', 'label' => 'Chờ lấy hàng'],
                                                    'shipping' => ['border' => 'border-info', 'text' => 'text-info', 'label' => 'Đang giao'],
                                                    'delivered' => ['border' => 'border-success', 'text' => 'text-success', 'label' => 'Đã giao hàng'],
                                                    'completed' => ['border' => 'border-success', 'text' => 'text-success', 'label' => 'Hoàn thành'],
                                                    'cancelled_by_customer' => ['border' => 'border-danger', 'text' => 'text-danger', 'label' => 'Khách hủy'],
                                                    'cancelled_by_admin' => ['border' => 'border-danger', 'text' => 'text-danger', 'label' => 'Admin hủy'],
                                                    'delivery_failed' => ['border' => 'border-danger', 'text' => 'text-danger', 'label' => 'Giao thất bại']
                                                ];
                                                $status = $statusColors[$order->status] ?? ['border' => 'border-secondary', 'text' => 'text-secondary', 'label' => ucfirst($order->status)];
                                            @endphp
                                            <span class="badge border {{ $status['border'] }} {{ $status['text'] }} px-2 py-1 fs-13">
                                                {{ $status['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="btn btn-sm btn-light" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-title="Xem chi tiết">
                                                <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                @else
            <div class="col-lg-12">
                @endif
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin người dùng</h5>
                    </div>
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-4 text-center">
                                    <label class="form-label fw-semibold d-block mb-3">Ảnh đại diện</label>
                                    <div class="position-relative d-inline-block">
                                        @if($user->image)
                                            <img src="{{ asset('storage/' . $user->image) }}" 
                                                 alt="Avatar" 
                                                 id="avatar-preview"
                                                 class="rounded-circle border border-3 border-primary shadow-sm"
                                                 style="width: 150px; height: 150px; object-fit: cover; cursor: pointer;">
                                        @else
                                            <div id="avatar-preview" 
                                                 class="rounded-circle border border-3 border-secondary bg-light d-flex align-items-center justify-content-center shadow-sm"
                                                 style="width: 150px; height: 150px; cursor: pointer;">
                                                <iconify-icon icon="solar:user-bold-duotone" class="fs-1 text-muted"></iconify-icon>
                                            </div>
                                        @endif
                                        <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 border border-3 border-white shadow">
                                            <iconify-icon icon="solar:camera-bold-duotone" class="text-white fs-5"></iconify-icon>
                                        </div>
                                    </div>
                                    <input type="file" 
                                           name="image" 
                                           id="avatar-input"
                                           class="form-control mt-3 d-none" 
                                           accept="image/*"
                                           onchange="previewAvatar(this)">
                                    <input type="hidden" name="remove_image" id="remove-image-flag" value="0">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-primary mt-2"
                                            onclick="document.getElementById('avatar-input').click()">
                                        <iconify-icon icon="solar:upload-bold-duotone" class="me-1"></iconify-icon>
                                        Chọn ảnh
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger mt-2"
                                            id="remove-avatar-btn"
                                            onclick="removeAvatar()"
                                            style="{{ !$user->image ? 'display: none;' : '' }}">
                                        <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="me-1"></iconify-icon>
                                        Xóa ảnh
                                    </button>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Họ tên <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $user->name) }}" required>
                                    @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Mật khẩu mới</label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="password" 
                                               id="password-input"
                                               class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Nhập mật khẩu mới">
                                        <button type="button" 
                                                class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3" 
                                                id="toggle-password"
                                                style="border: none; background: none; cursor: pointer; z-index: 10;">
                                            <iconify-icon icon="solar:eye-bold-duotone" id="password-eye-icon"></iconify-icon>
                                        </button>
                                    </div>
                                    <small class="text-muted">Để trống nếu không muốn đổi mật khẩu</small>
                                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Xác nhận mật khẩu</label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="password_confirmation" 
                                               id="password-confirmation-input"
                                               class="form-control"
                                               placeholder="Xác nhận mật khẩu">
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
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Giới tính</label>
                                    <select name="gender" class="form-select">
                                        <option value="">-- Chọn --</option>
                                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Ngày sinh</label>
                                    <input type="date" name="date_birth" class="form-control" 
                                           value="{{ old('date_birth', $user->date_birth ? $user->date_birth->format('Y-m-d') : '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Địa chỉ</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Vai trò <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select" required>
                                        <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select" required>
                                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="locked" {{ old('status', $user->status) == 'locked' ? 'selected' : '' }}>Khóa</option>
                                        <option value="banned" {{ old('status', $user->status) == 'banned' ? 'selected' : '' }}>Cấm</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Cấm đến ngày</label>
                                    <input type="datetime-local" name="banned_until" class="form-control" 
                                           value="{{ old('banned_until', $user->banned_until ? $user->banned_until->format('Y-m-d\TH:i') : '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.users.list') }}" class="btn btn-light">Hủy</a>
                            <button type="submit" class="btn btn-primary">
                                <iconify-icon icon="solar:check-circle-bold-duotone" class="me-1"></iconify-icon>
                                Cập nhật
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
    // Preview avatar khi chọn file
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Nếu là div placeholder, thay thế bằng img
                    const img = document.createElement('img');
                    img.id = 'avatar-preview';
                    img.src = e.target.result;
                    img.alt = 'Avatar';
                    img.className = 'rounded-circle border border-3 border-primary shadow-sm';
                    img.style.cssText = 'width: 150px; height: 150px; object-fit: cover; cursor: pointer;';
                    preview.parentNode.replaceChild(img, preview);
                }
                document.getElementById('remove-avatar-btn').style.display = 'inline-block';
                document.getElementById('remove-image-flag').value = '0';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Xóa avatar
    function removeAvatar() {
        const preview = document.getElementById('avatar-preview');
        const input = document.getElementById('avatar-input');
        const removeFlag = document.getElementById('remove-image-flag');
        
        // Reset input
        input.value = '';
        removeFlag.value = '1';
        
        // Thay thế bằng placeholder
        const placeholder = document.createElement('div');
        placeholder.id = 'avatar-preview';
        placeholder.className = 'rounded-circle border border-3 border-secondary bg-light d-flex align-items-center justify-content-center shadow-sm';
        placeholder.style.cssText = 'width: 150px; height: 150px; cursor: pointer;';
        placeholder.innerHTML = '<iconify-icon icon="solar:user-bold-duotone" class="fs-1 text-muted"></iconify-icon>';
        preview.parentNode.replaceChild(placeholder, preview);
        
        document.getElementById('remove-avatar-btn').style.display = 'none';
    }

    // Click vào avatar để chọn file
    document.addEventListener('DOMContentLoaded', function() {
        const avatarPreview = document.getElementById('avatar-preview');
        if (avatarPreview) {
            avatarPreview.addEventListener('click', function() {
                document.getElementById('avatar-input').click();
            });
        }
    });

    // Toggle password visibility
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

    // Toggle password confirmation visibility
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

