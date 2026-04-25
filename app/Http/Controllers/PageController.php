<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $stats = [
            'categories' => Category::where('status', 1)->count(),
            'products' => Product::where('status', 1)->count(),
            'posts' => Post::where('status', 1)->count(),
            'orders' => Order::whereIn('status', ['confirmed', 'shipping', 'completed'])->count(),
        ];

        $values = [
            [
                'title' => 'Chất liệu tự nhiên',
                'desc' => 'Ưu tiên tre, mây, gỗ và các vật liệu gần gũi với thiên nhiên để tạo nên không gian sống mộc mạc, ấm áp.',
                'icon' => 'bi-flower1',
            ],
            [
                'title' => 'Thủ công tinh tế',
                'desc' => 'Mỗi sản phẩm được hoàn thiện với sự chỉn chu trong từng chi tiết, mang vẻ đẹp vừa truyền thống vừa hiện đại.',
                'icon' => 'bi-stars',
            ],
            [
                'title' => 'Tối giản & bền vững',
                'desc' => 'Thiết kế hướng đến tính ứng dụng cao, lâu bền và phù hợp với nhiều phong cách sống khác nhau.',
                'icon' => 'bi-recycle',
            ],
        ];

        $services = [
            'Tư vấn chọn sản phẩm phù hợp với không gian sống',
            'Gợi ý phối đồ decor theo từng phong cách',
            'Hỗ trợ đặt hàng nhanh, giao hàng toàn quốc',
            'Chăm sóc khách hàng tận tâm sau bán',
        ];

        return view('pages.about', compact('stats', 'values', 'services'));
    }

    public function faq()
    {
        $faqs = [
            [
                'question' => 'Làm thế nào để đặt hàng trên website?',
                'answer' => 'Bạn chọn sản phẩm, thêm vào giỏ hàng, điền thông tin nhận hàng và xác nhận đặt hàng. Sau đó hệ thống sẽ ghi nhận đơn và gửi thông báo xác nhận.',
            ],
            [
                'question' => 'Website có hỗ trợ thanh toán khi nhận hàng không?',
                'answer' => 'Có. Bạn có thể chọn hình thức thanh toán khi nhận hàng (COD) hoặc chuyển khoản ngân hàng nếu hệ thống đã bật phương thức này.',
            ],
            [
                'question' => 'Tôi có thể đổi trả sản phẩm không?',
                'answer' => 'Có thể. Bạn nên liên hệ sớm với cửa hàng sau khi nhận hàng để được hỗ trợ đổi trả nếu sản phẩm gặp lỗi hoặc không đúng mô tả.',
            ],
            [
                'question' => 'Bao lâu tôi sẽ nhận được hàng?',
                'answer' => 'Thông thường đơn hàng nội thành sẽ nhanh hơn, còn đơn liên tỉnh sẽ phụ thuộc đơn vị vận chuyển. Bạn có thể theo dõi trạng thái đơn trong tài khoản của mình.',
            ],
            [
                'question' => 'Sản phẩm thủ công có giống hoàn toàn ảnh mẫu không?',
                'answer' => 'Vì là sản phẩm thủ công nên có thể có chênh lệch nhỏ về màu sắc, họa tiết hoặc chi tiết hoàn thiện. Đây là nét đặc trưng riêng của đồ handmade.',
            ],
            [
                'question' => 'Tôi có thể liên hệ tư vấn trước khi mua không?',
                'answer' => 'Có. Bạn có thể dùng trang Liên hệ để gửi câu hỏi, hoặc gọi hotline để được tư vấn nhanh hơn.',
            ],
        ];

        return view('pages.faq', compact('faqs'));
    }

    public function collections(Request $request)
    {
        $categories = Category::query()
            ->where('status', 1)
            ->withCount([
                'products' => function ($query) {
                    $query->where('status', 1);
                }
            ])
            ->orderBy('name')
            ->get();

        $selectedCategory = null;

        if ($request->filled('category')) {
            $selectedCategory = $categories->firstWhere('slug', $request->category);
        }

        if (!$selectedCategory) {
            $selectedCategory = $categories->first();
        }

        $products = Product::query()
            ->with('category')
            ->where('status', 1)
            ->when(
                $selectedCategory,
                fn ($query) => $query->where('category_id', $selectedCategory->id),
                fn ($query) => $query->whereRaw('1 = 0')
            )
            ->latest('id')
            ->paginate(9)
            ->withQueryString();

        $featuredCollections = $categories
            ->sortByDesc('products_count')
            ->take(3)
            ->values();

        return view('pages.collections', compact(
            'categories',
            'selectedCategory',
            'products',
            'featuredCollections'
        ));
    }
}