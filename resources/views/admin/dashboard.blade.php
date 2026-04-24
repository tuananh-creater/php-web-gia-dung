@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
    .stat-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        height: 100%;
    }

    .stat-card .icon-box {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 14px;
    }

    .stat-card .stat-label {
        color: #6b7280;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .stat-card .stat-value {
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .chart-card,
    .table-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .table-card .table {
        margin-bottom: 0;
    }

    .table-card .table td,
    .table-card .table th {
        vertical-align: middle;
    }

    .low-stock-badge {
        font-weight: 700;
        padding: 6px 10px;
        border-radius: 999px;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1">Dashboard quản trị</h2>
        <p class="text-muted mb-0">Tổng quan nhanh về sản phẩm, đơn hàng và doanh thu.</p>
    </div>

    <a href="{{ route('admin.reports.revenue') }}" class="btn btn-primary">
        <i class="bi bi-bar-chart-line me-1"></i> Xem báo cáo chi tiết
    </a>
</div>

<div class="row g-4 mb-4">

    <!-- <div class="col-12">
        <div class="card chart-card mb-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="mb-1">Sản phẩm bán chạy</h4>
                        <p class="text-muted mb-0">Top sản phẩm theo số lượng đã bán.</p>
                    </div>
                </div>
                <div class="row g-3">
                    @foreach($bestSellingProducts as $i => $item)
                    <div class="col-md-4 col-xl-2">
                        <div class="card stat-card border-start border-4
                            @if($loop->index === 0) border-warning
                            @elseif($loop->index === 1) border-secondary
                            @else border-danger @endif">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge rounded-pill
                                        @if($loop->index === 0) bg-warning text-dark
                                        @elseif($loop->index === 1) bg-secondary
                                        @else bg-danger @endif">
                                        #{{ $loop->iteration }}
                                    </span>
                                </div>
                                <div class="stat-label text-truncate" title="{{ $item->product->name ?? 'N/A' }}">
                                    {{ $item->product->name ?? 'N/A' }}
                                </div>
                                <div class="stat-value">{{ $item->total_sold }}</div>
                                <small class="text-muted">đã bán</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div> -->

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="icon-box bg-primary-subtle text-primary">
                    <i class="bi bi-grid"></i>
                </div>
                <div class="stat-label">Tổng danh mục</div>
                <div class="stat-value">{{ $totalCategories }}</div>
                <small class="text-muted">Tất cả danh mục trong hệ thống</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="icon-box bg-success-subtle text-success">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stat-label">Tổng sản phẩm</div>
                <div class="stat-value">{{ $totalProducts }}</div>
                <small class="text-muted">Bao gồm cả đang hiển thị và ẩn</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="icon-box bg-warning-subtle text-warning">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="stat-label">Tổng đơn hàng</div>
                <div class="stat-value">{{ $totalOrders }}</div>
                <small class="text-muted">Tất cả đơn hàng đã tạo</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="icon-box bg-success-subtle text-success">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="stat-label">Tổng lãi (lợi nhuận)</div>
                <div class="stat-value text-success">{{ number_format($profit, 0, ',', '.') }}đ</div>
                <small class="text-muted">
                    Doanh thu: {{ number_format($totalRevenue, 0, ',', '.') }}đ
                    - Giá vốn: {{ number_format($totalCost, 0, ',', '.') }}đ
                </small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="icon-box bg-info-subtle text-info">
                    <i class="bi bi-calendar-day"></i>
                </div>
                <div class="stat-label">Đơn hôm nay</div>
                <div class="stat-value">{{ $todayOrders }}</div>
                <small class="text-muted">Số đơn được tạo trong ngày</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="icon-box bg-dark-subtle text-dark">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="stat-label">Lãi hôm nay</div>
                <div class="stat-value text-success">
                    {{ number_format($todayProfit, 0, ',', '.') }}đ
                </div>
                <small class="text-muted">
                    Doanh thu: {{ number_format($todayRevenue, 0, ',', '.') }}đ
                    - Giá vốn: {{ number_format($todayCost, 0, ',', '.') }}đ
                </small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1 mb-4">
    <div class="col-md-6 col-xl-2">
        <div class="card stat-card">
            <div class="card-body">
                <div class="icon-box bg-warning-subtle text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-label">Chờ xác nhận</div>
                <div class="stat-value">{{ $pendingOrders }}</div>
                <small class="text-muted">Đơn chưa xử lý</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-2">
        <a href="{{ route('admin.contacts.index', ['status' => 'new']) }}" class="text-decoration-none">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-envelope-paper"></i>
                    </div>
                    <div class="stat-label">Liên hệ mới</div>
                    <div class="stat-value text-dark">{{ $newContactsCount }}</div>
                    <small class="text-muted">Chưa đọc / chưa xử lý</small>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-2">
        <div class="card stat-card">
            <div class="card-body">
                <div class="icon-box bg-success-subtle text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-label">Hoàn thành</div>
                <div class="stat-value">{{ $completedOrders }}</div>
                <small class="text-muted">Đơn đã xong</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-2">
        <div class="card stat-card">
            <div class="card-body">
                <div class="icon-box bg-danger-subtle text-danger">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-label">Đã hủy</div>
                <div class="stat-value">{{ $cancelledOrders }}</div>
                <small class="text-muted">Đơn bị hủy</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="icon-box bg-warning-subtle text-warning">
                    <i class="bi bi-star-fill"></i>
                </div>
                <div class="stat-label">Tổng đánh giá sản phẩm</div>
                <div class="stat-value">{{ $totalReviews }}</div>
                <small class="text-muted">TB: {{ $averageProductRating }}/5 sao</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="icon-box bg-info-subtle text-info">
                    <i class="bi bi-chat-left-text-fill"></i>
                </div>
                <div class="stat-label">Tổng bình luận bài viết</div>
                <div class="stat-value">{{ $totalComments }}</div>
                <small class="text-muted">Tổng tất cả bình luận</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="icon-box bg-success-subtle text-success">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-label">Đánh giá hôm nay</div>
                <div class="stat-value">{{ $todayReviews }}</div>
                <small class="text-muted">Review mới trong ngày</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="icon-box bg-primary-subtle text-primary">
                    <i class="bi bi-chat-dots-fill"></i>
                </div>
                <div class="stat-label">Bình luận hôm nay</div>
                <div class="stat-value">{{ $todayComments }}</div>
                <small class="text-muted">Comment mới trong ngày</small>
            </div>
        </div>
    </div>
</div>

<div class="card chart-card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h4 class="mb-1">Biểu đồ doanh thu 7 ngày gần nhất</h4>
                <p class="text-muted mb-0">Kết hợp doanh thu và số lượng đơn hàng theo ngày.</p>
            </div>
        </div>

        <canvas id="dashboardChart" height="100"></canvas>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card table-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1">Đơn hàng mới</h4>
                        <p class="text-muted mb-0">Các đơn hàng gần đây nhất trong hệ thống.</p>
                    </div>

                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-dark">
                        Xem tất cả
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>SP</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($latestOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $order->customer_name }}</div>
                                    <small class="text-muted">{{ $order->phone }}</small>
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
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-dark">
                                        Xem
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Chưa có đơn hàng nào.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card table-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1">Sản phẩm sắp hết hàng</h4>
                        <p class="text-muted mb-0">Sản phẩm có tồn kho từ 5 trở xuống.</p>
                    </div>

                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-dark">
                        Xem sản phẩm
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Tồn kho</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($lowStockProducts as $product)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    <small class="text-muted">ID: {{ $product->id }}</small>
                                </td>
                                <td>{{ $product->category->name ?? '---' }}</td>
                                <td>
                                    @if($product->quantity == 0)
                                        <span class="badge bg-danger low-stock-badge">Hết hàng</span>
                                    @elseif($product->quantity <= 2)
                                        <span class="badge bg-warning text-dark low-stock-badge">{{ $product->quantity }}</span>
                                    @else
                                        <span class="badge bg-info text-dark low-stock-badge">{{ $product->quantity }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Không có sản phẩm nào sắp hết hàng.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="card table-card">
                <div class="card-body">
                    <h4 class="mb-3">Đánh giá sản phẩm mới nhất</h4>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Người dùng</th>
                                    <th>Sản phẩm</th>
                                    <th>Sao</th>
                                    <th>Ngày</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($latestReviews as $review)
                                <tr>
                                    <td>{{ $review->user->name ?? '---' }}</td>
                                    <td>{{ $review->product->name ?? '---' }}</td>
                                    <td>{{ $review->rating }}/5</td>
                                    <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Chưa có đánh giá nào.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card table-card">
                <div class="card-body">
                    <h4 class="mb-3">Bình luận bài viết mới nhất</h4>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Người dùng</th>
                                    <th>Bài viết</th>
                                    <th>Nội dung</th>
                                    <th>Ngày</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($latestComments as $comment)
                                <tr>
                                    <td>{{ $comment->user->name ?? '---' }}</td>
                                    <td>{{ $comment->post->title ?? '---' }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($comment->content, 50) }}</td>
                                    <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Chưa có bình luận nào.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels         = @json($chartLabels);
    const revenueValues  = @json($chartRevenueValues);
    const orderValues    = @json($chartOrderValues); 
    const costValues     = @json($chartCostValues);
    const profitValues   = @json($chartProfitValues);

    new Chart(document.getElementById('dashboardChart'), {
        data: {
            labels,
            datasets: [
                {
                    type: 'bar',
                    label: 'Doanh thu',
                    data: revenueValues,
                    yAxisID: 'y',
                    backgroundColor: 'rgba(59,130,246,0.25)',
                    borderColor: 'rgba(59,130,246,0.8)',
                    borderWidth: 1,
                },
                {
                    type: 'bar',
                    label: 'Giá vốn',
                    data: costValues,
                    yAxisID: 'y',
                    backgroundColor: 'rgba(239,68,68,0.2)',
                    borderColor: 'rgba(239,68,68,0.7)',
                    borderWidth: 1,
                },
                {
                    type: 'line',
                    label: 'Lãi',
                    data: profitValues,
                    yAxisID: 'y',
                    tension: 0.35,
                    fill: false,
                    borderColor: '#16a34a',
                    backgroundColor: '#16a34a',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 2,
                },
                {
                    type: 'line',
                    label: 'Số đơn',
                    data: orderValues,
                    yAxisID: 'y1',
                    tension: 0.35,
                    fill: false,
                    borderColor: '#f59e0b',
                    borderWidth: 1.5,
                    pointRadius: 3,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    ticks: {
                        callback: v => new Intl.NumberFormat('vi-VN').format(v) + 'đ'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: { drawOnChartArea: false }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            if (ctx.dataset.label === 'Số đơn')
                                return ctx.dataset.label + ': ' + ctx.parsed.y;
                            return ctx.dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(ctx.parsed.y) + 'đ';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush

