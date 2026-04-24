@extends('layouts.app')

@section('title', $post->title)

@section('content')
<section class="section-space">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <article class="content-box news-detail-box">
                    <div class="mb-3">
                        <a href="{{ route('news.page') }}" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Quay lại tin tức
                        </a>
                    </div>

                    <div class="news-meta mb-3">
                        <span class="news-date">{{ $post->created_at->format('d/m/Y') }}</span>
                        <span class="news-tag">Tin tức</span>
                    </div>

                    <h1 class="news-detail-title">{{ $post->title }}</h1>

                    @if($post->summary)
                        <p class="news-detail-summary">{{ $post->summary }}</p>
                    @endif

                    <div class="news-detail-image-wrap mb-4">
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}"
                                 alt="{{ $post->title }}"
                                 class="img-fluid rounded-4 news-detail-image">
                        @else
                            <div class="news-image-placeholder rounded-4">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </div>

                    <div class="news-detail-content">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </article>

                <div class="content-box mt-4">
                    <h3 class="mb-4">Bình luận bài viết</h3>

                    @if(session('success'))
                        <div class="alert alert-success mb-3">{{ session('success') }}</div>
                    @endif

                    @auth
                        <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-4">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Nội dung bình luận</label>
                                <textarea name="content" rows="4" class="form-control" placeholder="Nhập bình luận của bạn...">{{ old('content') }}</textarea>
                                @error('content')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button class="btn btn-theme">Gửi bình luận</button>
                        </form>
                    @else
                        <div class="alert alert-info">
                            Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận bài viết.
                        </div>
                    @endauth

                    <hr>

                    @forelse($post->comments as $comment)
                        <div class="comment-item {{ !$loop->last ? 'mb-4 pb-4 border-bottom' : '' }}">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="fw-semibold">{{ $comment->user->name ?? 'Người dùng' }}</div>
                                    <div class="text-muted small mb-2">{{ $comment->created_at->format('d/m/Y H:i') }}</div>
                                    <div>{{ $comment->content }}</div>
                                </div>

                                @auth
                                    @if($comment->user_id === auth()->id())
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Xóa bình luận này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Xóa</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Chưa có bình luận nào cho bài viết này.</p>
                    @endforelse
                </div>

                @if($relatedPosts->count())
                    <div class="content-box mt-4">
                        <h3 class="mb-4">Bài viết liên quan</h3>

                        <div class="row g-4">
                            @foreach($relatedPosts as $item)
                                <div class="col-md-6">
                                    <div class="news-card h-100">
                                        <div class="news-image-wrap">
                                            <a href="{{ route('news.show', $item->slug) }}">
                                                @if($item->image)
                                                    <img src="{{ asset('storage/' . $item->image) }}"
                                                         alt="{{ $item->title }}"
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
                                                <span class="news-date">{{ $item->created_at->format('d/m/Y') }}</span>
                                                <span class="news-tag">Tin tức</span>
                                            </div>

                                            <h5 class="news-title">
                                                <a href="{{ route('news.show', $item->slug) }}" class="news-title-link">
                                                    {{ $item->title }}
                                                </a>
                                            </h5>

                                            <p class="news-desc">
                                                {{ \Illuminate\Support\Str::limit($item->summary ?: strip_tags($item->content), 100) }}
                                            </p>

                                            <a href="{{ route('news.show', $item->slug) }}" class="read-more-link">
                                                Đọc thêm <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
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
                    <h4 class="mb-3">Đi đến nhanh</h4>
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-theme-soft">Xem sản phẩm</a>
                        <a href="{{ route('home') }}" class="btn btn-outline-dark">Về trang chủ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection