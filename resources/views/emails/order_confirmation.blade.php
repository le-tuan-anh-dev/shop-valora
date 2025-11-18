<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .product-item {
            margin: 10px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 3px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container">

        <p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>
        
        <p>Cảm ơn bạn đã đặt hàng với chúng tôi. Đơn hàng của bạn đã được tiếp nhận thành công.</p>

        <!-- Thông Tin Đơn Hàng -->
        <div class="section">
            <h2>Thông Tin Đơn Hàng</h2>
            <table>
                <tr>
                    <td><strong>Mã đơn hàng:</strong></td>
                    <td>{{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td><strong>Ngày đặt:</strong></td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>Trạng thái:</strong></td>
                    <td>Chờ xác nhận</td>
                </tr>
            </table>
        </div>

        <!-- Chi Tiết Sản Phẩm -->
        <div class="section">
            <h2>Chi Tiết Sản Phẩm</h2>
            @foreach($orderItems as $item)
                <div class="product-item">
                    <strong>{{ $item->product_name }}</strong><br>
                    Số lượng: <strong>{{ $item->quantity }}</strong><br>
                    Giá: <strong>{{ number_format($item->unit_price, 0, ',', '.') }} đ</strong><br>
                    Tổng: <strong>{{ number_format($item->total_price, 0, ',', '.') }} đ</strong>
                    
                    @if($item->product_options)
                        <br>
                        @php $options = json_decode($item->product_options, true); @endphp
                        @foreach($options as $option)
                            <br>{{ $option['attribute'] }}: {{ $option['value'] }}
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Tóm Tắt Thanh Toán -->
        <div class="section">
            <h2>Tóm Tắt Thanh Toán</h2>
            <table>
                <tr>
                    <td>Tạm tính:</td>
                    <td>{{ number_format($order->subtotal, 0, ',', '.') }} đ</td>
                </tr>
                @if($order->promotion_amount > 0)
                <tr>
                    <td>Giảm giá:</td>
                    <td style="color: green;">-{{ number_format($order->promotion_amount, 0, ',', '.') }} đ</td>
                </tr>
                @endif
                <tr>
                    <td>Phí vận chuyển:</td>
                    <td>{{ number_format($order->shipping_fee, 0, ',', '.') }} đ</td>
                </tr>
                <tr class="total">
                    <td>TỔNG CỘNG:</td>
                    <td style="color: #e74c3c;">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                </tr>
            </table>
        </div>

        <!-- Địa Chỉ Giao Hàng -->
        <div class="section">
            <h2>Địa Chỉ Giao Hàng</h2>
            <p>
                <strong>{{ $order->receiver_name }}</strong><br>
                {{ $order->shipping_address }}<br>
                Điện thoại: {{ $order->receiver_phone }}
            </p>
        </div>

        <!-- Ghi Chú -->
        @if($order->note)
        <div class="section">
            <h2>Ghi Chú</h2>
            <p>{{ $order->note }}</p>
        </div>
        @endif

        <div class="footer">
            <p>Cảm ơn bạn đã mua sắm cùng chúng tôi!</p>
            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.</p>
            <p>&copy; {{ date('Y') }} Velora Store. All rights reserved.</p>
        </div>
    </div>
</body>
</html>