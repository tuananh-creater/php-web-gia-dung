@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')

<section class="hero-section">
    <div class="container">
        <div id="homeBannerCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($banners as $key => $banner)
                    <button type="button"
                            data-bs-target="#homeBannerCarousel"
                            data-bs-slide-to="{{ $key }}"
                            class="{{ $key === 0 ? 'active' : '' }}"
                            aria-current="{{ $key === 0 ? 'true' : 'false' }}">
                    </button>
                @endforeach
            </div>

            <div class="carousel-inner">
                @forelse($banners as $key => $banner)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <div class="hero-banner">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="hero-content">
                                        <span class="hero-tag">HomeKit</span>
                                        <h1>{{ $banner->title }}</h1>
                                        <p>{{ $banner->subtitle }}</p>
                                        <a href="{{ $banner->link ?: route('products.index') }}" class="btn btn-theme">
                                            Khám phá ngay
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="hero-image-wrap">
                                        <img src="{{ asset('storage/' . $banner->image) }}"
                                             alt="{{ $banner->title }}"
                                             class="img-fluid hero-image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="carousel-item active">
                        <div class="hero-banner">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="hero-content">
                                        <span class="hero-tag">HomeKit</span>
                                        <h1>Đồ gia dụng</h1>
                                        <p>Không gian sống ấm áp, tinh tế và gần gũi với thiên nhiên.</p>
                                        <a href="{{ route('products.index') }}" class="btn btn-theme">
                                            Xem sản phẩm
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="hero-image-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#homeBannerCarousel" data-bs-slide="prev">
                <span class="hero-arrow"><i class="bi bi-chevron-left"></i></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeBannerCarousel" data-bs-slide="next">
                <span class="hero-arrow"><i class="bi bi-chevron-right"></i></span>
            </button>
        </div>
    </div>
</section>

