<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách đơn hàng</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin-bottom: 8px; }
        .meta { margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; vertical-align: top; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>DANH SÁCH ĐƠN HÀNG</h2>
    <div class="meta">Ngày xuất: {{ now()->format('d/m/Y H:i') }}</div>

    <table>
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>SĐT</th>
                <th>Số SP</th>
                <th>Tổng tiền</th>
                <th>Thanh toán</th>
                <th>Trạng thái</th>
                <th>Ngày đặt</th>
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->phone }}</td>
                <td>{{ $order->items_count }}</td>
                <td>{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                <td>{{ $paymentMethods[$order->payment_method] ?? $order->payment_method }}</td>
                <td>{{ $statuses[$order->status] ?? $order->status }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>