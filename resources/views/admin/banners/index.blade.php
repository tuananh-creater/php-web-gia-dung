@extends('layouts.admin')

@section('title', 'Quản lý banner')

@section('content')

<form method="GET" action="{{ route('admin.banners.index') }}" class="row g-3 mb-4">
    <div class="col-md-9">
        <input type="text"
               name="keyword"
               class="form-control"
               value="{{ request('keyword') }}"
               placeholder="Tìm tiêu đề, phụ đề hoặc link banner">
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
        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Quản lý banner</h2>
        <p class="text-muted mb-0">Danh sách banner hiển thị ở trang chủ.</p>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Thêm banner
    </a>
</div>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th width="110">Ảnh</th>
            <th>Tiêu đề</th>
            <th>Phụ đề</th>
            <th>Link</th>
            <th>Thứ tự</th>
            <th>Trạng thái</th>
            <th width="180">Thao tác</th>
        </tr>
        </thead>
        <tbody>
        @forelse($banners as $banner)
            <tr>
                <td>{{ $banner->id }}</td>
                <td>
                    @if($banner->image)
                        <img src="{{ asset('storage/' . $banner->image) }}" width="80" height="60" alt="{{ $banner->title }}">
                    @else
                        <span class="text-muted">Không có</span>
                    @endif
                </td>
                <td>{{ $banner->title }}</td>
                <td>{{ $banner->subtitle }}</td>
                <td>{{ $banner->link }}</td>
                <td>{{ $banner->sort_order }}</td>
                <td>
                    @if($banner->status)
                        <span class="badge bg-success">Hiển thị</span>
                    @else
                        <span class="badge bg-secondary">Ẩn</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i> Sửa
                    </a>

                    <form action="{{ route('admin.banners.destroy', $banner) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Bạn có chắc muốn xóa banner này?')">
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
                <td colspan="8" class="text-center text-muted">Chưa có banner nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $banners->links() }}
</div>
@endsection