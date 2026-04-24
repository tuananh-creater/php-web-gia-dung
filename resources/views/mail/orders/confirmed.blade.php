<x-mail::message>
# Cảm ơn bạn đã đặt hàng

Xin chào **{{ $order->customer_name }}**,

Đơn hàng **#{{ $order->id }}** của bạn đã được ghi nhận thành công.

<x-mail::panel>
Tổng thanh toán: <strong>{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong>
</x-mail::panel>

**Thông tin đơn hàng**
@foreach($order->items as $item)
- {{ $item->product_name }} × {{ $item->quantity }} — {{ number_format($item->subtotal, 0, ',', '.') }}đ
@endforeach

**Phương thức thanh toán:**  
{{ $order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản ngân hàng' }}

**Địa chỉ nhận hàng:**  
{{ $order->address }}

<x-mail::button :url="route('account.orders.show', $order)">
Xem chi tiết đơn hàng
</x-mail::button>

Cảm ơn bạn đã tin tưởng {{ config('app.name') }}.

Trân trọng,  
{{ config('app.name') }}
</x-mail::message>