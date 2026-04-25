<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public function __construct(protected Request $request)
    {
    }

    public function collection()
    {
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

        return Order::query()
            ->withCount('items')
            ->when($this->request->filled('keyword'), function ($query) {
                $keyword = trim($this->request->keyword);

                $query->where(function ($subQuery) use ($keyword) {
                    if (is_numeric($keyword)) {
                        $subQuery->orWhere('id', $keyword);
                    }

                    $subQuery->orWhere('customer_name', 'like', '%' . $keyword . '%')
                        ->orWhere('phone', 'like', '%' . $keyword . '%')
                        ->orWhere('email', 'like', '%' . $keyword . '%');
                });
            })
            ->when($this->request->filled('status'), function ($query) {
                $query->where('status', $this->request->status);
            })
            ->when($this->request->filled('payment_method'), function ($query) {
                $query->where('payment_method', $this->request->payment_method);
            })
            ->latest('id')
            ->get()
            ->map(function ($order) use ($statuses, $paymentMethods) {
                return [
                    'Mã đơn' => $order->id,
                    'Khách hàng' => $order->customer_name,
                    'Email' => $order->email,
                    'SĐT' => $order->phone,
                    'Địa chỉ' => $order->address,
                    'Số SP' => $order->items_count,
                    'Tạm tính' => $order->subtotal,
                    'Giảm giá' => $order->discount_amount,
                    'Phí ship' => $order->shipping_fee,
                    'Tổng tiền' => $order->total_amount,
                    'Thanh toán' => $paymentMethods[$order->payment_method] ?? $order->payment_method,
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
            'Email',
            'SĐT',
            'Địa chỉ',
            'Số SP',
            'Tạm tính',
            'Giảm giá',
            'Phí ship',
            'Tổng tiền',
            'Thanh toán',
            'Trạng thái',
            'Ngày đặt',
        ];
    }
}