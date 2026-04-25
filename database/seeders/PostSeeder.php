<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Cách chọn nồi inox chất lượng cho gia đình',
                'image' => 'posts/post-1.jpg',
                'summary' => 'Hướng dẫn chọn nồi inox bền đẹp, an toàn và phù hợp nhu cầu sử dụng.',
                'content' => 'Nồi inox là vật dụng không thể thiếu trong gian bếp. Khi chọn nồi, bạn nên chú ý đến chất liệu, độ dày và khả năng dẫn nhiệt để đảm bảo hiệu quả nấu nướng và độ bền lâu dài.',
                'status' => 1,
            ],
            [
                'title' => 'Kinh nghiệm mua chảo chống dính tốt nhất',
                'image' => 'posts/post-2.jpg',
                'summary' => 'Những tiêu chí quan trọng khi chọn chảo chống dính an toàn và tiện lợi.',
                'content' => 'Chảo chống dính giúp việc nấu ăn trở nên dễ dàng hơn. Bạn nên chọn loại chảo có lớp chống dính cao cấp, không chứa chất độc hại và dễ vệ sinh sau khi sử dụng.',
                'status' => 1,
            ],
            [
                'title' => 'Tủ lạnh inverter có thực sự tiết kiệm điện?',
                'image' => 'posts/post-3.jpg',
                'summary' => 'Phân tích ưu nhược điểm của tủ lạnh inverter trong gia đình.',
                'content' => 'Tủ lạnh inverter giúp tiết kiệm điện năng nhờ khả năng điều chỉnh công suất linh hoạt. Đây là lựa chọn phù hợp cho các gia đình muốn giảm chi phí điện hàng tháng.',
                'status' => 1,
            ],
            [
                'title' => 'Nên chọn máy giặt cửa trước hay cửa trên?',
                'image' => 'posts/post-4.jpg',
                'summary' => 'So sánh ưu nhược điểm giữa hai loại máy giặt phổ biến.',
                'content' => 'Máy giặt cửa trước tiết kiệm nước và giặt sạch hơn, trong khi máy giặt cửa trên có giá thành rẻ và dễ sử dụng. Tùy vào nhu cầu mà bạn có thể lựa chọn phù hợp.',
                'status' => 1,
            ],
        ];

        foreach ($posts as $item) {
            Post::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'image' => $item['image'],
                'summary' => $item['summary'],
                'content' => $item['content'],
                'status' => $item['status'],
            ]);
        }
    }
}