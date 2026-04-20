@extends('layouts.app')

@section('title', 'Tài khoản cá nhân')

@section('content')
<section class="section-space">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="content-box">
                    <h3 class="mb-3">Tài khoản của tôi</h3>
                    <p><strong>Họ tên:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Vai trò:</strong> {{ $user->isAdmin() ? 'Admin' : 'Người dùng' }}</p>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="{{ route('account.orders') }}" class="btn btn-theme">Lịch sử đơn hàng</a>

                        @if($user->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">Vào trang quản trị</a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-danger w-100">Đăng xuất</button>
                        </form>
                    </div>
                </div>

                <div class="content-box mt-4">
                    <h4 class="mb-3">Thống kê đơn hàng</h4>
                    <p><strong>Tổng đơn:</strong> {{ $orderStats['total'] }}</p>
                    <p><strong>Chờ xác nhận:</strong> {{ $orderStats['pending'] }}</p>
                    <p><strong>Hoàn thành:</strong> {{ $orderStats['completed'] }}</p>
                    <p><strong>Đã hủy:</strong> {{ $orderStats['cancelled'] }}</p>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="content-box">
                    <h3 class="mb-3">Cập nhật thông tin cá nhân</h3>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('account.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Họ tên</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" name="password" class="form-control" placeholder="Để trống nếu không đổi">
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <button class="btn btn-theme">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection