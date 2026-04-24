<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f6f3ee, #eadcc4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Segoe UI", Tahoma, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            background: #fff;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
        }

        .btn-theme {
            background: #c98a3d;
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 12px 20px;
            font-weight: 700;
        }

        .btn-theme:hover {
            background: #9b6b32;
            color: #fff;
        }

        .form-control {
            border-radius: 14px;
            min-height: 48px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <h2 class="mb-1">Đăng nhập</h2>
            <p class="text-muted mb-0">Đăng nhập để theo dõi đơn hàng và quản lý tài khoản.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" autocomplete="off">
            @csrf

            <input type="text" name="fake_username" style="display:none" autocomplete="username">
            <input type="password" name="fake_password" style="display:none" autocomplete="current-password">

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email"
                    name="login_email"
                    class="form-control"
                    value="{{ old('login_email') }}"
                    placeholder="Nhập email"
                    autocomplete="off"
                    readonly
                    onfocus="this.removeAttribute('readonly');">
                @error('login_email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password"
                    name="login_password"
                    class="form-control"
                    placeholder="Nhập mật khẩu"
                    autocomplete="new-password"
                    readonly
                    onfocus="this.removeAttribute('readonly');">
                @error('login_password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember_user">
                <label class="form-check-label" for="remember_user">
                    Ghi nhớ đăng nhập
                </label>
            </div>

            <button type="submit" class="btn btn-theme w-100">Đăng nhập</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('register') }}" class="text-decoration-none d-block mb-2">Chưa có tài khoản? Đăng ký</a>
            <a href="{{ route('home') }}" class="text-decoration-none">← Quay về trang chủ</a>
        </div>
    </div>
</body>
</html>