@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')

<div class="d-flex gap-2 mb-3">
    <a href="{{ route('admin.exports.orders.excel', request()->query()) }}" class="btn btn-success">
        <i class="bi bi-file-earmark-excel me-1"></i> Xuất Excel
    </a>

    <a href="{{ route('admin.exports.orders.pdf', request()->query()) }}" class="btn btn-danger">
        <i class="bi bi-file-earmark-pdf me-1"></i> Xuất PDF
    </a>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1">Quản lý đơn hàng</h2>
        <p class="text-muted mb-0">Danh sách đơn hàng đã được tạo từ hệ thống.</p>
    </div>
</div>

<form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 mb-4">
    <div class="col-md-4">
        <input type="text"
               name="keyword"
               class="form-control"
               value="{{ request('keyword') }}"
               placeholder="Mã đơn / khách hàng / số điện thoại / email">
    </div>

    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">-- Tất cả trạng thái --</option>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <select name="payment_method" class="form-select">
            <option value="">-- Tất cả thanh toán --</option>
            @foreach($paymentMethods as $key => $label)
                <option value="{{ $key }}" {{ request('payment_method') === $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2 d-flex gap-2">
        <button class="btn btn-primary w-100">Lọc</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">Reset</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th width="90">Mã đơn</th>
                <th>Khách hàng</th>
                <th>SĐT</th>
                <th>Thanh toán</th>
                <th>SP</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày đặt</th>
                <th width="140">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>
                    <div class="fw-semibold">{{ $order->customer_name }}</div>
                    <small class="text-muted">{{ $order->email ?: 'Không có email' }}</small>
                </td>
                <td>{{ $order->phone }}</td>
                <td>
                    {{ $paymentMethods[$order->payment_method] ?? $order->payment_method }}
                </td>
                <td>{{ $order->items_count }}</td>
                <td class="fw-bold text-danger">
                    {{ number_format($order->total_amount, 0, ',', '.') }}đ
                </td>
                <td>
                    @if($order->status === 'pending')
                        <span class="badge bg-warning text-dark">{{ $statuses[$order->status] }}</span>
                    @elseif($order->status === 'confirmed')
                        <span class="badge bg-primary">{{ $statuses[$order->status] }}</span>
                    @elseif($order->status === 'shipping')
                        <span class="badge bg-info text-dark">{{ $statuses[$order->status] }}</span>
                    @elseif($order->status === 'completed')
                        <span class="badge bg-success">{{ $statuses[$order->status] }}</span>
                    @else
                        <span class="badge bg-danger">{{ $statuses[$order->status] }}</span>
                    @endif
                </td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-dark">
                        <i class="bi bi-eye"></i> Chi tiết
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center text-muted">Chưa có đơn hàng nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $orders->links() }}
</div>
@endsection