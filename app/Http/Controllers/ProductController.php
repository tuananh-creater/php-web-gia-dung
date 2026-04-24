<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->with('category')
            ->where('status', 1);

        if ($request->filled('q')) {
            $keyword = trim($request->q);

            $products->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('short_description', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('category')) {
            $products->whereHas('category', function ($query) use ($request) {
                $query->where('slug', $request->category);
            });
        }

        if ($request->filled('min_price')) {
            $products->whereRaw('COALESCE(sale_price, price) >= ?', [(float) $request->min_price]);
        }

        if ($request->filled('max_price')) {
            $products->whereRaw('COALESCE(sale_price, price) <= ?', [(float) $request->max_price]);
        }

        $sort = $request->get('sort', 'latest');

        switch ($sort) {
            case 'price_asc':
                $products->orderByRaw('COALESCE(sale_price, price) ASC');
                break;

            case 'price_desc':
                $products->orderByRaw('COALESCE(sale_price, price) DESC');
                break;

            case 'name_asc':
                $products->orderBy('name', 'asc');
                break;

            case 'featured':
                $products->orderByDesc('is_featured')
                    ->orderByDesc('id');
                break;

            case 'oldest':
                $products->oldest('id');
                break;

            case 'latest':
            default:
                $products->latest('id');
                break;
        }

        $products = $products->paginate(12)->withQueryString();

        return view('products.index', compact('products', 'categories', 'sort'));
    }

    public function show($slug)
    {
        $product = Product::query()
            ->with([
                'category',
                'images',
                'reviews' => function ($query) {
                    $query->where('is_visible', true)
                        ->with('user')
                        ->latest();
                },
            ])
            ->where('status', 1)
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::query()
            ->where('status', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest('id')
            ->take(4)
            ->get();

        $ratingAverage = round((float) $product->reviews->avg('rating'), 1);
        $ratingCount = $product->reviews->count();

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'ratingAverage',
            'ratingCount'
        ));
    }
}