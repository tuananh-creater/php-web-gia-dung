<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'CRAFT200',
                'title' => 'Giảm 200k giá trị đơn hàng',
                'discount_type' => 'fixed',
                'discount_value' => 200000,
                'min_order_value' => 1000000,
                'expired_at' => '2026-12-31',
                'status' => 1,
            ],
            [
                'code' => 'CRAFT100',
                'title' => 'Giảm 100k giá trị đơn hàng',
                'discount_type' => 'fixed',
                'discount_value' => 100000,
                'min_order_value' => 700000,
                'expired_at' => '2026-12-31',
                'status' => 1,
            ],
            [
                'code' => 'CRAFT50',
                'title' => 'Giảm 50k giá trị đơn hàng',
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'min_order_value' => 300000,
                'expired_at' => '2026-12-31',
                'status' => 1,
            ],
            [
                'code' => 'FREESHIP',
                'title' => 'Miễn phí giao hàng',
                'discount_type' => 'fixed',
                'discount_value' => 30000,
                'min_order_value' => 200000,
                'expired_at' => '2026-12-31',
                'status' => 1,
            ],
        ];

        foreach ($coupons as $item) {
            Coupon::create($item);
        }
    }
}