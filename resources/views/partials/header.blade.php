@php
    $cart = session('cart', []);
    $cartCount = collect($cart)->sum('quantity');
@endphp

<header>
    <div class="small d-flex align-items-center gap-3 flex-wrap">
        @auth
            <span>Xin chào, {{ auth()->user()->name }}</span>

            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-dark">
                    <i class="bi bi-speedometer2"></i> Admin
                </a>
            @endif

            <a href="{{ route('account.index') }}" class="text-decoration-none text-dark">
                <i class="bi bi-person"></i> Tài khoản
            </a>

            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-link p-0 text-decoration-none text-dark">
                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-decoration-none text-dark">
                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
            </a>

            <a href="{{ route('register') }}" class="text-decoration-none text-dark">
                <i class="bi bi-person-plus"></i> Đăng ký
            </a>
        @endauth

        <a href="{{ route('cart.index') }}" class="text-decoration-none text-dark">
            <i class="bi bi-bag"></i> Giỏ hàng
        </a>
    </div>

    <div class="main-header">
        <div class="container">
            <div class="row align-items-center gy-3">
                <div class="col-lg-3">
                    <a href="{{ route('home') }}" class="logo-wrap text-decoration-none">
                        <div class="logo-icon">
                            <i class="bi bi-flower1"></i>
                        </div>
                        <div>
                            <div class="logo-text">HomeKit</div>
                            <div class="logo-subtext">Từng chi tiết, gửi gắm yêu thương</div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-6">
                    <form action="{{ route('products.index') }}" method="GET" class="search-form">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                               placeholder="Tìm sản phẩm...">
                        <button type="submit" class="search-btn">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>

                <div class="col-lg-3">
                    <div class="header-actions">

                        @if(auth()->check())
                            <a href="{{ route('account.index') }}" class="header-action-item">
                                <i class="bi bi-person"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="header-action-item">
                                <i class="bi bi-box-arrow-in-right"></i>
                            </a>
                        @endif

                        <a href="{{ route('cart.index') }}" class="header-action-item cart-badge-wrap">
                            <i class="bi bi-bag"></i>
                            <span class="cart-badge">{{ $cartCount }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="menu-bar">
        <div class="container">
            <nav class="navbar navbar-expand-lg p-0">
                <div class="container-fluid px-0">
                    <a class="btn category-btn me-3" href="{{ route('products.index') }}">
                        <i class="bi bi-grid-3x3-gap-fill me-2"></i>Danh mục
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="mainMenu">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 main-nav">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Trang chủ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Về chúng tôi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Sản phẩm</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('collections') ? 'active' : '' }}" href="{{ route('collections') }}">Bộ sưu tập</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}" href="{{ route('faq') }}">Hỏi đáp</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('news.page') ? 'active' : '' }}" href="{{ route('news.page') }}">Tin tức</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Liên hệ</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>