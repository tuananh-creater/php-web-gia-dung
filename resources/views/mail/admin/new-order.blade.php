<x-mail::message>
# Có đơn hàng mới

Hệ thống vừa ghi nhận đơn hàng mới **#{{ $order->id }}**.

**Khách hàng:** {{ $order->customer_name }}  
**Số điện thoại:** {{ $order->phone }}  
**Email:** {{ $order->email ?: 'Không có' }}  
**Tổng tiền:** {{ number_format($order->total_amount, 0, ',', '.') }}đ

<x-mail::button :url="route('admin.orders.show', $order)">
Xem chi tiết đơn hàng
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>