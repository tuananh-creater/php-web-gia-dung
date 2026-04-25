<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Đồ Nhà Bếp',
                'description' => 'Các dụng cụ nhà bếp như nồi, chảo, xoong, dụng cụ nấu ăn.',
                'image' => 'categories/do-nha-bep.jpg',
            ],
            [
                'name' => 'Điện Gia Dụng',
                'description' => 'Các thiết bị điện gia dụng như nồi cơm, ấm siêu tốc.',
                'image' => 'categories/dien-gia-dung.jpg',
            ],
            [
                'name' => 'Tủ Lạnh',
                'description' => 'Các loại tủ lạnh tiết kiệm điện, dung tích đa dạng.',
                'image' => 'categories/tu-lanh.jpg',
            ],
            [
                'name' => 'Máy Giặt',
                'description' => 'Máy giặt cửa trên, cửa trước hiện đại.',
                'image' => 'categories/may-giat.jpg',
            ],
            [
                'name' => 'Thiết Bị Gia Dụng Khác',
                'description' => 'Các thiết bị gia dụng khác phục vụ đời sống.',
                'image' => 'categories/thiet-bi-khac.jpg',
            ],
        ];

        foreach ($categories as $item) {
            Category::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'image' => $item['image'],
                'description' => $item['description'],
                'status' => 1,
            ]);
        }
    }
}