<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1f2937, #374151);
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
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.15);
        }

        .btn-theme {
            background: #1f2937;
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 12px 20px;
            font-weight: 700;
        }

        .btn-theme:hover {
            background: #111827;
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
            <h2 class="mb-1">Đăng nhập Admin</h2>
            <p class="text-muted mb-0">Truy cập khu vực quản trị hệ thống.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" autocomplete="off">
            @csrf

            <input type="text" name="fake_admin_username" style="display:none" autocomplete="username">
            <input type="password" name="fake_admin_password" style="display:none" autocomplete="current-password">

            <div class="mb-3">
                <label class="form-label">Email admin</label>
                <input type="email"
                    name="admin_email"
                    class="form-control"
                    value="{{ old('admin_email') }}"
                    placeholder="Nhập email admin"
                    autocomplete="off"
                    readonly
                    onfocus="this.removeAttribute('readonly');">
                @error('admin_email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu admin</label>
                <input type="password"
                    name="admin_password"
                    class="form-control"
                    placeholder="Nhập mật khẩu admin"
                    autocomplete="new-password"
                    readonly
                    onfocus="this.removeAttribute('readonly');">
                @error('admin_password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember_admin">
                <label class="form-check-label" for="remember_admin">
                    Ghi nhớ đăng nhập
                </label>
            </div>

            <button type="submit" class="btn btn-theme w-100">Đăng nhập Admin</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none d-block mb-2">Đăng nhập người dùng</a>
            <a href="{{ route('home') }}" class="text-decoration-none">← Quay về trang chủ</a>
        </div>
    </div>
</body>
</html>