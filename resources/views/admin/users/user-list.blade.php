@extends('admin.layouts.main_nav')
@section('title', 'Danh sách người dùng')

@section('content')
<div class="page-content">
    <div class="container-xxl">

        {{-- Header Section --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                    <h4 class="page-title">Quản lý người dùng</h4>
                    <div class="page-title-right">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <iconify-icon icon="solar:add-circle-bold-duotone" class="me-1"></iconify-icon>
                            Thêm người dùng
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <iconify-icon icon="solar:check-circle-bold-duotone" class="me-2"></iconify-icon>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <iconify-icon icon="solar:close-circle-bold-duotone" class="me-2"></iconify-icon>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Filter Section --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.users.list') }}" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Tìm kiếm theo tên, email, số điện thoại..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="role" class="form-select">
                                    <option value="">Tất cả vai trò</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>Khóa</option>
                                    <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Cấm</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                                    Tìm kiếm
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Danh sách người dùng</h5>
                        <div class="card-toolbar">
                            <span class="badge bg-light-primary text-primary">
                                Tổng: {{ $users->total() }}
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Thông tin</th>
                                    <th>Liên hệ</th>
                                    <th style="width: 100px;">Vai trò</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 160px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">#{{ $user->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($user->image)
                                                    <img src="{{ asset('storage/' . $user->image) }}" 
                                                         alt="{{ $user->name }}" 
                                                         class="rounded-circle me-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="avatar-sm me-2">
                                                        <div class="avatar-title bg-light-primary text-primary rounded-circle">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">{{ $user->name }}</h6>
                                                    @if($user->date_birth)
                                                        <small class="text-muted">{{ $user->date_birth->format('d/m/Y') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="mb-1">
                                                    <iconify-icon icon="solar:letter-bold-duotone" class="me-1"></iconify-icon>
                                                    {{ $user->email }}
                                                </div>
                                                @if($user->phone)
                                                    <div>
                                                        <iconify-icon icon="solar:phone-calling-bold-duotone" class="me-1"></iconify-icon>
                                                        {{ $user->phone }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($user->role == 'admin')
                                                <span class="badge bg-light-danger text-danger">
                                                    <iconify-icon icon="solar:shield-user-bold-duotone" class="me-1"></iconify-icon>
                                                    Admin
                                                </span>
                                            @else
                                                <span class="badge bg-light-info text-info">
                                                    <iconify-icon icon="solar:user-bold-duotone" class="me-1"></iconify-icon>
                                                    Khách hàng
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->status == 'active')
                                                <span class="badge bg-light-success text-success">
                                                    <iconify-icon icon="solar:check-circle-bold-duotone" class="me-1"></iconify-icon>
                                                    Hoạt động
                                                </span>
                                            @elseif($user->status == 'locked')
                                                <span class="badge bg-light-warning text-warning">
                                                    <iconify-icon icon="solar:lock-bold-duotone" class="me-1"></iconify-icon>
                                                    Khóa
                                                </span>
                                            @else
                                                <span class="badge bg-light-danger text-danger">
                                                    <iconify-icon icon="solar:ban-bold-duotone" class="me-1"></iconify-icon>
                                                    Cấm
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                                   class="btn btn-sm btn-icon btn-light" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-title="Chỉnh sửa">
                                                    <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon>
                                                </a>
                                                
                                                @if($user->id !== auth()->id())
                                                    @if($user->status === 'locked')
                                                        <form action="{{ route('admin.users.toggleLock', $user->id) }}" 
                                                              method="POST" 
                                                              class="d-inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-icon btn-light" 
                                                                    data-bs-toggle="tooltip" 
                                                                    data-bs-title="Mở khóa tài khoản">
                                                                <iconify-icon icon="solar:lock-password-unlocked-bold-duotone" class="text-success"></iconify-icon>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('admin.users.toggleLock', $user->id) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Bạn chắc chắn muốn khóa tài khoản này?')">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-icon btn-light" 
                                                                    data-bs-toggle="tooltip" 
                                                                    data-bs-title="Khóa tài khoản">
                                                                <iconify-icon icon="solar:lock-password-bold-duotone" class="text-warning"></iconify-icon>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                                          method="POST" 
                                                          class="d-inline" 
                                                          onsubmit="return confirm('Bạn chắc chắn muốn xóa người dùng này? Hành động này không thể hoàn tác!')">
                                                        @csrf 
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-icon btn-light" 
                                                                data-bs-toggle="tooltip" 
                                                                data-bs-title="Xóa">
                                                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="text-danger"></iconify-icon>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted small">Tài khoản của bạn</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <iconify-icon icon="solar:inbox-bold-duotone" style="font-size: 3rem; opacity: 0.5;"></iconify-icon>
                                                <p class="mt-3 mb-0">Chưa có người dùng nào</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($users->hasPages())
                        <div class="card-footer border-top">
                            <nav aria-label="Page navigation">
                                {{ $users->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

