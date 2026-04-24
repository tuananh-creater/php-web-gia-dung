<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\PostComment;
use App\Models\ProductReview;
use App\Models\OrderItem;
use App\Models\StockImport;

class DashboardController extends Controller
{
    public function index()
    {
        $revenueStatuses = ['completed'];

        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::whereIn('status', $revenueStatuses)->sum('total_amount');
        $totalImportCost = StockImport::sum('total_cost');
        $soldItems = OrderItem::whereHas('order', function ($q) use ($revenueStatuses) {
                $q->whereIn('status', $revenueStatuses);
            })
            ->whereHas('product')
            ->with(['product', 'order'])
            ->get();

        $totalCost = $soldItems->sum(fn($item) => $item->quantity * $item->product->cost_price);
        $profit = $totalRevenue - $totalCost;
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $today = Carbon::today();

        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todayRevenue = Order::whereIn('status', $revenueStatuses)
            ->whereBetween('updated_at', [
                Carbon::today()->startOfDay(),
                Carbon::today()->endOfDay()
            ])
            ->sum('total_amount');
        $todayOrdersQuery = Order::where('status', 'completed')
            ->whereBetween('updated_at', [
                $today->copy()->startOfDay(),
                $today->copy()->endOfDay()
            ]);
        $todayRevenue = $todayOrdersQuery->sum('total_amount');
        $todayOrderItems = OrderItem::whereHas('order', function ($q) use ($today) {
                $q->where('status', 'completed')
                ->whereBetween('updated_at', [
                    $today->copy()->startOfDay(),
                    $today->copy()->endOfDay()
                ]);
            })
            ->with('product')
            ->get();
        $todayCost = $todayOrderItems->sum(function ($item) {
            return $item->quantity * $item->product->cost_price;
        });
        $todayProfit = $todayRevenue - $todayCost;

        $totalReviews = ProductReview::count();
        $totalComments = PostComment::count();
        $averageProductRating = round((float) ProductReview::avg('rating'), 1);

        $todayReviews = ProductReview::whereDate('created_at', $today)->count();
        $todayComments = PostComment::whereDate('created_at', $today)->count();

        $latestReviews = ProductReview::with(['user', 'product'])
            ->latest('id')
            ->take(5)
            ->get();

        $latestComments = PostComment::with(['user', 'post'])
            ->latest('id')
            ->take(5)
            ->get();

        $newContactsCount = Contact::where('status', 'new')->count();

        $latestOrders = Order::query()
            ->withCount('items')
            ->latest('id')
            ->take(8)
            ->get();

        $lowStockProducts = Product::query()
            ->with('category')
            ->where('status', 1)
            ->where('quantity', '<=', 5)
            ->orderBy('quantity')
            ->take(8)
            ->get();

        $bestSellingProducts = OrderItem::whereHas('order', function ($q) use ($revenueStatuses) {
                $q->whereIn('status', $revenueStatuses);
            })
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->take(5)
            ->get();

        $chartStartDate = Carbon::today()->subDays(6);

        $revenueData = Order::query()
            ->selectRaw('DATE(created_at) as order_date, SUM(total_amount) as total_revenue')
            ->whereIn('status', $revenueStatuses)
            ->whereDate('created_at', '>=', $chartStartDate)
            ->groupBy('order_date')
            ->orderBy('order_date')
            ->pluck('total_revenue', 'order_date')
            ->toArray();

        $orderCountData = Order::query()
            ->selectRaw('DATE(created_at) as order_date, COUNT(*) as total_orders')
            ->whereDate('created_at', '>=', $chartStartDate)
            ->groupBy('order_date')
            ->orderBy('order_date')
            ->pluck('total_orders', 'order_date')
            ->toArray();

        $dailyCostData = $soldItems
            ->filter(fn($item) => $item->order && $item->order->created_at >= $chartStartDate)
            ->groupBy(fn($item) => $item->order->created_at->format('Y-m-d'))
            ->map(fn($items) => $items->sum(fn($i) => $i->quantity * $i->product->cost_price))
            ->toArray();

        $chartLabels = [];
        $chartRevenueValues = [];
        $chartOrderValues = [];
        $chartCostValues = [];
        $chartProfitValues = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $chartStartDate->copy()->addDays($i);
            $key = $date->format('Y-m-d');

            $dailyRevenue = (float) ($revenueData[$key] ?? 0);
            $dailyCost    = (float) ($dailyCostData[$key] ?? 0);

            $chartLabels[]       = $date->format('d/m');
            $chartRevenueValues[]= $dailyRevenue;
            $chartOrderValues[]  = (int) ($orderCountData[$key] ?? 0);
            $chartCostValues[]   = $dailyCost;
            $chartProfitValues[] = $dailyRevenue - $dailyCost;
        }

        $statuses = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        return view('admin.dashboard', compact(
            'totalCategories',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'totalCost',
            'profit',
            'bestSellingProducts',
            'pendingOrders',
            'completedOrders',
            'cancelledOrders',
            'todayOrders',
            'todayRevenue',
            'todayRevenue',
            'todayCost',
            'todayProfit',
            'newContactsCount',
            'latestOrders',
            'lowStockProducts',
            'chartLabels',
            'chartRevenueValues',
            'chartOrderValues',
            'chartCostValues',
            'chartProfitValues',
            'statuses',
            'totalReviews',
            'totalComments',
            'averageProductRating',
            'todayReviews',
            'todayComments',
            'latestReviews',
            'latestComments'
        ));
    }
}