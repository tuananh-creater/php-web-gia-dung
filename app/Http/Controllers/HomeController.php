<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Post;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::query()
            ->where('status', 1)
            ->orderBy('sort_order')
            ->latest('id')
            ->get();

        $categories = Category::query()
            ->where('status', 1)
            ->latest('id')
            ->take(8)
            ->get();

        $saleProducts = Product::query()
            ->with('category')
            ->where('status', 1)
            ->whereNotNull('sale_price')
            ->orderByDesc('id')
            ->take(8)
            ->get();

        $featuredProducts = Product::query()
            ->with('category')
            ->where('status', 1)
            ->where('is_featured', 1)
            ->orderByDesc('id')
            ->take(8)
            ->get();

        $coupons = Coupon::query()
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('expired_at')
                      ->orWhereDate('expired_at', '>=', now()->toDateString());
            })
            ->latest('id')
            ->take(4)
            ->get();

        $posts = Post::query()
            ->where('status', 1)
            ->latest('id')
            ->take(3)
            ->get();

        return view('home', compact(
            'banners',
            'categories',
            'saleProducts',
            'featuredProducts',
            'coupons',
            'posts'
        ));
    }
}