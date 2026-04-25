<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RevenueExport implements FromCollection, WithHeadings
{
    public function __construct(protected Request $request)
    {
    }

    public function collection()
    {
        $validStatuses = ['confirmed', 'shipping', 'completed'];

        $statuses = [
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
        ];

        return Order::query()
            ->whereIn('status', $validStatuses)
            ->when($this->request->filled('from'), function ($query) {
                $query->whereDate('created_at', '>=', $this->request->from);
            })
            ->when($this->request->filled('to'), function ($query) {
                $query->whereDate('created_at', '<=', $this->request->to);
            })
            ->latest('id')
            ->get()
            ->map(function ($order) use ($statuses) {
                return [
                    'Mã đơn' => $order->id,
                    'Khách hàng' => $order->customer_name,
                    'SĐT' => $order->phone,
                    'Tạm tính' => $order->subtotal,
                    'Giảm giá' => $order->discount_amount,
                    'Phí ship' => $order->shipping_fee,
                    'Tổng tiền' => $order->total_amount,
                    'Trạng thái' => $statuses[$order->status] ?? $order->status,
                    'Ngày đặt' => $order->created_at->format('d/m/Y H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Mã đơn',
            'Khách hàng',
            'SĐT',
            'Tạm tính',
            'Giảm giá',
            'Phí ship',
            'Tổng tiền',
            'Trạng thái',
            'Ngày đặt',
        ];
    }
}