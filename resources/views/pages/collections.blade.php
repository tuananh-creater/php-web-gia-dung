@extends('layouts.app')

@section('title', 'Bộ sưu tập')

@section('content')
<section class="section-space">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">HomeKit</span>
            <h2>Bộ sưu tập</h2>
            <p>Khám phá các nhóm sản phẩm được tuyển chọn theo từng phong cách và công năng sử dụng.</p>
        </div>

        <div class="row g-4 mb-4">
            @forelse($featuredCollections as $collection)
                <div class="col-md-6 col-lg-4">
                    <div class="collection-highlight-card">
                        <div class="collection-highlight-thumb">
                            @if($collection->image)
                                <img src="{{ asset('storage/' . $collection->image) }}"
                                     alt="{{ $collection->name }}"
                                     class="img-fluid">
                            @else
                                <div class="collection-thumb-placeholder">
                                    <i class="bi bi-grid"></i>
                                </div>
                            @endif
                        </div>

                        <div class="collection-highlight-body">
                            <h4>{{ $collection->name }}</h4>
                            <p class="text-muted mb-2">
                                {{ $collection->description ?: 'Bộ sưu tập mang cảm hứng thủ công và tự nhiên.' }}
                            </p>
                            <div class="small text-muted mb-3">{{ $collection->products_count }} sản phẩm</div>

                            <a href="{{ route('collections', ['category' => $collection->slug]) }}" class="btn btn-theme-soft">
                                Xem bộ sưu tập
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="content-box text-center">
                        Chưa có bộ sưu tập nào.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="content-box mb-4">
            <div class="d-flex flex-wrap gap-2">
                @foreach($categories as $category)
                    <a href="{{ route('collections', ['category' => $category->slug]) }}"
                       class="collection-filter-link {{ $selectedCategory && $selectedCategory->id === $category->id ? 'active' : '' }}">
                        {{ $category->name }} ({{ $category->products_count }})
                    </a>
                @endforeach
            </div>
        </div>

        @if($selectedCategory)
            <div class="content-box mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h3 class="mb-1">{{ $selectedCategory->name }}</h3>
                        <p class="text-muted mb-0">
                            {{ $selectedCategory->description ?: 'Các sản phẩm nổi bật trong bộ sưu tập này.' }}
                        </p>
                    </div>

                    <a href="{{ route('products.index', ['category' => $selectedCategory->slug]) }}" class="btn btn-outline-dark">
                        Xem toàn bộ sản phẩm
                    </a>
                </div>
            </div>
        @endif

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
                        <h4>Danh mục này chưa có sản phẩm</h4>
                        <p class="text-muted mb-3">Bạn có thể chọn bộ sưu tập khác hoặc quay lại trang sản phẩm.</p>
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
</section>
@endsection