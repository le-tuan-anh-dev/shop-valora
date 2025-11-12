@extends('admin.layouts.admin')

@section('content')
<h2>Sửa Voucher</h2>

<form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Mã</label>
        <input type="text" value="{{ $voucher->code }}" class="form-control" disabled>
    </div>
    <div class="mb-3">
        <label>Loại</label>
        <select name="type" class="form-control">
            <option value="percent" @selected($voucher->type == 'percent')>%</option>
            <option value="fixed" @selected($voucher->type == 'fixed')>Cố định</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Giá trị</label>
        <input type="number" step="0.01" name="value" value="{{ $voucher->value }}" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>
@endsection