<section class="category-section section-space">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">Danh mục nổi bật</span>
            <h2>Khám phá theo từng nhóm sản phẩm</h2>
            <p>Gợi ý các nhóm decor và gia dụng mang cảm hứng mây tre đan như mẫu giao diện bạn gửi.</p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($categories as $category)
                <div class="col-6 col-md-4 col-lg">
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="category-card text-decoration-none">
                        <div class="category-thumb">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                            @else
                                <div class="category-thumb-placeholder">
                                    <i class="bi bi-grid"></i>
                                </div>
                            @endif
                        </div>
                        <h5>{{ $category->name }}</h5>
                        <span>Xem sản phẩm</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="sale-section section-space">
    <div class="container">
        <div class="sale-box">
            <div class="sale-box-header">
                <div>
                    <span class="section-kicker">Cùng HomeKit khởi động</span>
                    <h2 class="mb-1">Khuyến mãi nổi bật</h2>
                    <p class="mb-0">Những sản phẩm đang có giá tốt theo phong cách giống layout mẫu.</p>
                </div>
                <div class="sale-timer">
                    <i class="bi bi-clock me-2"></i>Ưu đãi đang diễn ra
                </div>
            </div>

            <div class="row g-4 mt-1">
                @foreach($saleProducts as $product)
                    @php
                        $discountPercent = 0;
                        if ($product->sale_price && $product->price > 0) {
                            $discountPercent = round((($product->price - $product->sale_price) / $product->price) * 100);
                        }
                    @endphp

                    <div class="col-md-6 col-lg-3">
                        <div class="product-card">
                            @if($discountPercent > 0)
                                <div class="discount-badge">-{{ $discountPercent }}%</div>
                            @endif

                            <a href="{{ route('products.show', $product->slug) }}" class="product-image-link">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                                @else
                                    <div class="product-image-placeholder"><i class="bi bi-image"></i></div>
                                @endif
                            </a>

                            <div class="product-body">
                                <h5 class="product-title">
                                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                </h5>

                                <div class="product-price">
                                    <span class="old-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                    <span class="new-price">{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}đ</span>
                                </div>

                                <div class="product-actions">
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-theme-soft">Chi tiết</a>

                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="m-0">
                                        @csrf
                                        <button class="btn icon-btn" type="submit">
                                            <i class="bi bi-bag-plus"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="bestseller-section section-space">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">Được yêu thích nhất</span>
            <h2>Sản phẩm bán chạy</h2>
            <p>Những sản phẩm được khách hàng tin tưởng và lựa chọn nhiều nhất.</p>
        </div>

        <div class="row g-4">
            @foreach($bestSellingProducts as $index => $item)
                @php
                    $product = $item->product;
                    if (!$product) continue;
                    $currentPrice = $product->sale_price ?? $product->price;
                    $discountPercent = 0;
                    if ($product->sale_price && $product->price > 0) {
                        $discountPercent = round((($product->price - $product->sale_price) / $product->price) * 100);
                    }
                @endphp

                <div class="col-md-6 col-lg-3">
                    <div class="product-card bestseller-card">

                        {{-- Rank badge --}}
                        <div class="bestseller-rank rank-{{ $index + 1 <= 3 ? $index + 1 : 'other' }}">
                            #{{ $index + 1 }}
                        </div>

                        {{-- Hot badge cho top 1 --}}
                        @if($index === 0)
                            <div class="bestseller-hot-badge">
                                <i class="bi bi-fire"></i> Bán chạy nhất
                            </div>
                        @endif

                        @if($discountPercent > 0)
                            <div class="discount-badge">-{{ $discountPercent }}%</div>
                        @endif

                        <a href="{{ route('products.show', $product->slug) }}" class="product-image-link">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                            @else
                                <div class="product-image-placeholder"><i class="bi bi-image"></i></div>
                            @endif
                        </a>

                        <div class="product-body">
                            <div class="product-category">{{ $product->category->name ?? 'Sản phẩm' }}</div>

                            <h5 class="product-title">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            </h5>

                            <div class="bestseller-sold-info">
                                <i class="bi bi-bag-check-fill me-1"></i>
                                Đã bán <strong>{{ $item->total_sold }}</strong> sản phẩm
                            </div>

                            <div class="product-price">
                                @if($product->sale_price)
                                    <span class="old-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                @endif
                                <span class="new-price">{{ number_format($currentPrice, 0, ',', '.') }}đ</span>
                            </div>

                            <div class="d-flex gap-2 mt-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-theme-soft flex-fill">
                                    Xem chi tiết
                                </a>
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-fill">
                                    @csrf
                                    <button class="btn btn-theme w-100">Thêm giỏ</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section-space pt-0">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">HomeKit</span>
            <h2>Mã giảm giá dành cho bạn</h2>
        </div>

        @if($coupons->count())
            <div id="couponCarousel" class="carousel slide coupon-carousel-wrap" data-bs-ride="false">
                <div class="carousel-inner">
                    @foreach($coupons->chunk(3) as $chunkIndex => $couponChunk)
                        <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                            <div class="row g-4">
                                @foreach($couponChunk as $coupon)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="coupon-ticket h-100">
                                            <div class="coupon-ticket__icon">
                                                <i class="bi bi-ticket-perforated-fill"></i>
                                            </div>

                                            <div class="coupon-ticket__content">
                                                <h4 class="coupon-ticket__code">{{ $coupon->code }}</h4>
                                                <p class="coupon-ticket__desc">
                                                    @if($coupon->discount_type === 'fixed')
                                                        Giảm {{ number_format($coupon->discount_value / 1000, 0, ',', '.') }}k giá trị đơn hàng
                                                    @else
                                                        Giảm {{ $coupon->discount_value }}% giá trị đơn hàng
                                                    @endif
                                                </p>
                                                <span class="coupon-ticket__date">
                                                    HSD:
                                                    {{ $coupon->expired_at ? $coupon->expired_at->format('d/m/Y') : 'Không giới hạn' }}
                                                </span>
                                            </div>

                                            <div class="coupon-ticket__action">
                                                <button
                                                    type="button"
                                                    class="coupon-copy-btn"
                                                    data-code="{{ $coupon->code }}">
                                                    Copy mã
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($coupons->count() > 3)
                    <button class="carousel-control-prev coupon-carousel-btn coupon-carousel-btn--prev"
                            type="button"
                            data-bs-target="#couponCarousel"
                            data-bs-slide="prev">
                        <span class="coupon-carousel-btn__icon">
                            <i class="bi bi-chevron-left"></i>
                        </span>
                    </button>

                    <button class="carousel-control-next coupon-carousel-btn coupon-carousel-btn--next"
                            type="button"
                            data-bs-target="#couponCarousel"
                            data-bs-slide="next">
                        <span class="coupon-carousel-btn__icon">
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    </button>
                @endif
            </div>
        @else
            <div class="content-box text-center">
                Chưa có mã giảm giá nào.
            </div>
        @endif
    </div>
</section>

