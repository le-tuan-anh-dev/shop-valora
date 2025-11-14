@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Thêm khách hàng</h2>

    <form action="{{ route('admin.customer.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Tên khách hàng</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="mb-3">
            <label>Địa chỉ</label>
            <input type="text" name="address" class="form-control">
        </div>
        <button class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection