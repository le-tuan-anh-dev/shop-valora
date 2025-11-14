@extends('admin.layouts.admin')

@section('content')
<h2>Thêm Voucher</h2>

<form action="{{ route('admin.vouchers.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Mã</label>
        <input type="text" name="code" class="form-control">
    </div>
    <div class="mb-3">
        <label>Loại</label>
        <select name="type" class="form-control">
            <option value="percent">%</option>
            <option value="fixed">Cố định</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Giá trị</label>
        <input type="number" step="0.01" name="value" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Lưu</button>
</form>
@endsection