<section class="featured-section section-space">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">HomeKit</span>
            <h2>Các sản phẩm nổi bật</h2>
            <p>Sự kết hợp giữa chất liệu truyền thống và thiết kế hiện đại, tối giản, tinh tế.</p>
        </div>

        <div class="row g-4">
            @foreach($featuredProducts as $product)
                @php
                    $currentPrice = $product->sale_price ?? $product->price;
                    $discountPercent = 0;
                    if ($product->sale_price && $product->price > 0) {
                        $discountPercent = round((($product->price - $product->sale_price) / $product->price) * 100);
                    }
                @endphp

                <div class="col-md-6 col-lg-3">
                    <div class="product-card large-card">
                        @if($discountPercent > 0)
                            <div class="discount-badge">-{{ $discountPercent }}%</div>
                        @endif

                        <a href="{{ route('products.show', $product->slug) }}" class="product-image-link">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                            @else
                                <div class="product-image-placeholder"><i class="bi bi-image"></i></div>
                            @endif
                        </a>

                        <div class="product-body">
                            <div class="product-category">{{ $product->category->name ?? 'Sản phẩm' }}</div>

                            <h5 class="product-title">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            </h5>

                            <div class="product-price">
                                @if($product->sale_price)
                                    <span class="old-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                @endif
                                <span class="new-price">{{ number_format($currentPrice, 0, ',', '.') }}đ</span>
                            </div>

                            <p class="product-desc">
                                {{ \Illuminate\Support\Str::limit($product->short_description, 65) }}
                            </p>

                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-theme-soft flex-fill">
                                    Xem chi tiết
                                </a>

                                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-fill">
                                    @csrf
                                    <button class="btn btn-theme w-100">Thêm giỏ</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="service-strip section-space-sm">
    <div class="container">
        <div class="service-grid">
            <div class="service-item">
                <i class="bi bi-truck"></i>
                <div>
                    <h6>Giao hàng tận tâm</h6>
                    <p>Miễn phí, nhanh chóng, an toàn, hỗ trợ tận nơi.</p>
                </div>
            </div>
            <div class="service-item">
                <i class="bi bi-arrow-repeat"></i>
                <div>
                    <h6>Đổi trả 1 - 1</h6>
                    <p>Miễn phí, đổi mới dễ dàng, không lo rủi ro.</p>
                </div>
            </div>
            <div class="service-item">
                <i class="bi bi-shield-check"></i>
                <div>
                    <h6>Bảo hành chu đáo</h6>
                    <p>Sửa chữa miễn phí, nhanh chóng và đảm bảo dài lâu.</p>
                </div>
            </div>
            <div class="service-item">
                <i class="bi bi-headset"></i>
                <div>
                    <h6>Tư vấn decor</h6>
                    <p>Gợi ý cách bài trí tinh tế, hòa hợp cùng thiên nhiên.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="news-section section-space">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">HomeKit</span>
            <h2>Tin tức mới nhất</h2>
        </div>

        <div class="row g-4">
            @foreach($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="news-card">
                        <div class="news-image-wrap">
                            <a href="{{ route('news.show', $post->slug) }}">
                                @if($post->image)
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="news-image">
                                @else
                                    <div class="news-image-placeholder"><i class="bi bi-image"></i></div>
                                @endif
                            </a>
                        </div>

                        <div class="news-body">
                            <div class="news-meta">
                                <span class="news-date">{{ $post->created_at->format('d/m/Y') }}</span>
                                <span class="news-tag">Lifestyle</span>
                            </div>

                            <h5 class="news-title">
                                <a href="{{ route('news.show', $post->slug) }}" class="news-title-link">
                                    {{ $post->title }}
                                </a>
                            </h5>
                            <p class="news-desc">
                                {{ \Illuminate\Support\Str::limit($post->summary, 120) }}
                            </p>

                            <a href="{{ route('news.show', $post->slug) }}" class="read-more-link">
                                Đọc thêm <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('news.page') }}" class="btn btn-theme">Xem thêm</a>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.bestseller-section .product-card.bestseller-card {
    position: relative;
    border-radius: 18px;
    overflow: visible;
}

.bestseller-rank {
    position: absolute;
    top: 12px;
    right: 12px; 
    left: auto;
    z-index: 3;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
}

.rank-1 { background: #FAEEDA; color: #8a5e0a; border: 1.5px solid #EF9F27; }
.rank-2 { background: #e8e8e8; color: #4a4a4a; border: 1.5px solid #aaa; }
.rank-3 { background: #FAECE7; color: #7a3320; border: 1.5px solid #D85A30; }
.rank-other { background: #f0f0f0; color: #666; border: 1.5px solid #ccc; }

.bestseller-hot-badge {
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 4;
    background: #e53935;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 999px;
    white-space: nowrap;
    letter-spacing: 0.3px;
}

.bestseller-sold-info {
    font-size: 12px;
    color: #888;
    margin-bottom: 8px;
}

.bestseller-sold-info strong {
    color: #e53935;
}

.bestseller-section .product-card.bestseller-card:first-child {
    border-top: 3px solid #EF9F27;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.coupon-copy-btn');

    buttons.forEach(button => {
        button.addEventListener('click', async function () {
            const code = this.dataset.code;
            const originalText = this.textContent;

            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(code);
                } else {
                    const tempInput = document.createElement('textarea');
                    tempInput.value = code;
                    tempInput.style.position = 'fixed';
                    tempInput.style.left = '-9999px';
                    document.body.appendChild(tempInput);
                    tempInput.focus();
                    tempInput.select();
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);
                }

                this.textContent = 'Đã copy';
                this.classList.add('copied');

                setTimeout(() => {
                    this.textContent = originalText;
                    this.classList.remove('copied');
                }, 1500);
            } catch (error) {
                alert('Không thể copy mã. Mã của bạn là: ' + code);
            }
        });
    });
});
</script>
@endpush