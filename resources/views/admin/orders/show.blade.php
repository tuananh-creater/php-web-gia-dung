@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1">Chi tiết đơn hàng #{{ $order->id }}</h2>
        <p class="text-muted mb-0">Thông tin đầy đủ của đơn hàng.</p>
    </div>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">
                <h4 class="mb-3">Thông tin khách hàng</h4>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Khách hàng:</strong>
                        <div>{{ $order->customer_name }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Số điện thoại:</strong>
                        <div>{{ $order->phone }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Email:</strong>
                        <div>{{ $order->email ?: 'Không có' }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Phương thức thanh toán:</strong>
                        <div>{{ $paymentMethods[$order->payment_method] ?? $order->payment_method }}</div>
                    </div>

                    <div class="col-12 mb-3">
                        <strong>Địa chỉ giao hàng:</strong>
                        <div>{{ $order->address }}</div>
                    </div>

                    <div class="col-12">
                        <strong>Ghi chú:</strong>
                        <div>{{ $order->note ?: 'Không có ghi chú' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h4 class="mb-3">Danh sách sản phẩm</h4>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
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
                                <td>
                                    <div class="fw-semibold">{{ $item->product_name }}</div>
                                    <small class="text-muted">
                                        ID sản phẩm: {{ $item->product_id }}
                                    </small>
                                </td>
                                <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="fw-bold">{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="ms-auto mt-3" style="max-width: 360px;">
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

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Tổng cộng</span>
                        <strong class="text-danger fs-5">{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">
                <h4 class="mb-3">Thông tin đơn</h4>

                <p><strong>Mã đơn:</strong> #{{ $order->id }}</p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p>
                    <strong>Trạng thái hiện tại:</strong><br>
                    @if($order->status === 'pending')
                        <span class="badge bg-warning text-dark mt-1">{{ $statuses[$order->status] }}</span>
                    @elseif($order->status === 'confirmed')
                        <span class="badge bg-primary mt-1">{{ $statuses[$order->status] }}</span>
                    @elseif($order->status === 'shipping')
                        <span class="badge bg-info text-dark mt-1">{{ $statuses[$order->status] }}</span>
                    @elseif($order->status === 'completed')
                        <span class="badge bg-success mt-1">{{ $statuses[$order->status] }}</span>
                    @else
                        <span class="badge bg-danger mt-1">{{ $statuses[$order->status] }}</span>
                    @endif
                </p>
                
                <p>
                    <strong>Đã hoàn kho:</strong>
                    @if($order->restocked)
                        <span class="badge bg-success">Đã hoàn</span>
                    @else
                        <span class="badge bg-secondary">Chưa hoàn</span>
                    @endif
                </p>

                @if($order->coupon_code)
                    <p><strong>Mã giảm giá:</strong> {{ $order->coupon_code }}</p>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h4 class="mb-3">Cập nhật trạng thái</h4>

                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Trạng thái mới</label>
                        <select name="status" class="form-select">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $order->status) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button class="btn btn-primary w-100">
                        <i class="bi bi-check2-circle me-1"></i> Cập nhật trạng thái
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection