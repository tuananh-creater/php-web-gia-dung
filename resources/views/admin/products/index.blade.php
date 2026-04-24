@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')

<form method="GET" action="{{ route('admin.products.index') }}" class="row g-3 mb-4">
    <div class="col-md-4">
        <input type="text"
               name="keyword"
               class="form-control"
               value="{{ request('keyword') }}"
               placeholder="Tìm tên, slug, mô tả sản phẩm">
    </div>

    <div class="col-md-3">
        <select name="category_id" class="form-select">
            <option value="">-- Tất cả danh mục --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">-- Trạng thái --</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
        </select>
    </div>

    <div class="col-md-2">
        <select name="is_featured" class="form-select">
            <option value="">-- Nổi bật --</option>
            <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>Có</option>
            <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>Không</option>
        </select>
    </div>

    <div class="col-md-1 d-flex gap-2">
        <button class="btn btn-primary w-100">Lọc</button>
    </div>

    <div class="col-md-12">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Reset bộ lọc</a>
    </div>
</form>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Quản lý sản phẩm</h2>
        <p class="text-muted mb-0">Danh sách toàn bộ sản phẩm trong hệ thống.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Thêm sản phẩm
    </a>
</div>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th width="70">ID</th>
                <th width="100">Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>SL</th>
                <th>Nổi bật</th>
                <th>Trạng thái</th>
                <th width="180">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" width="70" height="70" alt="{{ $product->name }}">
                    @else
                        <span class="text-muted">Không có</span>
                    @endif
                </td>
                <td>
                    <div class="fw-semibold">{{ $product->name }}</div>
                    <small class="text-muted">{{ $product->slug }}</small>
                </td>
                <td>{{ $product->category->name ?? '---' }}</td>
                <td>
                    <div>Giá: {{ number_format($product->price, 0, ',', '.') }}đ</div>
                    <div class="text-danger">
                        KM: {{ number_format($product->sale_price ?? 0, 0, ',', '.') }}đ
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-theme-soft flex-fill">Chi tiết</a>

                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-fill">
                            @csrf
                            <button class="btn btn-theme w-100">Thêm giỏ</button>
                        </form>
                    </div>
                </td>
                <td>{{ $product->quantity }}</td>
                <td>
                    @if($product->is_featured)
                        <span class="badge bg-info text-dark">Có</span>
                    @else
                        <span class="badge bg-secondary">Không</span>
                    @endif
                </td>
                <td>
                    @if($product->status)
                        <span class="badge bg-success">Hiển thị</span>
                    @else
                        <span class="badge bg-secondary">Ẩn</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i> Sửa
                    </a>

                    <form action="{{ route('admin.products.destroy', $product) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
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
                <td colspan="9" class="text-center text-muted">Chưa có sản phẩm nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $products->links() }}
</div>
@endsection