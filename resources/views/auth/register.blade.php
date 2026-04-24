<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
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

        .register-card {
            width: 100%;
            max-width: 520px;
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
    <div class="register-card">
        <div class="text-center mb-4">
            <h2 class="mb-1">Đăng ký tài khoản</h2>
            <p class="text-muted mb-0">Tạo tài khoản để theo dõi đơn hàng và quản lý thông tin cá nhân.</p>
        </div>

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Họ và tên</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn btn-theme w-100">Đăng ký</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none">Đã có tài khoản? Đăng nhập</a>
        </div>
    </div>
</body>
</html>