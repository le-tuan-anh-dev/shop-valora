<!DOCTYPE html>
<html>
<head>
    <title>Báo cáo Thống kê Shop</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f7f9fc;
            padding: 20px;
        }
        h1, h2 {
            margin-bottom: 15px;
        }
        .stats {
            display: flex;
            gap: 30px;
            margin-bottom: 40px;
        }
        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgb(0 0 0 / 0.1);
            flex: 1;
            text-align: center;
        }
        .stat-box h3 {
            margin: 0;
            font-size: 24px;
        }
        .stat-box p {
            margin: 5px 0 0 0;
            font-size: 16px;
            color: #555;
        }
        .charts-container {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
        }
        .chart-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgb(0 0 0 / 0.1);
            flex: 1;
            min-width: 320px;
        }
        #doanhThuChart {
            height: 300px !important;
        }
        #danhMucChart {
            width: 200px !important;
            height: 200px !important;
            margin: auto;
        }
        .chart-legend {
            list-style: none;
            padding-left: 0;
            margin-top: 15px;
            max-width: 220px;
            margin-left: auto;
            margin-right: auto;
        }
        .chart-legend li {
            margin-bottom: 10px;
            font-size: 14px;
            display: flex;
            align-items: center;
            cursor: default;
        }
        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 12px;
        }
        .legend-text {
            flex: 1;
            color: #212529;
        }
        .legend-percent {
            font-weight: 600;
            color: #212529;
            margin-left: 10px;
        }
    </style>
</head>
<body>

<h1>Báo cáo Thống kê Shop</h1>

<div class="stats">
    <div class="stat-box">
        <h3>{{ $totalOrders }}</h3>
        <p>Tổng số đơn hàng</p>
    </div>
    <div class="stat-box">
        <h3>{{ number_format($totalRevenue) }}₫</h3>
        <p>Tổng doanh thu</p>
    </div>
    <div class="stat-box">
        <h3>{{ $totalProducts }}</h3>
        <p>Tổng sản phẩm bán ra</p>
    </div>
</div>

<div class="charts-container">
    <!-- Biểu đồ doanh thu theo tháng -->
    <div class="chart-box">
        <h2>Doanh thu theo tháng</h2>
        <canvas id="doanhThuChart"></canvas>
    </div>

    <!-- Biểu đồ danh mục sản phẩm -->
    <div class="chart-box">
        <h2>Danh mục sản phẩm</h2>
        <canvas id="danhMucChart"></canvas>
        <ul class="chart-legend" id="chartLegend"></ul>
    </div>
</div>

<script>
    // Line chart doanh thu theo tháng
    const doanhThuLabels = [
        @foreach($doanhThuTheoThang as $item)
            '{{ $item->thang }}',
        @endforeach
    ];
    const doanhThuData = [
        @foreach($doanhThuTheoThang as $item)
            {{ $item->doanh_thu }},
        @endforeach
    ];
    const ctxDoanhThu = document.getElementById('doanhThuChart').getContext('2d');
    new Chart(ctxDoanhThu, {
        type: 'line',
        data: {
            labels: doanhThuLabels,
            datasets: [{
                label: 'Doanh thu',
                data: doanhThuData,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.3)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#007bff',
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', {style:'currency', currency:'VND'}).format(value);
                        }
                    }
                }
            },
            plugins: { legend: { display: true } }
        }
    });

    // Pie chart danh mục sản phẩm (chỉ 4 màu)
    const danhMucLabels = [
        @foreach($danhMucSanPham->take(4) as $item)
            '{{ $item->product_name }}',
        @endforeach
    ];
    const danhMucData = [
        @foreach($danhMucSanPham->take(4) as $item)
            {{ $item->total_quantity }},
        @endforeach
    ];
    const colors = ['#007bff', '#28a745', '#ffc107', '#dc3545'];
    const total = danhMucData.reduce((a,b) => a+b, 0);

    const ctxDanhMuc = document.getElementById('danhMucChart').getContext('2d');
    new Chart(ctxDanhMuc, {
        type: 'doughnut',
        data: {
            labels: danhMucLabels,
            datasets: [{ data: danhMucData, backgroundColor: colors, borderWidth: 0 }]
        },
        options: { responsive: false, cutout: '60%', plugins: { legend: { display: false }, tooltip: { enabled: true } } }
    });

    // Legend danh mục sản phẩm với %
    const legendContainer = document.getElementById('chartLegend');
    danhMucLabels.forEach((label, index) => {
        const percent = ((danhMucData[index] / total) * 100).toFixed(1);
        const li = document.createElement('li');
        const colorBox = document.createElement('span');
        colorBox.className = 'legend-color';
        colorBox.style.backgroundColor = colors[index];
        li.appendChild(colorBox);
        const text = document.createElement('span');
        text.className = 'legend-text';
        text.textContent = label;
        li.appendChild(text);
        const percentText = document.createElement('span');
        percentText.className = 'legend-percent';
        percentText.textContent = percent + '%';
        li.appendChild(percentText);
        legendContainer.appendChild(li);
    });
</script>

</body>
</html>
