@extends('admin.layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Danh sách Voucher</h2>

    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary mb-3">+ Thêm mới</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Loại</th>
                <th>Giá trị</th>
                <th>Hiệu lực</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vouchers as $voucher)
                <tr>
                    <td>{{ $voucher->code }}</td>
                    <td>{{ $voucher->type }}</td>
                    <td>{{ $voucher->value }}</td>
                    <td>{{ $voucher->start_date }} - {{ $voucher->end_date }}</td>
                    <td>
                        <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                        <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa voucher này không?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Phân trang --}}
    <div class="d-flex justify-content-center">
        {{ $vouchers->links() }}
    </div>
</div>
@endsection
