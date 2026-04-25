<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo doanh thu</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin-bottom: 8px; }
        .meta { margin-bottom: 12px; }
        .summary { margin-bottom: 14px; }
        .summary div { margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; vertical-align: top; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>BÁO CÁO DOANH THU</h2>
    <div class="meta">
        Thời gian lọc:
        {{ $from ?: 'Từ đầu' }} -
        {{ $to ?: 'Đến nay' }}
    </div>

    <div class="summary">
        <div><strong>Tổng đơn hợp lệ:</strong> {{ $summary['total_orders'] }}</div>
        <div><strong>Tổng doanh thu:</strong> {{ number_format($summary['total_revenue'], 0, ',', '.') }}đ</div>
        <div><strong>Tổng giảm giá:</strong> {{ number_format($summary['total_discount'], 0, ',', '.') }}đ</div>
        <div><strong>Tổng phí ship:</strong> {{ number_format($summary['total_shipping'], 0, ',', '.') }}đ</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>SĐT</th>
                <th>Tổng tiền</th>
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
                <td>{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                <td>{{ $statuses[$order->status] ?? $order->status }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>