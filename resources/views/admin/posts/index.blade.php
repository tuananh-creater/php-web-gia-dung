@extends('layouts.admin')

@section('title', 'Quản lý bài viết')

@section('content')

<form method="GET" action="{{ route('admin.posts.index') }}" class="row g-3 mb-4">
    <div class="col-md-9">
        <input type="text"
               name="keyword"
               class="form-control"
               value="{{ request('keyword') }}"
               placeholder="Tìm tiêu đề, slug, tóm tắt hoặc nội dung bài viết">
    </div>

    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">-- Trạng thái --</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
        </select>
    </div>

    <div class="col-md-1">
        <button class="btn btn-primary w-100">Lọc</button>
    </div>

    <div class="col-12">
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Quản lý bài viết / tin tức</h2>
        <p class="text-muted mb-0">Danh sách bài viết hiển thị ở mục tin tức.</p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Thêm bài viết
    </a>
</div>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th width="100">Ảnh</th>
            <th>Tiêu đề</th>
            <th>Slug</th>
            <th>Tóm tắt</th>
            <th>Trạng thái</th>
            <th width="180">Thao tác</th>
        </tr>
        </thead>
        <tbody>
        @forelse($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" width="70" height="70" alt="{{ $post->title }}">
                    @else
                        <span class="text-muted">Không có</span>
                    @endif
                </td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->slug }}</td>
                <td>{{ \Illuminate\Support\Str::limit($post->summary, 80) }}</td>
                <td>
                    @if($post->status)
                        <span class="badge bg-success">Hiển thị</span>
                    @else
                        <span class="badge bg-secondary">Ẩn</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i> Sửa
                    </a>

                    <form action="{{ route('admin.posts.destroy', $post) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')">
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
                <td colspan="7" class="text-center text-muted">Chưa có bài viết nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $posts->links() }}
</div>
@endsection