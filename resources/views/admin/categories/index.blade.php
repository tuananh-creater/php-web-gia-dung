@extends('layouts.admin')

@section('title', 'Quản lý danh mục')

@section('content')

<form method="GET" action="{{ route('admin.categories.index') }}" class="row g-3 mb-4">
    <div class="col-md-8">
        <input type="text"
               name="keyword"
               class="form-control"
               value="{{ request('keyword') }}"
               placeholder="Tìm theo tên, slug hoặc mô tả danh mục">
    </div>

    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">-- Trạng thái --</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
        </select>
    </div>

    <div class="col-md-2 d-flex gap-2">
        <button class="btn btn-primary w-100">Lọc</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary w-100">Reset</a>
    </div>
</form>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Quản lý danh mục</h2>
        <p class="text-muted mb-0">Danh sách toàn bộ danh mục sản phẩm.</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Thêm danh mục
    </a>
</div>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th width="70">ID</th>
                <th width="100">Ảnh</th>
                <th>Tên danh mục</th>
                <th>Slug</th>
                <th>Trạng thái</th>
                <th width="180">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" width="70" height="70" alt="{{ $category->name }}">
                    @else
                        <span class="text-muted">Không có</span>
                    @endif
                </td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>
                    @if($category->status)
                        <span class="badge bg-success">Hiển thị</span>
                    @else
                        <span class="badge bg-secondary">Ẩn</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i> Sửa
                    </a>

                    <form action="{{ route('admin.categories.destroy', $category) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
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
                <td colspan="6" class="text-center text-muted">Chưa có danh mục nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $categories->links() }}
</div>
@endsection