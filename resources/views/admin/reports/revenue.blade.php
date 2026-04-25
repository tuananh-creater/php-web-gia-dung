@extends('layouts.admin')

@section('title', 'Báo cáo doanh thu')

@section('content')

<div class="d-flex gap-2 mb-3">
    <a href="{{ route('admin.exports.revenue.excel', request()->query()) }}" class="btn btn-success">
        <i class="bi bi-file-earmark-excel me-1"></i> Xuất Excel
    </a>

    <a href="{{ route('admin.exports.revenue.pdf', request()->query()) }}" class="btn btn-danger">
        <i class="bi bi-file-earmark-pdf me-1"></i> Xuất PDF
    </a>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1">Báo cáo doanh thu</h2>
        <p class="text-muted mb-0">Thống kê doanh thu và số lượng đơn hàng trong hệ thống.</p>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger mb-3">{{ session('error') }}</div>
@endif

<form method="GET" action="{{ route('admin.reports.revenue') }}" class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">Từ ngày</label>
        <input type="date" name="from" class="form-control" value="{{ $from }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Đến ngày</label>
        <input type="date" name="to" class="form-control" value="{{ $to }}">
    </div>

    <div class="col-md-4 d-flex align-items-end gap-2">
        <button class="btn btn-primary w-100">Lọc dữ liệu</button>
        <a href="{{ route('admin.reports.revenue') }}" class="btn btn-secondary w-100">Reset</a>
    </div>
</form>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="text-muted mb-2">Tổng đơn hợp lệ</div>
                <div class="fs-3 fw-bold">{{ $totalOrders }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="text-muted mb-2">Tổng doanh thu</div>
                <div class="fs-3 fw-bold text-danger">{{ number_format($totalRevenue, 0, ',', '.') }}đ</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="text-muted mb-2">Tổng giảm giá</div>
                <div class="fs-3 fw-bold text-success">{{ number_format($totalDiscount, 0, ',', '.') }}đ</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="text-muted mb-2">Tổng phí ship</div>
                <div class="fs-3 fw-bold">{{ number_format($totalShipping, 0, ',', '.') }}đ</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <h4 class="mb-3">Số lượng đơn theo trạng thái</h4>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Trạng thái</th>
                                <th width="140">Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statuses as $key => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>{{ $ordersByStatus[$key] ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <h4 class="mb-3">Doanh thu theo ngày</h4>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Ngày</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($dailyRevenue as $row)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($row->order_date)->format('d/m/Y') }}</td>
                                <td class="fw-bold text-danger">{{ number_format($row->revenue, 0, ',', '.') }}đ</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Chưa có dữ liệu doanh thu.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <h4 class="mb-3">10 đơn hàng gần nhất</h4>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>SP</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th width="120">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($latestOrders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->items_count }}</td>
                        <td class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                        <td>{{ $statuses[$order->status] ?? $order->status }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-dark">
                                Xem
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Chưa có đơn hàng nào.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection