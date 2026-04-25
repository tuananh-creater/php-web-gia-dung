<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $orderStats = [
            'total' => $user->orders()->count(),
            'pending' => $user->orders()->where('status', 'pending')->count(),
            'completed' => $user->orders()->where('status', 'completed')->count(),
            'cancelled' => $user->orders()->where('status', 'cancelled')->count(),
        ];

        return view('account.index', compact('user', 'orderStats'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return back()->with('success', 'Cập nhật tài khoản thành công.');
    }

    public function orders(): View
    {
        $orders = Auth::user()
            ->orders()
            ->withCount('items')
            ->latest('id')
            ->paginate(10);

        $statuses = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        return view('account.orders', compact('orders', 'statuses'));
    }

    public function showOrder(Order $order): View
    {
        abort_if($order->user_id !== Auth::id(), 403);

        $order->load('items.product');

        $statuses = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        $paymentMethods = [
            'cod' => 'Thanh toán khi nhận hàng',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
        ];

        return view('account.order-show', compact('order', 'statuses', 'paymentMethods'));
    }
}