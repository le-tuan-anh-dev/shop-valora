<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Báo Cáo Thống Kê</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Báo Cáo Thống Kê Shop Quần Áo</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-primary p-3">
                <h5>Tổng Doanh Thu</h5>
                <p>{{ number_format($tongDoanhThu) }}₫</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-success p-3">
                <h5>Tổng Đơn Hàng</h5>
                <p>{{ $tongDonHang }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-warning p-3">
                <h5>Tổng Sản Phẩm Bán Ra</h5>
                <p>{{ $tongSanPham }}</p>
            </div>
        </div>
    </div>

    <h3>Doanh Thu Theo Tháng</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Tháng</th>
            <th>Doanh Thu (₫)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($doanhThuThang as $item)
            <tr>
                <td>{{ $item->thang }}</td>
                <td>{{ number_format($item->doanh_thu) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
