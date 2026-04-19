<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Đồ gia dụng cao cấp',
                'subtitle' => 'Giảm giá lên đến 40%',
                'image' => 'banners/banner-1.jpg',
                'link' => '/san-pham',
                'sort_order' => 1,
                'status' => 1,
            ],
            [
                'title' => 'Khuyến mãi điện gia dụng',
                'subtitle' => 'Mua nồi cơm, tủ lạnh ưu đãi lớn',
                'image' => 'banners/banner-2.jpg',
                'link' => '/san-pham',
                'sort_order' => 2,
                'status' => 1,
            ],
            [
                'title' => 'Sắm nhà tiện nghi',
                'subtitle' => 'Máy giặt, chảo chống dính giá tốt',
                'image' => 'banners/banner-3.jpg',
                'link' => '/san-pham',
                'sort_order' => 3,
                'status' => 1,
            ],
        ];

        foreach ($banners as $item) {
            Banner::create($item);
        }
    }
}