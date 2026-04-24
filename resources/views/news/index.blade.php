@extends('layouts.app')

@section('title', 'Tin tức')

@section('content')
<section class="section-space">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">HomeKit</span>
            <h2>Tin tức & bài viết</h2>
            <p>Cập nhật xu hướng decor, mây tre đan và các mẹo làm đẹp không gian sống.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="content-box mb-4">
                    <form method="GET" action="{{ route('news.page') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-9">
                                <label class="form-label">Tìm bài viết</label>
                                <input type="text"
                                       name="q"
                                       value="{{ request('q') }}"
                                       class="form-control"
                                       placeholder="Nhập từ khóa bài viết...">
                            </div>

                            <div class="col-md-3">
                                <button class="btn btn-theme w-100">Tìm kiếm</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="row g-4">
                    @forelse($posts as $post)
                        <div class="col-md-6">
                            <article class="news-card h-100">
                                <div class="news-image-wrap">
                                    <a href="{{ route('news.show', $post->slug) }}">
                                        @if($post->image)
                                            <img src="{{ asset('storage/' . $post->image) }}"
                                                 alt="{{ $post->title }}"
                                                 class="news-image">
                                        @else
                                            <div class="news-image-placeholder">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </a>
                                </div>

                                <div class="news-body">
                                    <div class="news-meta">
                                        <span class="news-date">{{ $post->created_at->format('d/m/Y') }}</span>
                                        <span class="news-tag">Tin tức</span>
                                    </div>

                                    <h5 class="news-title">
                                        <a href="{{ route('news.show', $post->slug) }}" class="news-title-link">
                                            {{ $post->title }}
                                        </a>
                                    </h5>

                                    <p class="news-desc">
                                        {{ \Illuminate\Support\Str::limit($post->summary ?: strip_tags($post->content), 140) }}
                                    </p>

                                    <a href="{{ route('news.show', $post->slug) }}" class="read-more-link">
                                        Xem chi tiết <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="content-box text-center">
                                <h4>Chưa có bài viết phù hợp</h4>
                                <p class="text-muted mb-3">Hãy thử tìm với từ khóa khác.</p>
                                <a href="{{ route('news.page') }}" class="btn btn-theme">Xem tất cả bài viết</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($posts->hasPages())
                    <div class="mt-4">
                        {{ $posts->links('vendor.pagination.custom') }}
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="content-box mb-4">
                    <h4 class="mb-3">Bài viết mới nhất</h4>

                    @forelse($latestPosts as $item)
                        <div class="latest-news-item {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                            <a href="{{ route('news.show', $item->slug) }}" class="latest-news-link">
                                <div class="fw-semibold">{{ $item->title }}</div>
                                <small class="text-muted">{{ $item->created_at->format('d/m/Y') }}</small>
                            </a>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Chưa có bài viết nào.</p>
                    @endforelse
                </div>

                <div class="content-box">
                    <h4 class="mb-3">Chuyên mục</h4>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="filter-chip">Decor</span>
                        <span class="filter-chip">Mây tre đan</span>
                        <span class="filter-chip">Nội thất</span>
                        <span class="filter-chip">Phong cách sống</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection