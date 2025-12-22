<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo hủy đơn hàng</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .alert {
            background-color: #fee;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .alert-title {
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .order-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .order-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-info td {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .order-info td:first-child {
            font-weight: bold;
            color: #667eea;
            width: 40%;
        }
        .order-info tr:last-child td {
            border-bottom: none;
        }
        .reason-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .reason-title {
            color: #856404;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .reason-text {
            color: #856404;
            line-height: 1.8;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .products-table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }
        .products-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        .products-table tr:last-child td {
            border-bottom: none;
        }
        .text-right {
            text-align: right;
        }
        .contact-section {
            background-color: #ff6161;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .contact-section h3 {
            color: #000000;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .contact-info {
            list-style: none;
        }
        .contact-info li {
            margin-bottom: 8px;
            font-size: 14px;
        }
        .contact-info strong {
            color: #000000;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #dee2e6;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #5568d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1> Thông báo: Đơn hàng đã bị hủy</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                <p>Xin chào <strong>{{ $customerName ?? $order->user->name }} </strong>,</p>
                <p>Chúng tôi xin thông báo rằng đơn hàng của bạn đã bị hủy.</p>
            </div>

            <!-- Alert -->
            <div class="alert">
                <div class="alert-title"> Chi tiết hủy đơn hàng</div>
                <div class="order-info">
                    <table>
                        <tr>
                            <td>Mã đơn hàng:</td>
                            <td><strong>#{{ $orderNumber ?? $order->order_number }}</strong></td>
                        </tr>
                        <tr>
                            <td>Ngày hủy:</td>
                            <td>{{ $cancelledAt ?? $order->cancelled_at }}</td>
                        </tr>
                        <tr>
                            <td>Tổng giá trị:</td>
                            <td><strong>{{ $totalAmount ?? $order->total_amount }}₫</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Reason -->
            <div class="reason-box">
                <div class="reason-title"> Lý do hủy đơn hàng</div>
                <div class="reason-text">
                    {{ $cancelReason ?? 'Khách Hủy' }}
                </div>
            </div>

            <!-- Products Table -->
            @if($order->orderItems && count($order->orderItems) > 0)
            <h3 style="color: #667eea; margin: 20px 0 10px 0;">📦 Thông tin sản phẩm</h3>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-right">Số lượng</th>
                        <th class="text-right">Đơn giá</th>
                        <th class="text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            @if(isset($item->product_options) && !empty($item->product_options))
                                @php
                                    $options = is_string($item->product_options) ? json_decode($item->product_options, true) : $item->product_options;
                                @endphp
                                @if(is_array($options))
                                    <br><small style="color: #666;">
                                        @foreach($options as $option)
                                            @if(isset($option['attribute']) && isset($option['value']))
                                                {{ $option['attribute'] }}: <strong>{{ $option['value'] }}</strong>
                                                @if(!$loop->last)<br>@endif
                                            @endif
                                        @endforeach
                                    </small>
                                @endif
                            @endif
                        </td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 0, ',', '.') }}₫</td>
                        <td class="text-right"><strong>{{ number_format($item->total_price, 0, ',', '.') }}₫</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            <!-- Contact Section -->
            <div class="contact-section">
                <h3><strong> Vui lòng liên hệ với chúng tôi để hoàn tiền (nếu đã thanh toán) qua:</strong></h3>
                <ul class="contact-info">
                    <li><strong>Email:</strong> valorashop@gmail.com</li>
                    <li><strong>Điện thoại:</strong> +84 912 345 678</li>
                </ul>
            </div>

            <p style="color: #666; text-align: center; margin-top: 20px;">
                Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Bảo lưu mọi quyền.</p>
        </div>
    </div>
</body>
</html>