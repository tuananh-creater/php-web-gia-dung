<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ContactsExport;
use App\Exports\OrdersExport;
use App\Exports\RevenueExport;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function ordersExcel(Request $request)
    {
        return Excel::download(
            new OrdersExport($request),
            'don-hang-' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function ordersPdf(Request $request)
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
            ->get();

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

        $pdf = Pdf::loadView('admin.exports.orders-pdf', compact('orders', 'statuses', 'paymentMethods'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('don-hang-' . now()->format('Ymd_His') . '.pdf');
    }

    public function contactsExcel(Request $request)
    {
        return Excel::download(
            new ContactsExport($request),
            'lien-he-' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function contactsPdf(Request $request)
    {
        $contacts = Contact::query()
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = trim($request->keyword);

                $query->where(function ($subQuery) use ($keyword) {
                    if (is_numeric($keyword)) {
                        $subQuery->orWhere('id', $keyword);
                    }

                    $subQuery->orWhere('name', 'like', '%' . $keyword . '%')
                        ->orWhere('email', 'like', '%' . $keyword . '%')
                        ->orWhere('phone', 'like', '%' . $keyword . '%')
                        ->orWhere('subject', 'like', '%' . $keyword . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest('id')
            ->get();

        $statuses = [
            'new' => 'Mới',
            'read' => 'Đã đọc',
            'replied' => 'Đã phản hồi',
        ];

        $pdf = Pdf::loadView('admin.exports.contacts-pdf', compact('contacts', 'statuses'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('lien-he-' . now()->format('Ymd_His') . '.pdf');
    }

    public function revenueExcel(Request $request)
    {
        return Excel::download(
            new RevenueExport($request),
            'bao-cao-doanh-thu-' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function revenuePdf(Request $request)
    {
        $validStatuses = ['confirmed', 'shipping', 'completed'];

        $from = $request->input('from');
        $to = $request->input('to');

        $orders = Order::query()
            ->whereIn('status', $validStatuses)
            ->when($from, function ($query) use ($from) {
                $query->whereDate('created_at', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate('created_at', '<=', $to);
            })
            ->latest('id')
            ->get();

        $summary = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_amount'),
            'total_discount' => $orders->sum('discount_amount'),
            'total_shipping' => $orders->sum('shipping_fee'),
        ];

        $statuses = [
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
        ];

        $pdf = Pdf::loadView('admin.exports.revenue-pdf', compact('orders', 'summary', 'from', 'to', 'statuses'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('bao-cao-doanh-thu-' . now()->format('Ymd_His') . '.pdf');
    }
}