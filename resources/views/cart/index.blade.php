@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<section class="section-space">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <div>
                <span class="section-kicker">Giỏ hàng của bạn</span>
                <h2 class="mb-0">Sản phẩm đã chọn</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-theme-soft">Tiếp tục mua hàng</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif

        @if(count($cart))
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="content-box">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th width="150">Số lượng</th>
                                        <th>Tạm tính</th>
                                        <th width="100">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($cart as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                @if($item['image'])
                                                    <img src="{{ asset('storage/' . $item['image']) }}"
                                                         width="80"
                                                         height="80"
                                                         style="object-fit: cover; border-radius: 12px;"
                                                         alt="{{ $item['name'] }}">
                                                @else
                                                    <div style="width:80px;height:80px;background:#f3efe9;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                @endif

                                                <div>
                                                    <div class="fw-semibold">{{ $item['name'] }}</div>
                                                    <small class="text-muted">Kho còn: {{ $item['stock'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-semibold text-danger">
                                            {{ number_format($item['price'], 0, ',', '.') }}đ
                                        </td>
                                        <td>
                                            <form action="{{ route('cart.update', $item['id']) }}" method="POST">
                                                @csrf
                                                <div class="d-flex gap-2">
                                                    <input type="number"
                                                           name="quantity"
                                                           min="1"
                                                           max="{{ $item['stock'] }}"
                                                           value="{{ $item['quantity'] }}"
                                                           class="form-control form-control-sm">
                                                    <button class="btn btn-sm btn-outline-dark">Cập nhật</button>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="fw-bold">
                                            {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ
                                        </td>
                                        <td>
                                            <form action="{{ route('cart.remove', $item['id']) }}" method="POST" onsubmit="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between flex-wrap gap-2 mt-3">
                            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Bạn muốn xóa toàn bộ giỏ hàng?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger">Xóa toàn bộ giỏ hàng</button>
                            </form>

                            <a href="{{ route('checkout.index') }}" class="btn btn-theme">Tiến hành đặt hàng</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="content-box mb-4">
                        <h4 class="mb-3">Mã giảm giá</h4>

                        @if(!empty($summary['coupon']))
                            <div class="alert alert-success">
                                <div><strong>{{ $summary['coupon']['code'] }}</strong></div>
                                <div>{{ $summary['coupon']['title'] }}</div>
                            </div>

                            <form action="{{ route('cart.removeCoupon') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger w-100">Xóa mã giảm giá</button>
                            </form>
                        @else
                            <form action="{{ route('cart.applyCoupon') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <input type="text" name="coupon_code" class="form-control" placeholder="Nhập mã giảm giá">
                                    @error('coupon_code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <button class="btn btn-theme w-100">Áp dụng mã</button>
                            </form>
                        @endif
                    </div>

                    <div class="content-box">
                        <h4 class="mb-3">Tóm tắt đơn hàng</h4>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Số lượng sản phẩm</span>
                            <strong>{{ $summary['item_count'] }}</strong>
                        </div>

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

                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Tổng cộng</span>
                            <strong class="text-danger fs-5">{{ number_format($summary['total'], 0, ',', '.') }}đ</strong>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-theme w-100">Đặt hàng ngay</a>
                    </div>
                </div>
            </div>
        @else
            <div class="content-box text-center">
                <h4>Giỏ hàng đang trống</h4>
                <p class="text-muted">Bạn chưa thêm sản phẩm nào vào giỏ hàng.</p>
                <a href="{{ route('products.index') }}" class="btn btn-theme">Mua sắm ngay</a>
            </div>
        @endif
    </div>
</section>
@endsection