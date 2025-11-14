@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Sửa thông tin khách hàng</h2>

    <form action="{{ route('admin.customer.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Tên</label>
            <input type="text" name="name" class="form-control" value="{{ $customer->name }}">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $customer->email }}">
        </div>
        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
        </div>
        <div class="mb-3">
            <label>Địa chỉ</label>
            <input type="text" name="address" class="form-control" value="{{ $customer->address }}">
        </div>

        <button class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection