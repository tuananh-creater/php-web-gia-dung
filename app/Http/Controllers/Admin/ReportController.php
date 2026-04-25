<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $validStatuses = ['confirmed', 'shipping', 'completed'];

        $baseQuery = Order::query()
            ->whereIn('status', $validStatuses);

        if ($from) {
            $baseQuery->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $baseQuery->whereDate('created_at', '<=', $to);
        }

        $totalOrders = (clone $baseQuery)->count();
        $totalRevenue = (clone $baseQuery)->sum('total_amount');
        $totalDiscount = (clone $baseQuery)->sum('discount_amount');
        $totalShipping = (clone $baseQuery)->sum('shipping_fee');

        $ordersByStatus = Order::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->when($from, function ($query) use ($from) {
                $query->whereDate('created_at', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate('created_at', '<=', $to);
            })
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $dailyRevenue = Order::query()
            ->selectRaw('DATE(created_at) as order_date, SUM(total_amount) as revenue')
            ->whereIn('status', $validStatuses)
            ->when($from, function ($query) use ($from) {
                $query->whereDate('created_at', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate('created_at', '<=', $to);
            })
            ->groupBy('order_date')
            ->orderBy('order_date')
            ->get();

        $latestOrders = Order::query()
            ->withCount('items')
            ->latest('id')
            ->take(10)
            ->get();

        $statuses = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        return view('admin.reports.revenue', compact(
            'from',
            'to',
            'totalOrders',
            'totalRevenue',
            'totalDiscount',
            'totalShipping',
            'ordersByStatus',
            'dailyRevenue',
            'latestOrders',
            'statuses'
        ));
    }
}