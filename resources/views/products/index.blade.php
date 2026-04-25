@extends('layouts.app')

@section('title', 'Sản phẩm')

@section('content')
<section class="section-space">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">HomeKit</span>
            <h2>Danh sách sản phẩm</h2>
            <p>Tìm kiếm, lọc và sắp xếp sản phẩm theo nhu cầu của bạn.</p>
        </div>

        <div class="product-page-wrap">
            <div class="row g-4">
                <div class="col-lg-3">
                    <div class="filter-sidebar content-box">
                        <h4 class="mb-3">Bộ lọc sản phẩm</h4>

                        <form method="GET" action="{{ route('products.index') }}">
                            <div class="mb-3">
                                <label class="form-label">Từ khóa</label>
                                <input type="text"
                                       name="q"
                                       value="{{ request('q') }}"
                                       class="form-control"
                                       placeholder="Nhập tên sản phẩm...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Danh mục</label>
                                <select name="category" class="form-select">
                                    <option value="">-- Tất cả danh mục --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->slug }}"
                                            {{ request('category') == $category->slug ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Giá từ</label>
                                    <input type="number"
                                           name="min_price"
                                           value="{{ request('min_price') }}"
                                           class="form-control"
                                           placeholder="0">
                                </div>

                                <div class="col-6 mb-3">
                                    <label class="form-label">Giá đến</label>
                                    <input type="number"
                                           name="max_price"
                                           value="{{ request('max_price') }}"
                                           class="form-control"
                                           placeholder="9999999">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sắp xếp</label>
                                <select name="sort" class="form-select">
                                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                    <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Nổi bật trước</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-theme">Áp dụng bộ lọc</button>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-dark">Xóa bộ lọc</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="content-box mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h5 class="mb-1">Kết quả sản phẩm</h5>
                                <div class="text-muted">
                                    Tìm thấy <strong>{{ $products->total() }}</strong> sản phẩm
                                </div>
                            </div>

                            <div class="filter-shortcuts d-flex flex-wrap gap-2">
                                @if(request('q'))
                                    <span class="filter-chip">Từ khóa: {{ request('q') }}</span>
                                @endif

                                @if(request('category'))
                                    <span class="filter-chip">Danh mục: {{ request('category') }}</span>
                                @endif

                                @if(request('min_price'))
                                    <span class="filter-chip">Từ: {{ number_format(request('min_price'), 0, ',', '.') }}đ</span>
                                @endif

                                @if(request('max_price'))
                                    <span class="filter-chip">Đến: {{ number_format(request('max_price'), 0, ',', '.') }}đ</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        @forelse($products as $product)
                            @php
                                $currentPrice = $product->sale_price ?? $product->price;
                                $discountPercent = 0;
                                if ($product->sale_price && $product->price > 0) {
                                    $discountPercent = round((($product->price - $product->sale_price) / $product->price) * 100);
                                }
                            @endphp

                            <div class="col-md-6 col-xl-4">
                                <div class="product-card large-card h-100">
                                    @if($discountPercent > 0)
                                        <div class="discount-badge">-{{ $discountPercent }}%</div>
                                    @endif

                                    <a href="{{ route('products.show', $product->slug) }}" class="product-image-link">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="product-image">
                                        @else
                                            <div class="product-image-placeholder">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </a>

                                    <div class="product-body">
                                        <div class="product-category">
                                            {{ $product->category->name ?? 'Sản phẩm' }}
                                        </div>

                                        <h5 class="product-title">
                                            <a href="{{ route('products.show', $product->slug) }}">
                                                {{ $product->name }}
                                            </a>
                                        </h5>

                                        <div class="product-price">
                                            @if($product->sale_price)
                                                <span class="old-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                            @endif
                                            <span class="new-price">{{ number_format($currentPrice, 0, ',', '.') }}đ</span>
                                        </div>

                                        <p class="product-desc">
                                            {{ \Illuminate\Support\Str::limit($product->short_description, 70) }}
                                        </p>

                                        <div class="mt-3 d-flex gap-2">
                                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-theme-soft flex-fill">
                                                Chi tiết
                                            </a>

                                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-fill">
                                                @csrf
                                                <button class="btn btn-theme w-100">Thêm giỏ</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="content-box text-center">
                                    <h4>Không tìm thấy sản phẩm phù hợp</h4>
                                    <p class="text-muted mb-3">
                                        Hãy thử thay đổi từ khóa, khoảng giá hoặc danh mục.
                                    </p>
                                    <a href="{{ route('products.index') }}" class="btn btn-theme">Xem tất cả sản phẩm</a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if($products->hasPages())
                        <div class="mt-4">
                            {{ $products->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection