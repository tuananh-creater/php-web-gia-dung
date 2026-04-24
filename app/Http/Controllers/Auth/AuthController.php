<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showUserLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (session('login_area') === 'admin' && Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('account.index');
        }

        return view('auth.login');
    }

    public function showAdminLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (session('login_area') === 'admin' && Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('account.index')
                ->with('error', 'Bạn đang đăng nhập ở chế độ người dùng. Muốn vào admin, hãy đăng xuất rồi đăng nhập lại tại /admin/login');
        }

        return view('auth.admin-login');
    }

    public function showRegisterForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (session('login_area') === 'admin' && Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('account.index');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(6)],
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã đăng ký.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('login_area', 'user');

        return redirect()
            ->route('account.index')
            ->with('success', 'Đăng ký tài khoản thành công.');
    }

    public function loginUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login_email' => ['required', 'email'],
            'login_password' => ['required', 'string'],
        ], [
            'login_email.required' => 'Vui lòng nhập email.',
            'login_email.email' => 'Email không đúng định dạng.',
            'login_password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $credentials = [
            'email' => $data['login_email'],
            'password' => $data['login_password'],
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'login_email' => 'Email hoặc mật khẩu không đúng.',
                ])
                ->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put('login_area', 'user');

        return redirect()->route('account.index');
    }

    public function loginAdmin(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'admin_email' => ['required', 'email'],
            'admin_password' => ['required', 'string'],
        ], [
            'admin_email.required' => 'Vui lòng nhập email.',
            'admin_email.email' => 'Email không đúng định dạng.',
            'admin_password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $user = User::where('email', $data['admin_email'])->first();

        if (!$user || !$user->isAdmin()) {
            return back()
                ->withErrors([
                    'admin_email' => 'Tài khoản này không có quyền truy cập admin.',
                ])
                ->withInput();
        }

        $credentials = [
            'email' => $data['admin_email'],
            'password' => $data['admin_password'],
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'admin_email' => 'Email hoặc mật khẩu không đúng.',
                ])
                ->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put('login_area', 'admin');

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->forget('login_area');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Đăng xuất thành công.');
    }
}