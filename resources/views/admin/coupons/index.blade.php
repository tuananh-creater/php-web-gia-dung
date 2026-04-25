@extends('layouts.admin')

@section('title', 'Quản lý mã giảm giá')

@section('content')

<form method="GET" action="{{ route('admin.coupons.index') }}" class="row g-3 mb-4">
    <div class="col-md-6">
        <input type="text"
               name="keyword"
               class="form-control"
               value="{{ request('keyword') }}"
               placeholder="Tìm mã hoặc tiêu đề coupon">
    </div>

    <div class="col-md-2">
        <select name="discount_type" class="form-select">
            <option value="">-- Loại giảm --</option>
            <option value="fixed" {{ request('discount_type') === 'fixed' ? 'selected' : '' }}>Tiền cố định</option>
            <option value="percent" {{ request('discount_type') === 'percent' ? 'selected' : '' }}>Phần trăm</option>
        </select>
    </div>

    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">-- Trạng thái --</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
        </select>
    </div>

    <div class="col-md-2 d-flex gap-2">
        <button class="btn btn-primary w-100">Lọc</button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary w-100">Reset</a>
    </div>
</form>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Quản lý mã giảm giá</h2>
        <p class="text-muted mb-0">Danh sách coupon trong hệ thống.</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Thêm mã giảm giá
    </a>
</div>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Mã</th>
            <th>Tiêu đề</th>
            <th>Loại</th>
            <th>Giá trị</th>
            <th>Đơn tối thiểu</th>
            <th>Hết hạn</th>
            <th>Trạng thái</th>
            <th width="180">Thao tác</th>
        </tr>
        </thead>
        <tbody>
        @forelse($coupons as $coupon)
            <tr>
                <td>{{ $coupon->id }}</td>
                <td><strong>{{ $coupon->code }}</strong></td>
                <td>{{ $coupon->title }}</td>
                <td>{{ $coupon->discount_type === 'fixed' ? 'Tiền cố định' : 'Phần trăm' }}</td>
                <td>
                    @if($coupon->discount_type === 'fixed')
                        {{ number_format($coupon->discount_value, 0, ',', '.') }}đ
                    @else
                        {{ $coupon->discount_value }}%
                    @endif
                </td>
                <td>{{ number_format($coupon->min_order_value ?? 0, 0, ',', '.') }}đ</td>
                <td>{{ $coupon->expired_at ? $coupon->expired_at->format('d/m/Y') : 'Không giới hạn' }}</td>
                <td>
                    @if($coupon->status)
                        <span class="badge bg-success">Hoạt động</span>
                    @else
                        <span class="badge bg-secondary">Ẩn</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i> Sửa
                    </a>

                    <form action="{{ route('admin.coupons.destroy', $coupon) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center text-muted">Chưa có mã giảm giá nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $coupons->links() }}
</div>
@endsection