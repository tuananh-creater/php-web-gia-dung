@extends('layouts.app')

@section('title', 'Lịch sử đơn hàng')

@section('content')
<section class="section-space">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <div>
                <span class="section-kicker">Tài khoản</span>
                <h2 class="mb-0">Lịch sử đơn hàng</h2>
            </div>

            <a href="{{ route('account.index') }}" class="btn btn-outline-dark">Quay lại tài khoản</a>
        </div>

        <div class="content-box">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Số SP</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $order->items_count }}</td>
                            <td class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
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
                            <td>
                                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-sm btn-dark">Xem</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Bạn chưa có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</section>
@endsection