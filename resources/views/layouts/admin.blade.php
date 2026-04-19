@php
    $newAdminContactsCount = \App\Models\Contact::where('status', 'new')->count();
@endphp

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản trị') - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
        }

        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 16.666667%;
            height: 100vh;
            overflow-y: auto;

            background: #1f2937;
            color: #fff;
            padding: 24px 16px;
        }

        .admin-content-wrap {
            margin-left: 16.666667%;
        }

        .admin-brand {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 24px;
        }

        .admin-sidebar a {
            display: block;
            color: #d1d5db;
            text-decoration: none;
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 8px;
        }

        .admin-sidebar a:hover,
        .admin-sidebar a.active {
            background: #374151;
            color: #fff;
        }

        .admin-content {
            padding: 24px;
        }

        .content-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .table img {
            border-radius: 10px;
            object-fit: cover;
        }
    </style>

    @stack('styles')
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-2 p-0 align-self-start">
            <aside class="admin-sidebar">
                <div class="admin-brand">Admin Panel</div>

                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Tổng quan
                </a>

                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-grid me-2"></i> Danh mục
                </a>

                <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam me-2"></i> Sản phẩm
                </a>

                <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt me-2"></i> Đơn hàng
                </a>

                <a href="{{ route('admin.reports.revenue') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line me-2"></i> Báo cáo doanh thu
                </a>

                <a href="{{ route('admin.banners.index') }}" class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <i class="bi bi-images me-2"></i> Banner
                </a>

                <a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated me-2"></i> Mã giảm giá
                </a>

                <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                    <i class="bi bi-newspaper me-2"></i> Bài viết
                </a>

                <a href="{{ route('admin.contacts.index') }}" class="{{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope-paper me-2"></i> Liên hệ
                    @if($newAdminContactsCount > 0)
                        <span class="badge bg-danger ms-2">{{ $newAdminContactsCount }}</span>
                    @endif
                </a>

                <a href="{{ route('home') }}">
                    <i class="bi bi-house-door me-2"></i> Về trang chủ
                </a>

                <form action="{{ route('logout') }}" method="POST" class="mt-3">
                    @csrf
                    <button class="btn btn-danger w-100">
                        <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                    </button>
                </form>
            </aside>
        </div>

        <div class="col-lg-10 admin-content-wrap">
            <div class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="content-card">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>