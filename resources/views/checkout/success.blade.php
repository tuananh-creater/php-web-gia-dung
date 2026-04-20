@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<section class="section-space">
    <div class="container">
        <div class="content-box text-center mb-4">
            <div class="mb-3" style="font-size: 64px; color: #16a34a;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <h2>Đặt hàng thành công</h2>
            <p class="text-muted">
                Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ liên hệ sớm để xác nhận đơn hàng.
            </p>
            <a href="{{ route('home') }}" class="btn btn-theme">Về trang chủ</a>
        </div>

        <div class="content-box">
            <h4 class="mb-3">Thông tin đơn hàng #{{ $order->id }}</h4>

            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Khách hàng:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                    <p><strong>Email:</strong> {{ $order->email ?: 'Không có' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Phương thức thanh toán:</strong>
                        {{ $order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản ngân hàng' }}
                    </p>
                    <p><strong>Trạng thái:</strong> {{ ucfirst($order->status) }}</p>
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <p><strong>Địa chỉ nhận hàng:</strong> {{ $order->address }}</p>

            @if($order->note)
                <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
            @endif

            <hr>

            <h5 class="mb-3">Chi tiết sản phẩm</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="ms-auto" style="max-width: 360px;">
                <div class="d-flex justify-content-between mb-2">
                    <span>Tạm tính</span>
                    <strong>{{ number_format($order->subtotal, 0, ',', '.') }}đ</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Giảm giá</span>
                    <strong class="text-success">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Phí vận chuyển</span>
                    <strong>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="fw-bold">Tổng cộng</span>
                    <strong class="text-danger fs-5">{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection