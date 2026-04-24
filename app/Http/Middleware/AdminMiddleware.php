<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Vui lòng đăng nhập tại trang admin.');
        }

        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')
                ->with('error', 'Bạn không có quyền truy cập khu vực quản trị.');
        }

        if (session('login_area') !== 'admin') {
            return redirect()->route('account.index')
                ->with('error', 'Bạn đang đăng nhập ở chế độ người dùng. Muốn vào admin, hãy đăng xuất rồi đăng nhập lại tại /admin/login');
        }

        return $next($request);
    }
}