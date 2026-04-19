@extends('layouts.app')

@section('title', $product->name)

@section('content')
<section class="section-space">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="product-detail-image-box">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded-4">
                    @else
                        <div class="product-image-placeholder detail-placeholder">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="product-detail-box">
                    <div class="product-category mb-2">{{ $product->category->name ?? 'Sản phẩm' }}</div>
                    <h1 class="mb-3">{{ $product->name }}</h1>

                    <div class="product-price mb-3">
                        @if($product->sale_price)
                            <span class="old-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                        @endif
                        <span class="new-price">{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}đ</span>
                    </div>

                    <p class="mb-3">{{ $product->short_description }}</p>

                    <div class="product-meta-box">
                        <p><strong>Số lượng còn:</strong> {{ $product->quantity }}</p>
                        <p><strong>Trạng thái:</strong> {{ $product->status ? 'Đang bán' : 'Ngừng bán' }}</p>
                    </div>

                    <div class="d-flex gap-2 mt-3 flex-wrap">
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button class="btn btn-theme">Thêm vào giỏ</button>
                        </form>

                        <a href="{{ route('products.index') }}" class="btn btn-outline-dark">Quay lại danh sách</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h3 class="mb-3">Mô tả sản phẩm</h3>
            <div class="content-box">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>

        <div class="mt-5">
            <div class="content-box">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                    <div>
                        <h3 class="mb-1">Đánh giá sản phẩm</h3>
                        <div class="text-muted">
                            {{ $ratingCount }} đánh giá
                            @if($ratingCount > 0)
                                • Trung bình {{ $ratingAverage }}/5
                            @endif
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success mb-3">{{ session('success') }}</div>
                @endif

                @auth
                    <form action="{{ route('reviews.store', $product) }}" method="POST" class="mb-4">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Số sao</label>
                            <select name="rating" class="form-select">
                                <option value="">-- Chọn số sao --</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} sao
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nội dung đánh giá</label>
                            <textarea name="content" rows="4" class="form-control" placeholder="Chia sẻ cảm nhận của bạn...">{{ old('content') }}</textarea>
                            @error('content')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button class="btn btn-theme">Gửi đánh giá</button>
                    </form>
                @else
                    <div class="alert alert-info">
                        Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để đánh giá sản phẩm.
                    </div>
                @endauth

                <hr>

                @forelse($product->reviews as $review)
                    <div class="review-item {{ !$loop->last ? 'mb-4 pb-4 border-bottom' : '' }}">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="fw-semibold">{{ $review->user->name ?? 'Người dùng' }}</div>
                                <div class="text-warning mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                </div>
                                <div class="text-muted small mb-2">{{ $review->created_at->format('d/m/Y H:i') }}</div>
                                @if($review->content)
                                    <div>{{ $review->content }}</div>
                                @endif
                            </div>

                            @auth
                                @if($review->user_id === auth()->id())
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Xóa đánh giá này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Xóa</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Chưa có đánh giá nào cho sản phẩm này.</p>
                @endforelse
            </div>
        </div>

        @if($relatedProducts->count())
            <div class="mt-5">
                <h3 class="mb-4">Sản phẩm liên quan</h3>
                <div class="row g-4">
                    @foreach($relatedProducts as $item)
                        <div class="col-md-6 col-lg-3">
                            <div class="product-card">
                                <a href="{{ route('products.show', $item->slug) }}" class="product-image-link">
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="product-image">
                                    @else
                                        <div class="product-image-placeholder"><i class="bi bi-image"></i></div>
                                    @endif
                                </a>

                                <div class="product-body">
                                    <h5 class="product-title">
                                        <a href="{{ route('products.show', $item->slug) }}">{{ $item->name }}</a>
                                    </h5>
                                    <div class="product-price">
                                        <span class="new-price">{{ number_format($item->sale_price ?? $item->price, 0, ',', '.') }}đ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection