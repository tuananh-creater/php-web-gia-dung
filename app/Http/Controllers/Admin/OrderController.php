<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->withCount('items')
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = trim($request->keyword);

                $query->where(function ($subQuery) use ($keyword) {
                    if (is_numeric($keyword)) {
                        $subQuery->orWhere('id', $keyword);
                    }

                    $subQuery->orWhere('customer_name', 'like', '%' . $keyword . '%')
                        ->orWhere('phone', 'like', '%' . $keyword . '%')
                        ->orWhere('email', 'like', '%' . $keyword . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('payment_method'), function ($query) use ($request) {
                $query->where('payment_method', $request->payment_method);
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

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

        return view('admin.orders.index', compact('orders', 'statuses', 'paymentMethods'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product']);

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

        return view('admin.orders.show', compact('order', 'statuses', 'paymentMethods'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,shipping,completed,cancelled'],
        ], [
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        DB::beginTransaction();

        try {
            $order->load('items');

            $oldStatus = $order->status;
            $newStatus = $validated['status'];

            if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled' && !$order->restocked) {
                foreach ($order->items as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);

                    if ($product) {
                        $product->increment('quantity', $item->quantity);
                    }
                }

                $order->restocked = true;
            }

            $order->status = $newStatus;
            $order->save();

            DB::commit();

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Có lỗi xảy ra khi cập nhật đơn hàng: ' . $e->getMessage());
        }
    }
}