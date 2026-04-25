@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<section class="section-space">
    <div class="container">
        <div class="mb-4">
            <span class="section-kicker">Thanh toán</span>
            <h2 class="mb-0">Thông tin đặt hàng</h2>
        </div>

        @if(session('error'))
            <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="content-box">
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $user->name ?? '') }}">
                            @error('customer_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ nhận hàng</label>
                            <textarea name="address" rows="4" class="form-control">{{ old('address') }}</textarea>
                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phương thức thanh toán</label>
                            <select name="payment_method" class="form-select">
                                <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Thanh toán khi nhận hàng (COD)</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản ngân hàng</option>
                            </select>
                            @error('payment_method')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="note" rows="4" class="form-control">{{ old('note') }}</textarea>
                            @error('note')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button class="btn btn-theme">Xác nhận đặt hàng</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="content-box">
                    <h4 class="mb-3">Đơn hàng của bạn</h4>

                    @foreach($cart as $item)
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="fw-semibold">{{ $item['name'] }}</div>
                                <small class="text-muted">SL: {{ $item['quantity'] }}</small>
                            </div>
                            <strong>{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ</strong>
                        </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính</span>
                        <strong>{{ number_format($summary['subtotal'], 0, ',', '.') }}đ</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Giảm giá</span>
                        <strong class="text-success">-{{ number_format($summary['discount_amount'], 0, ',', '.') }}đ</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển</span>
                        <strong>{{ number_format($summary['shipping_fee'], 0, ',', '.') }}đ</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Tổng thanh toán</span>
                        <strong class="text-danger fs-5">{{ number_format($summary['total'], 0, ',', '.') }}đ</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection