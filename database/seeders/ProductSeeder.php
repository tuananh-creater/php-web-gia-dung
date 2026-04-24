<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $nhaBep = Category::where('slug', 'do-nha-bep')->first();
        $dienGiaDung = Category::where('slug', 'dien-gia-dung')->first();
        $tuLanh = Category::where('slug', 'tu-lanh')->first();
        $mayGiat = Category::where('slug', 'may-giat')->first();

        $products = [
            [
                'category_id' => $nhaBep?->id,
                'name' => 'Nồi inox 3 lớp cao cấp',
                'price' => 850000,
                'sale_price' => 750000,
                'quantity' => 20,
                'image' => 'products/noi-inox.jpg',
                'short_description' => 'Nồi inox bền đẹp, dẫn nhiệt tốt.',
                'description' => 'Nồi inox 3 lớp chống cháy, phù hợp nấu mọi loại thực phẩm.',
                'is_featured' => 1,
            ],
            [
                'category_id' => $nhaBep?->id,
                'name' => 'Chảo chống dính cao cấp',
                'price' => 500000,
                'sale_price' => 420000,
                'quantity' => 30,
                'image' => 'products/chao-chong-dinh.jpg',
                'short_description' => 'Chảo chống dính tiện lợi.',
                'description' => 'Lớp chống dính cao cấp, dễ vệ sinh, an toàn cho sức khỏe.',
                'is_featured' => 1,
            ],
            [
                'category_id' => $nhaBep?->id,
                'name' => 'Bộ xoong nồi 5 món',
                'price' => 1500000,
                'sale_price' => 1290000,
                'quantity' => 15,
                'image' => 'products/bo-xoong-noi.jpg',
                'short_description' => 'Bộ nồi đầy đủ cho gia đình.',
                'description' => 'Gồm 5 món tiện dụng, chất liệu inox cao cấp.',
                'is_featured' => 1,
            ],
            [
                'category_id' => $dienGiaDung?->id,
                'name' => 'Nồi cơm điện 1.8L',
                'price' => 900000,
                'sale_price' => 790000,
                'quantity' => 25,
                'image' => 'products/noi-com-dien.jpg',
                'short_description' => 'Nồi cơm điện tiện lợi.',
                'description' => 'Dung tích 1.8L phù hợp gia đình 4-6 người.',
                'is_featured' => 1,
            ],
            [
                'category_id' => $dienGiaDung?->id,
                'name' => 'Ấm siêu tốc 2L',
                'price' => 350000,
                'sale_price' => 290000,
                'quantity' => 40,
                'image' => 'products/am-sieu-toc.jpg',
                'short_description' => 'Đun nước nhanh chóng.',
                'description' => 'Công suất lớn, tự ngắt khi sôi.',
                'is_featured' => 0,
            ],
            [
                'category_id' => $tuLanh?->id,
                'name' => 'Tủ lạnh 2 cánh Inverter',
                'price' => 9500000,
                'sale_price' => 8900000,
                'quantity' => 10,
                'image' => 'products/tu-lanh.jpg',
                'short_description' => 'Tiết kiệm điện năng.',
                'description' => 'Công nghệ Inverter hiện đại, dung tích lớn.',
                'is_featured' => 1,
            ],
            [
                'category_id' => $mayGiat?->id,
                'name' => 'Máy giặt cửa trước 8kg',
                'price' => 7200000,
                'sale_price' => 6800000,
                'quantity' => 8,
                'image' => 'products/may-giat.jpg',
                'short_description' => 'Giặt sạch hiệu quả.',
                'description' => 'Công nghệ giặt hiện đại, tiết kiệm nước.',
                'is_featured' => 1,
            ],
        ];

        foreach ($products as $item) {
            if (!$item['category_id']) {
                continue;
            }

            Product::create([
                'category_id' => $item['category_id'],
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'price' => $item['price'],
                'sale_price' => $item['sale_price'],
                'quantity' => $item['quantity'],
                'image' => $item['image'],
                'short_description' => $item['short_description'],
                'description' => $item['description'],
                'is_featured' => $item['is_featured'],
                'status' => 1,
            ]);
        }
    }
}