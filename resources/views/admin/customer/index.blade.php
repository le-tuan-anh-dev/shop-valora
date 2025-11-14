@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Danh sách khách hàng</h2>

    <!-- Form tìm kiếm -->
    <form action="{{ route('admin.customer.index') }}" method="GET" class="mb-3 d-flex" style="gap:10px;">
        <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Tìm tên, email hoặc số điện thoại...">
        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">Reset</a>
    </form>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.customer.create') }}" class="btn btn-success mb-3">+ Thêm khách hàng</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $customer)
            <tr>
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->address }}</td>
                <td>
                    <a href="{{ route('admin.customer.edit', $customer) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('admin.customer.destroy', $customer) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Không tìm thấy khách hàng nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Phân trang -->
    <div class="d-flex justify-content-center">
        {{ $customers->links() }}
    </div>
</div>
@endsection