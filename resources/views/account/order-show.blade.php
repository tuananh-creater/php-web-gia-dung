@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<section class="section-space">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <div>
                <span class="section-kicker">Tài khoản</span>
                <h2 class="mb-0">Chi tiết đơn hàng #{{ $order->id }}</h2>
            </div>

            <a href="{{ route('account.orders') }}" class="btn btn-outline-dark">Quay lại lịch sử đơn</a>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="content-box">
                    <h4 class="mb-3">Sản phẩm trong đơn</h4>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>SL</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="fw-bold">{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr>

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

            <div class="col-lg-4">
                <div class="content-box">
                    <h4 class="mb-3">Thông tin đơn hàng</h4>

                    <p><strong>Mã đơn:</strong> #{{ $order->id }}</p>
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Người nhận:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                    <p><strong>Email:</strong> {{ $order->email ?: 'Không có' }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                    <p><strong>Thanh toán:</strong> {{ $paymentMethods[$order->payment_method] ?? $order->payment_method }}</p>
                    <p><strong>Trạng thái:</strong> {{ $statuses[$order->status] ?? $order->status }}</p>

                    @if($order->note)
                        <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection