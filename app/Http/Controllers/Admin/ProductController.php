<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->with('category')
            ->when(request()->filled('keyword'), function ($query) {
                $keyword = trim(request('keyword'));

                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('slug', 'like', '%' . $keyword . '%')
                        ->orWhere('short_description', 'like', '%' . $keyword . '%')
                        ->orWhere('description', 'like', '%' . $keyword . '%');
                });
            })
            ->when(request()->filled('category_id'), function ($query) {
                $query->where('category_id', request('category_id'));
            })
            ->when(request()->filled('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->when(request()->filled('is_featured'), function ($query) {
                $query->where('is_featured', request('is_featured'));
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        $slug = Str::slug($data['name']);
        $originalSlug = $slug;
        $count = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['slug'] = $slug;

        $product = Product::create($data);

            if (!empty($data['quantity']) && $data['quantity'] > 0) {
                StockImport::create([
                    'product_id' => $product->id,
                    'quantity' => $data['quantity'],
                    'cost_price' => $data['cost_price'] ?? 0,
                    'total_cost' => $data['quantity'] * ($data['cost_price'] ?? 0),
                ]);
            }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công.');
    }

    public function show(Product $product)
    {
        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(Product $product)
    {
        $categories = Category::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($product->name !== $data['name']) {
            $slug = Str::slug($data['name']);
            $originalSlug = $slug;
            $count = 1;

            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $data['slug'] = $slug;
        }

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $oldQuantity = $product->quantity;
        $oldCostPrice = $product->cost_price;

        $product->update($data);

        $newQuantity = $data['quantity'];
        $newCostPrice = $data['cost_price'];

        $diff = $newQuantity - $oldQuantity;

        if ($diff > 0) {
            StockImport::create([
                'product_id' => $product->id,
                'quantity' => $diff,
                'cost_price' => $newCostPrice,
                'total_cost' => $diff * $newCostPrice,
            ]);
        }

        if ($diff < 0) {
            StockImport::create([
                'product_id' => $product->id,
                'quantity' => $diff, // âm
                'cost_price' => $newCostPrice,
                'total_cost' => $diff * $newCostPrice,
            ]);
        }

        if ($diff == 0 && $newCostPrice != $oldCostPrice) {
            StockImport::create([
                'product_id' => $product->id,
                'quantity' => 0,
                'cost_price' => $newCostPrice,
                'total_cost' => 0,
            ]);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công.');
    }
}